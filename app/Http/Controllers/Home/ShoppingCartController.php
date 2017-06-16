<?php

namespace App\Http\Controllers\Home;


use App\Repositories\ActivityRepository;
use App\Repositories\CargoRepository;
use App\Repositories\GoodsAttributeRepository;
use App\Repositories\GoodsLabelRepository;
use App\Repositories\RelGoodsActivityRepository;
use App\Repositories\ShoppingCartRepositories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShoppingCartController extends Controller
{
    /**
     * @var CargoRepository
     */
    protected $cargo;

    /**
     * @var string
     */
    protected $hashShoppingCart;

    /**
     * @var string
     */
    protected $listShoppingCart;

    /**
     * @var GoodsLabelRepository
     * @author zhulinjie
     */
    protected $goodsLabel;

    /**
     * @var GoodsAttributeRepository
     */
    protected $goodsAttr;

    /**
     * @var
     * @author zhulinjie
     */
    protected $activity;

    /**
     * @var
     * @author zhulinjie
     */
    protected $relGoodsActivity;

    /**
     *
     */
    protected $hashCargoInfo;

    /**
     * ShoppingCartController constructor.
     * @param CargoRepository $cargoRepository
     * @param GoodsLabelRepository $goodsLabelRepository
     * @param GoodsAttributeRepository $goodsAttributeRepository
     * @param ActivityRepository $activityRepository
     * @param RelGoodsActivityRepository $relGoodsActivityRepository
     */
    public function __construct
    (
        CargoRepository $cargoRepository,
        GoodsLabelRepository $goodsLabelRepository,
        GoodsAttributeRepository $goodsAttributeRepository,
        ActivityRepository $activityRepository,
        RelGoodsActivityRepository $relGoodsActivityRepository
    )
    {
        $this->cargo = $cargoRepository;
        $this->hashShoppingCart = HASH_SHOPPING_CART_INFO_;
        $this->listShoppingCart = LIST_SHOPPING_CART_INFO_;
        $this->hashCargoInfo = HASH_CARGO_INFO_;
        $this->goodsLabel = $goodsLabelRepository;
        $this->goodsAttr = $goodsAttributeRepository;
        $this->activity = $activityRepository;
        $this->relGoodsActivity = $relGoodsActivityRepository;
    }

    /**
     * 购物车列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function index()
    {
        // 初始化返回数组
        $data = [];

        // 获取用户ID
        $userId = \Session::get('user')->user_id;

        // 先从缓存中获取购物车信息
        $listLength = \Redis::lLen($this->listShoppingCart . $userId);
        if ($listLength) {
            // 获取hash的KEY便于取出所有商品
            $keys = \Redis::lrange($this->listShoppingCart . $userId, 0, $listLength);
            if (empty($keys)) {
                return responseMsg(['data' => []]);
            }

            // 循环遍历购物车hash的key
            foreach ($keys as $key => $item) {
                // 从缓存中获取购物车信息
                $cart = \Redis::hgetAll($item);

                // 获取货品信息，先从redis里面获取，如果redis中没有缓存再从数据库中获取并写入到缓存
                $cargo = \Redis::hgetall($this->hashCargoInfo . $cart['cargo_id']);
                if (empty($cargo)) {
                    $cargo = $this->cargo->find(['id' => $cart['cargo_id']])->toArray();
                    if (empty($cargo)) {
                        responseMsg('非法操作', 400);
                    }
                    if (!\Redis::hmset(HASH_CARGO_INFO_ . $cart['cargo_id'], $cargo)) {
                        responseMsg('货品信息写入缓存失败', 400);
                    }
                }

                // 获取正在进行的活动
                $currentTimestamp = time();
                $activity = $this->activity->ongoingActivities($currentTimestamp);
                // 货品正在做活动并且购买数量大于用来做活动的货品数量则不可以享受活动价
                if ($activity) {
                    $activity->cargoActivity = $this->relGoodsActivity->find(['cargo_id' => $cargo['id'], 'activity_id' => $activity->id]);
                }

                if ($activity && $activity->cargoActivity) {
                    if ($cart['shopping_number'] <= $activity->cargoActivity->number) {
                        // 活动价
                        $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargo['id'], 'price', $activity->cargoActivity->promotion_price);
                        $cart['price'] = $activity->cargoActivity->promotion_price;
                    } else {
                        $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargo['id'], 'price', $cargo['cargo_price']);
                        $cart['price'] = $cargo['cargo_price'];
                    }
                } else {
                    $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargo['id'], 'price', $cargo['cargo_price']);
                    $cart['price'] = $cargo['cargo_price'];
                }

                // 失败的情况 如果操作成功，哈希表中域字段已经存在且旧值已被新值覆盖则返回0
                if ($res !== 0) {
                    return responseMsg('修改购物车价格失败', 400);
                }

                if ($cargo['inventory'] == 0) {
                    $cargo['shopping_number'] = 0;
                }

                // 组装数据
                $data[] = array_merge($cart, $this->standard($cargo));
            }
        } else {
            $data = [];
        }

        // 返回视图
        return view('home.shoppingCart.index', ['data' => $data]);
    }

    /**
     * 获取货品规格信息
     *
     * @param $cargo
     * @return mixed
     * @author zhulinjie
     */
    private function standard($cargo)
    {
        // 获取商品标签
        if (!empty($cargo['cargo_ids'])) {
            // 商品标签转为数组
            $labels = json_decode($cargo['cargo_ids'], 1);
            // 便利标签
            foreach ($labels as $k => $v) {
                // 查询商品标签
                $label = $this->goodsLabel->find(['id' => $k]);
                // 查询商品标签值
                $attr = $this->goodsAttr->find(['id' => $v]);
                // 拼装货品信息
                if (!empty($label) && !empty($attr)) {
                    $cargo['label'][$v] = [
                        'label_name' => $label->goods_label_name,
                        'attr_name' => $attr->goods_label_name
                    ];

                }
            }
        }
        return $cargo;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 添加购物车
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function store(Request $request)
    {
        // 获取货品ID
        $cargoId = $request['cargo_id'];
        // 加入购物车的数量
        $number = empty($request['number']) ? 1 : $request['number'];
        // 获取用户ID
        $userId = \Session::get('user')->user_id;

        // 获取货品信息，先从redis里面获取，如果redis中没有缓存再从数据库中获取并写入到缓存
        $cargo = \Redis::hgetall(HASH_CARGO_INFO_ . $cargoId);
        if (empty($cargo)) {
            $cargo = $this->cargo->find(['id' => $cargoId])->toArray();
            if (empty($cargo)) {
                responseMsg('非法操作', 400);
            }
            if (!\Redis::hmset(HASH_CARGO_INFO_ . $cargoId, $cargo)) {
                responseMsg('加入购物车失败', 400);
            }
        }

        // 无库存的情况
        if ($cargo['inventory'] == 0) {
            return responseMsg('该货品已被抢光', 414);
        }

        // 获取正在进行的活动
        $currentTimestamp = time();
        $activity = $this->activity->ongoingActivities($currentTimestamp);
        // 此商品是否存在于活动当中
        if ($activity) {
            $activity->cargoActivity = $this->relGoodsActivity->find(['cargo_id' => $cargoId, 'activity_id' => $activity->id]);
        }

        // 判断该条货品的key是否存在
        if (\Redis::exists($this->hashShoppingCart . $userId . ':' . $cargoId)) {
            $cart = \Redis::hgetall($this->hashShoppingCart . $userId . ':' . $cargoId);

            // 如果该货品有活动并且用户抢购的数量小于等于用来做活动的货品数量才享受活动价
            if ($activity && $activity->cargoActivity) {
                // 更改为活动价
                if (($request['number'] + $cart['shopping_number']) <= $activity->cargoActivity->number) {
                    $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'price', $activity->cargoActivity->promotion_price);
                }else{
                    $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'price', $cargo['cargo_price']);
                }
            } else {
                // 更改为原价
                $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'price', $cargo['cargo_price']);
            }

            // 失败的情况 如果操作成功，哈希表中域字段已经存在且旧值已被新值覆盖则返回0
            if ($res !== 0) {
                return responseMsg('添加购物车失败!', 400);
            }
            \Redis::hincrBy($this->hashShoppingCart . $userId . ':' . $cargoId, 'shopping_number', $number);
        } else {
            $data = [];
            $data['user_id'] = $userId;
            $data['cargo_id'] = $cargoId;

            // 如果该货品有活动并且用户抢购的数量小于等于用来做活动的货品数量才享受活动价
            if ($activity && $activity->cargoActivity) {
                // 更改为活动价
                if ($request['number'] <= $activity->cargoActivity) {
                    $data['price'] = $activity->cargoActivity->promotion_price;
                } else {
                    $data['price'] = $cargo['cargo_price'];
                }
            } else {
                $data['price'] = $cargo['cargo_price'];
            }

            // 添加购买数量
            $data['shopping_number'] = $number;

            // 存入缓存
            $hashResult = \Redis::hMset($this->hashShoppingCart . $userId . ':' . $cargoId, $data);

            if (!$hashResult) {
                // 记录log日志
                return responseMsg('加入购物车失败!', 400);
            }

            // 存入列表
            $listResult = \Redis::rpush($this->listShoppingCart . $userId, $this->hashShoppingCart . $userId . ':' . $cargoId);

            // 列表数据存入是否成功
            if (!$listResult) {
                // 不成功 删除hash记录
                $hDelResult = \Redis::hdel($this->hashShoppingCart . $userId . ':' . $cargoId);
                if (!$hDelResult) {
                    // 记录log日志
                    return responseMsg('加入购物车失败!', 400);

                    // 记录失败数据
                }
            }
        }
        // 返回数量,以便修改页面购物车数量
        return responseMsg($number);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除购物车记录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function destroy(Request $request)
    {
        // 获取用户ID
        $userId = \Session::get('user')->user_id;
        // 获取货品ID
        foreach ($request['cargoId'] as $value) {
            // 判断货品是否存在缓存中
            if (\Redis::exists($this->hashShoppingCart . $userId . ':' . $value)) {
                // 从缓存中删除货品
                \Redis::del($this->hashShoppingCart . $userId . ':' . $value);
            }
            // 删除缓存中list记录
            \Redis::lRem($this->listShoppingCart . $userId, 0, $this->hashShoppingCart . $userId . ':' . $value);
        }

        $cartLen = \Redis::lLen($this->listShoppingCart . $userId);

        // 返回信息
        return responseMsg($cartLen);
    }

    /**
     * 检测库存是否充足
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function checkShoppingCart(Request $request)
    {
        // 获取用户ID
        $userId = \Session::get('user')->user_id;

        // 获取货品ID
        $cargoId = $request['cargoId'];

        // 获取货品信息
        $cargo = \Redis::hgetall(HASH_CARGO_INFO_ . $request['cargoId']);
        if (empty($cargo)) {
            $cargo = $this->cargo->find(['id' => $request['cargoId']])->toArray();
            if (empty($cargo)) {
                return responseMsg('非法操作', 400);
            }
            if (!\Redis::hmset(HASH_CARGO_INFO_ . $request['cargoId'], $cargo)) {
                responseMsg('货品信息写入缓存失败', 400);
            }
        }

        // 无货的情况
        if ($cargo['inventory'] == 0) {
            return responseMsg('该商品已被抢光', 414);
        }

        // 库存不足的情况
        if ($request['number'] > $cargo['inventory']) {
            \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'shopping_number', $cargo['inventory']);
            // 库存不充足
            return responseMsg($cargo['inventory'], 412);
        }

        // 首先判断购买数量是否大于限定值
        if ($request['number'] > 200) {
            \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'shopping_number', 200);
            return responseMsg('商品数量不能大于200', 410);
        }

        // 获取正在进行的活动
        $currentTimestamp = time();
        $activity = $this->activity->ongoingActivities($currentTimestamp);
        if ($activity) {
            $activity->cargoActivity = $this->relGoodsActivity->find(['cargo_id' => $request['cargoId'], 'activity_id' => $activity->id]);
        }

        // 货品正在做活动并且购买数量大于用来做活动的货品数量则不可以享受活动价
        if ($activity && $activity->cargoActivity) {
            $data['promotion_number'] = $activity->cargoActivity->number;
            // 更改为活动价
            if ($request['number'] <= $activity->cargoActivity->number) {
                $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'price', $activity->cargoActivity->promotion_price);
                $data['price'] = $activity->cargoActivity->promotion_price;
            // 更改为原价
            } else {
                $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'price', $cargo['cargo_price']);
                $data['price'] = $cargo['cargo_price'];
            }
            // 失败的情况 如果操作成功，哈希表中域字段已经存在且旧值已被新值覆盖则返回0
            if ($res !== 0) {
                return responseMsg('操作失败!', 400);
            }
        } else {
            \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'price', $cargo['cargo_price']);
            $data['price'] = $cargo['cargo_price'];
        }
        
        // 更新数量
        $res = \Redis::hset($this->hashShoppingCart . $userId . ':' . $cargoId, 'shopping_number', $request['number']);

        if ($res !== 0) {
            return responseMsg('新增购买数量失败', 400);
        }

        // 库存充足
        return responseMsg($data);
    }
}
