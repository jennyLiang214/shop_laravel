<?php

namespace App\Http\Controllers\Home;

use App\Repositories\ActivityRepository;
use App\Repositories\AddressRepository;
use App\Repositories\CargoRepository;
use App\Repositories\GoodsAttributeRepository;
use App\Repositories\GoodsLabelRepository;
use App\Repositories\OrderDetailsRepository;
use App\Repositories\OrdersRepository;
use App\Repositories\RelGoodsActivityRepository;
use App\Tools\Common;
use App\Tools\LogOperation;
use Carbon\Carbon;
use EasyWeChat\Payment\Order;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;

class OrderController extends Controller
{

    /**
     * 购物车货品Hash KEY
     *
     * @var
     */
    protected $hashShoppingCart;
    /**
     *  购物车货品List KEY
     *
     * @var string
     */
    protected $listShoppingCart;
    /**
     * 货品
     *
     * @var CargoRepository
     */
    protected $cargo;
    /**
     * 商品标签
     *
     * @var GoodsLabel
     */
    protected $goodsLabel;
    /**
     * 商品标签值
     *
     * @var GoodsAttributeRepository
     */
    protected $goodsAttr;
    /**
     * 收货地址
     *
     * @var AddressRepository
     */
    protected $address;
    /**
     * 订单
     *
     * @var OrdersRepository
     */
    protected $order;
    /**
     * 日志
     *
     * @var LogOperation
     */
    protected $log;
    /**
     * 订单详情
     *
     * @var OrderDetailsRepository
     */
    protected $orderDetails;
    /**
     * @var ActivityRepository
     */
    protected $activity;
    /**
     * @var ActivityRepository
     */
    protected $relGoodsActivity;
    /**
     * @var string
     */
    protected $hashCargoInfo;

    public function __construct
    (

        CargoRepository $cargoRepository,
        GoodsLabelRepository $goodsLabelRepository,
        GoodsAttributeRepository $goodsAttributeRepository,
        AddressRepository $addressRepository,
        OrdersRepository $ordersRepository,
        LogOperation $logOperation,
        OrderDetailsRepository $orderDetailsRepository,
        ActivityRepository $activityRepository,
        RelGoodsActivityRepository $relGoodsActivityRepository

    )
    {
        $this->hashShoppingCart = HASH_SHOPPING_CART_INFO_;
        $this->listShoppingCart = LIST_SHOPPING_CART_INFO_;
        $this->hashCargoInfo = HASH_CARGO_INFO_;
        $this->cargo = $cargoRepository;
        $this->goodsLabel = $goodsLabelRepository;
        $this->goodsAttr = $goodsAttributeRepository;
        $this->address = $addressRepository;
        $this->order = $ordersRepository;
        $this->log = $logOperation;
        $this->orderDetails = $orderDetailsRepository;
        $this->activity = $activityRepository;
        $this->relGoodsActivity = $relGoodsActivityRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status)
    {

        $where['user_id'] = \Session::get('user')->user_id;
        $where['del_status'] = 1;
        if (!empty($status)) {
            $where['order_status'] = $status;
        }
        // 根据状态查询订单副表，并对其进行分页
        $result = $this->orderDetails->getListPage($where, 10);
        // 初始化返回数组
        $data = [];
        if (!empty($result)) {
            $requestArray = $result->toArray();
            foreach ($requestArray['data'] as $item) {

                $labels = json_decode($item['cargo_message']['cargo_ids'], 1);
                foreach ($labels as $k => $v) {
                    // 查询商品标签
                    $label = $this->goodsLabel->find(['id' => $k]);
                    // 查询商品标签值
                    $attr = $this->goodsAttr->find(['id' => $v]);
                    // 拼装货品信息
                    if (!empty($label) && !empty($attr)) {
                        $item['label'][$v] = [
                            'label_name' => $label->goods_label_name,
                            'attr_name' => $attr->goods_label_name
                        ];

                    }
                }
                // 获取订单主表信息
                $order = $this->order->find(['guid' => $item['order_guid']]);
                if (!empty($order)) {
                    $data[$item['order_guid']]['order'] = $order->toArray();
                    $messages = json_decode($order->goods_message, 1);

                    foreach ($messages as $message) {
                        if ($item['cargo_id'] == $message['id']) {
                            unset($message['id']);
                            $item = array_merge($item, $message);
                        }
                    }

                }

                $data[$item['order_guid']]['orderDetails'][] = $item;
            }

        }

        return view('home.orders.index', ['data' => $data, 'page' => $result]);
    }

