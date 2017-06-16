<?php

namespace App\Http\Controllers\Home;

use App\Repositories\CargoRepository;
use App\Repositories\CommentsRepository;
use App\Repositories\GoodsAttributeRepository;
use App\Repositories\GoodsLabelRepository;
use App\Repositories\OrderDetailsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    /**
     * @var OrderDetailsRepository
     */
    protected $orderDetails;
    /**
     * @var CommentsRepository
     */
    protected $comment;
    /**
     * @var string
     */
    protected $hashCargo;
    /**
     * @var CargoRepository
     */
    protected $cargo;
    /**
     * @var GoodsLabelRepository
     */
    protected $goodsLabel;
    /**
     * @var GoodsAttributeRepository
     */
    protected $goodsAttr;

    public function __construct
    (
        OrderDetailsRepository $orderDetailsRepository,
        CargoRepository $cargoRepository,
        GoodsLabelRepository $goodsLabelRepository,
        GoodsAttributeRepository $goodsAttributeRepository,
        CommentsRepository $commentsRepository
    )
    {
        $this->orderDetails = $orderDetailsRepository;
        $this->hashCargo = HASH_CARGO_INFO_;
        $this->cargo = $cargoRepository;
        $this->goodsAttr = $goodsAttributeRepository;
        $this->goodsLabel = $goodsLabelRepository;
        $this->comment = $commentsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 初始化返回数组
        $data = [];
        // 获取用户ID
        $userId = \Session::get('user')->user_id;
        // 根据用户ID查找用户所有评论
        $commentResult = $this->comment->commentsList(['user_id' => $userId], 1);
        // 判断是否为空
        if (!empty($commentResult)) {
            // 对象转数组
            $data = $commentResult->toArray();
            // 便利数据 获取商品标签
            foreach ($data['data'] as $key => $item) {
                $labels = json_decode($item['cargo_message']['cargo_ids'], 1);
                foreach ($labels as $k => $v) {
                    // 查询商品标签
                    $label = $this->goodsLabel->find(['id' => $k]);
                    // 查询商品标签值
                    $attr = $this->goodsAttr->find(['id' => $v]);
                    // 拼装货品信息
                    if (!empty($label) && !empty($attr)) {
                        $data['data'][$key]['label'][$v] = [
                            'label_name' => $label->goods_label_name,
                            'attr_name' => $attr->goods_label_name
                        ];

                    }
                }
            }
        }

        // 返回视图
        return view('home.comment.index', ['data' => $data, 'page' => $commentResult]);
    }

    /**
     * 评论显示页
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function create(Request $request)
    {
        $orderDetailsId = $request['orderDetailsId'];
        $detailResult = $this->orderDetails->find(['id' => $orderDetailsId]);
        // 初始化返回数组
        $data = [];
        if (!empty($detailResult)) {
            // 订单详情数据
            $data['order'] = $detailResult->toArray();
            $cargo = \Redis::hGetAll($this->hashCargo . $detailResult->cargo_id);
            if (empty($cargo)) {
                $cargo = $this->cargo->find(['id' => $detailResult->cargo_id]);
                if (!empty($cargo)) {
                    $cargo = $cargo->toArray();
                    \Redis::hSet($this->hashCargo . $detailResult->cargo_id);
                }
            }

            $labels = json_decode($cargo['cargo_ids'], 1);
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
            $data['cargo'] = $cargo;
        }

        return view('home.comment.create', ['data' => $data]);
    }

    /**
     * 添加评论
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     * @throws \Exception
     */
    public function store(Request $request)
    {
        // 组装数据
        $request['user_id'] = \Session::get('user')->user_id;
        // 组装条件
        $where = [
            'user_id' => $request['user_id'],
            'order_id' => $request['order_id'],
            'cargo_id' => $request['cargo_id']
        ];
        // 根据条件查询是否已对该商品进行评价
        if ($this->comment->find($where)) {
            return responseMsg('已经对该商品进行评价，不可重复评论', 400);
        }

        try {
            // 开始事物
            \DB::beginTransaction();
            // 插入评论数据
            $commentResult = $this->comment->insert($request->all());
            // 插入数据失败 抛出异常
            if (empty($commentResult)) {
                throw new \Exception('评论数据写入失败');
            }
            // 更改订单状态
            $orderResult = $this->orderDetails->update(['id' => $request['order_id']], ['order_status' => 5, 'comment_status' => 2]);
            // 判断订单状态是否更新成功
            if (empty($orderResult)) {
                throw new \Exception('订单状态更新失败');
            }
            $commentNum = $this->orderDetails->incrementForField(['id' => $request['cargo_id']], 'number_of_comments');
            if (empty($commentNum)) {
                throw new \Exception('评论数量修改失败');
            }
            // 提交
            \DB::commit();

            // 成功返回
            return responseMsg('成功');
        } catch (Exception $e) {
            // 事物回滚
            \DB::rollBack();
            // 失败返回
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