    /**
     * 确认订单
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function create(Request $request)
    {

        // 获取用户ID
        $userId = \Session::get('user')->user_id;
        // 获取货品ID
        $cargoIds = explode(',', $request['cargo_id']);
        // 组装商品信息
        $data = [];
        // 购物车是否存在数据
        if (!empty(\Redis::lLen($this->listShoppingCart . $userId))) {
            // 便利购物车选中的商品
            foreach ($cargoIds as $key => $item) {
                // 读取缓存中购物车数据 不存在从数据库中读取
                $cart = \Redis::hGetAll($this->hashShoppingCart . $userId . ':' . $item);
                // 读取缓存中商品数据 不存在从数据库中读取
                $cargo = \Redis::hGetAll($this->hashCargoInfo . $item);
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
                $data['goods'][] = array_merge($cart, $cargo);
            }
            // 获取用户收货地址
            $address = $this->address->select(['user_id' => $userId]);
            // 判断用户是否获取成功
            if (empty($address)) {
                // 获取失败，默认为空
                $address = [];
            }
            // 继续组装返回数据
            $data['address'] = $address;
        }
        // 返回视图
        return view('home.order.index', ['data' => $data]);

    }

    /**
     * 添加订单
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function store(Request $request)
    {
        // 获取用户ID
        $userId = \Session::get('user')->user_id;
        // 收货地址
        $address = $this->address->find(['id' => $request['address_id']]);
        if (empty($address)) {
            return responseMsg('非法操作', 400);
        }
        // 初始化总金额
        $total = 0;
        // 组装订单表数据
        $orderData = [];
        $orderData['user_id'] = $userId;
        $orderData['guid'] = Common::getUuid(); // 订单号
        $orderData['pay_type'] = $request['pay_type']; // 支付方式
        $orderData['address_message'] = json_encode($address->toArray());// 收货地址
        // 获取单个货品做准备
        $cargoes = json_decode($request['goods_message'], 1);
        $time = time();
        // 获取正在进行的活动
        $activity = $this->activity->ongoingActivities($time);
        foreach ($cargoes as $key => $item) {

            $cart = \Redis::hGetAll($this->hashShoppingCart . $userId . ':' . $item['cargo_id']);
            $cargo = \Redis::hGetAll($this->hashCargoInfo . $item['cargo_id']);
            // 判断是否存在库存
            $tmp = array_merge($cart, $cargo);

            if (!empty($tmp['inventory'])) {
                // 判断购买数量是否已经超出库存
                if ($tmp['inventory'] < $tmp['shopping_number']) {
                    $tmp['shopping_number'] = $tmp['inventory'];
                }
                // 组装订单详情表数据
                $orderDetailsData[$key]['user_id'] = $userId;            // 购买用户
                $orderDetailsData[$key]['cargo_id'] = $tmp['id'];       // 货品ID
                $orderDetailsData[$key]['goods_id'] = $tmp['goods_id']; // 商品ID
                $orderDetailsData[$key]['order_guid'] = $orderData['guid'];// 订单ID
                $orderDetailsData[$key]['cargo_price'] = $tmp['price']; // 货品价格
                $orderDetailsData[$key]['commodity_number'] = $tmp['shopping_number']; // 购买数量
                $orderDetailsData[$key]['addtime'] = $time; // 设置下单时间
                // 记录商品进行的活动
                $cargoActivity = '';
                if ($activity) {
                    $cargoActivity = $this->relGoodsActivity->find(['cargo_id' => $tmp['id'], 'activity_id' => $activity->id]);
                }
                // 购买商品记录
                $goodsMessage[$key] = [
                    'id' => $tmp['id'],
                    'cargo_price' => $tmp['price'],
                    'shopping_number' => $tmp['shopping_number'],
                    'cargo_title' => $tmp['cargo_name'],
                    'cargo_activity' => empty($cargoActivity) ? '' : $activity->id
                ];
                // 计算订单总金额
                $total += $tmp['price'] * $tmp['shopping_number'];
            }
        }
        // 判断 订单中是否存在货品
        if (empty($orderDetailsData)) {
            // 返回
            return responseMsg('订单内不存在商品或商品已售罄', 400);
        }
        // 组装订单表数据
        $orderData['goods_message'] = json_encode($goodsMessage);
        $orderData['total_amount'] = $total;
        try {
            // 开始事物
            \DB::beginTransaction();
            // 插入订单
            $orderResult = $this->order->insert($orderData);
            if (empty($orderResult)) {
                // 抛出异常
                throw new Exception(config('log.systemLog')[9]);
            }
            // 插入订单详情
            $orderDetailsResult = $this->orderDetails->insertManyData($orderDetailsData);
            if (empty($orderDetailsResult)) {
                // 抛出异常
                throw new Exception(config('log.systemLog')[10]);
            }
            // 减去库存
            foreach ($orderDetailsData as $item) {
                // 从缓存中读取库存
                $inventory = \Redis::hget($this->hashCargoInfo . $item['cargo_id'], 'inventory');
                // 现有库存
                if ($inventory >= 1) {
                    $number = $inventory - $item['commodity_number'];
                    // 修改库存
                    $cargoResult = $this->cargo->update(['id' => $item['cargo_id']], ['inventory' => $number]);
                    // 不成功抛出异常
                    if (empty($cargoResult)) {
                        throw new Exception(config('log.systemLog')[18]);
                    }
                    // 修改缓存数据
                    \Redis::hSet($this->hashCargoInfo . $item['cargo_id'], 'inventory', $number);
                }

            }
            $result = $this->payType($orderData['pay_type'], $goodsMessage, $total, $orderData['guid']);
            if (empty($result)) {
                throw new Exception(config('log.systemLog')[12]);
            }

            \DB::commit();
            // 下单成功从购物车里删除该货品
            foreach ($cargoes as $item) {
                $delResult = \Redis::del($this->hashShoppingCart . $userId . ':' . $item['cargo_id']);
                if (empty($delResult)) {
                    $logMessage = Common::logMessageForInside($userId, config('log.systemLog')[11], $item);
                    $this->log->writeSystemLog($logMessage);
                }
                \Redis::lRem($this->listShoppingCart . $userId, 0, $this->hashShoppingCart . $userId . ':' . $item['cargo_id']);
            }


            return responseMsg($result, 200);

        } catch (Exception $e) {
            // 事物回滚
            \DB::rollBack();
            // 组装填写log日志
            $logMessage = Common::logMessageForInside($userId, $e->getMessage());
            // 写入log日志
            $this->log->writeSystemLog($logMessage);
            // 返回失败信息
            return responseMsg('下单失败', 400);
        }


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
        $result = $this->orderDetails->update(['id' => $id], ['order_status' => $request['order_status']]);
        if (!empty($result)) {
            return responseMsg('收货成功');
        }
        return responseMsg('失败', 400);
    }

    /**
     * 删除订单
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function destroy($id)
    {
        // 删除
        $result = $this->orderDetails->update(['id' => $id], ['del_status' => 2]);
        // 判断结果
        if (!empty($result)) {
            // 成功
            return responseMsg('删除成功');
        }

        // 失败
        return responseMsg('删除失败', 400);

    }


    /**
     * 支付类型
     *
     * @param $type
     * @param $data
     * @param $total
     * @return mixed
     * @author zhangyuchao
     */
    private function payType($type, $data, $total, $order_guid)
    {

        switch ($type) {
            case 1:
                $tmp = $this->weiXinPay($data, $total, $order_guid);
                break;
            case 2:
                $tmp = $this->aliPay($data, $total, $order_guid);
                break;
        }

        return $tmp;
    }

    /**
     * 支付宝支付
     *
     * @param $data
     * @param $total
     * @return mixed
     * @author zhangyuchao
     */
    private function aliPay($data, $total, $order_guid)
    {
        // 创建支付单。
        $aliPay = app('alipay.web');
        $orderId = time();
        // $orderPrice = $total;
        $orderPrice = 0.01;
        if (count($data) > 1) {
            $goodsName = "合并" . count($data) . '笔订单,共' . array_sum(array_map(function ($item) {
                    return $item['shopping_number'];
                }, $data)) . '件商品';
        } else {
            $goodsName = $data[0]['cargo_title'];

        }
        $goodsDescription = $order_guid;
        $aliPay->setOutTradeNo($orderId);
        $aliPay->setTotalFee($orderPrice);
        $aliPay->setSubject($goodsName);
        $aliPay->setBody($goodsDescription);
        return $aliPay->getPayLink();
    }

    /**
     * 微信支付 生成订单二维码
     *
     * @param $data
     * @param $total
     * @param $order_guid
     * @return array|bool
     * @author zhangyuchao
     */
    public function weiXinPay($data, $total, $order_guid)
    {
        if (count($data) > 1) {
            $goodsName = "合并" . count($data) . '笔订单,共' . array_sum(array_map(function ($item) {
                    return $item['shopping_number'];
                }, $data)) . '件商品';
        } else {
            $goodsName = $data[0]['cargo_title'];

        }

        $payment = \Wechat::payment();
        $attributes = [
            'trade_type' => 'NATIVE',
            'body' => $goodsName,
            'detail' => '',
            'out_trade_no' => $order_guid,//订单号
            'total_fee' => 1,  //充值金额
            'attach' => 'xxx'
        ];

        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $data = [
                'code' => $result['code_url'],
                'QrCode' => json_encode(\QrCode::size(250)->generate($result['code_url'])),
                'out_trade_no' => $order_guid,
                'total_fee' => $total
            ];

            return $data;
        }
        // 判断支付宝异步回调异常
        $logMessage = Common::logMessageForInside(\Session::get('user')->user_id, config('log.systemLog')[16]);
        // 写入log日志
        $this->log->writeSystemLog($logMessage);

        return false;
    }

    /**
     * 同步回调 用于显示视图信息
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function aliPayCogradient(Request $request)
    {
        // 定义返回数组
        $data = [];
        // 判断是否支付成功
        if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED') {
            // 消费金额
            $data['total_fee'] = $request['total_fee'];
            // 地址信息
            $orderResult = $this->order->find(['guid' => $request['body']]);
            // 判断
            if (!empty($orderResult)) {
                $data['address'] = json_decode($orderResult->address_message, 1);
            }
        }
        // 返回视图
        return view('home.order.success', ['data' => $data]);
    }


    /**
     * 支付宝异步回调
     *
     * @param Request $request
     * @author zhangyuchao
     */
    public function aliPayNotify(Request $request)
    {
        // 判断是否支付成功
        if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED') {
            // 拼装参数
            $orderData = [
                'pay_status' => 2,
                'pay_transaction' => $request['trade_no']
            ];
            // 修改订单状态
            $this->notifyUpdate($request['body'], $orderData);
        } else {
            // 判断支付宝异步回调异常
            $logMessage = Common::logMessageForInside(\Session::get('user')->user_id, config('log.systemLog')[15]);
            // 写入log日志
            $this->log->writeSystemLog($logMessage);
        }

    }

    /**
     * 微信支付异步回调
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @author zhangyuchao
     */
    public function wechatNotify(Request $request)
    {
        $app = new Application(config('wechat'));
        $response = $app->payment->handleNotify(function ($notify, $successful) {
            // 判断是否成功
            if ($successful) {
                // 获取订单信息
                $orderMessage = json_decode($notify, 1);
                // 获取订单号
                $guid = $orderMessage['out_trade_no'];
                // 修改订单状态
                $result = $this->notifyUpdate($guid, ['pay_status' => 2]);
                // 返回
                return $result;

            } else {
                // 判断支付宝异步回调异常
                $logMessage = Common::logMessageForInside(\Session::get('user')->user_id, config('log.systemLog')[15]);
                // 写入log日志
                $this->log->writeSystemLog($logMessage);

                // 返回
                return false;
            }
        });

        // 返回
        return $response;
    }

    /**
     * 更改订单状态
     *
     * @param $guid
     * @param $orderData
     * @return bool
     * @author zhangyuchao
     */
    private function notifyUpdate($guid, $orderData)
    {
        try {
            // 开始事物
            \DB::beginTransaction();
            // 修改订单状态
            $orderResult = $this->order->update(['guid' => $guid], $orderData);
            // 失败 抛出异常
            if (empty($orderResult)) {
                throw new Exception(config('log.systemLog')[13]);
            }
            // 修改订单详情状态
            $orderDetailsResult = $this->orderDetails->update(['order_guid' => $guid], ['order_status' => 2]);
            // 失败 抛出异常
            if (empty($orderDetailsResult)) {
                throw new Exception(config('log.systemLog')[14]);
            }
            // 销量
            $detailsMessage = $this->orderDetails->select(['order_guid' => $guid]);
            foreach($detailsMessage as $detail) {
                $sales = $this->cargo->incrementForField(['id' => $detail->cargo_id],'sales_volume',$detail->commodity_number);
                if(empty($sales)) {
                    \Log::info('销量修改失败货品ID'.$detail->cargo_id.'销量数'.$detail->commodity_number);
                }
            }
            // 提交
            \DB::commit();

            return true;
        } catch (Exception $e) {
            // 回滚
            \DB::rollBack();
            // 组装数据
            $logMessage = Common::logMessageForInside(\Session::get('user')->user_id, $e->getMessage());
            // 写入log日志
            $this->log->writeSystemLog($logMessage);

            return false;
        }
    }

    /**
     * 轮训订单状态 查看是否支付成功
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function rotation(Request $request)
    {
        // 获取订单编号
        $guid = $request['guid'];
        // 查询订单数据
        $orderResult = $this->order->find(['guid' => $guid]);
        // 判断订单是否为空
        if (empty($orderResult)) {
            // 组装数据
            $logMessage = Common::logMessageForInside(\Session::get('user')->user_id, config('log.systemLog')[17]);
            // 写入log日志
            $this->log->writeSystemLog($logMessage);
            // 返回状态  数据丢失
            return responseMsg('', 410);
        }
        if ($orderResult->pay_status == 2) {
            // 正常 已支付
            return responseMsg($orderResult, 200);
        }
        // 还未支付
        return responseMsg('', 400);
    }

    /**
     * 再次支付
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function againPay(Request $request)
    {
        // 判断订单ID
        if (empty($request['order_id'])) {
            return responseMsg('非法操作', 400);
        }
        $orderResult = $this->order->find(['id' => $request['order_id']]);

        // 判断订单 是否存在
        if (empty($orderResult)) {
            return responseMsg('支付失败', 400);
        }
        // 获取订单内 商品信息
        $goods = json_decode($orderResult->goods_message, 1);
        // 判断商品库存 略过
        // 调用支付方法
        $result = $this->payType($orderResult->pay_type, $goods, $orderResult->total_amount, $orderResult->guid);
        //
        if (!empty($result)) {

            return responseMsg($result);
        }

        return responseMsg('支付失败', 400);

    }
}
