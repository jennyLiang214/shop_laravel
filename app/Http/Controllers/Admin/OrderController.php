<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\OrderDetailsRepository;
use App\Repositories\OrdersRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    protected $order;
    /**
     * @var OrderDetailsRepository
     */
    protected $orderDetails;


    public function __construct
    (
        OrderDetailsRepository $orderDetailsRepository,
        OrdersRepository $ordersRepository
    )
    {
        $this->orderDetails = $orderDetailsRepository;
        $this->order = $ordersRepository;
    }
    public function index()
    {
        //
        return view('admin.order.index');
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 获取订单列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function orderList(Request $request)
    {
        // 初始化 搜索条件
        $where = [];
        // 查找搜索条件
        if(!empty($request['where']['type'])) {
            $where['order_status'] = $request['where']['type'];
        }
        // 获取订单列表
        $orderDetails = $this->orderDetails->getListPage($where,$request['perPage']);
        // 判断是否为空
        if(!empty($orderDetails)) {
            // 正确信息
            return responseMsg($orderDetails,200);
        }
        //  错误信息
        return responseMsg('数据获取失败',400);
    }

    /**
     * 获取收货地址
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function orderAddress(Request $request)
    {
        // 根据订单号码 获取订单主表信息
        $orderResult = $this->order->find(['guid' => $request['guid']]);
        // 判断订单是否存在
        if(!empty($orderResult)) {
            // 获取收货地址
            $address = json_decode($orderResult->address_message,1);
            // 成功
            return responseMsg($address);
        }
        // 失败
        return responseMsg('获取收货失败!',400);
    }

    /**
     * 发货
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function sendGoods(Request $request)
    {
        // 获取订单详情ID
        $id = $request['id'];
        // 修改订单状态
        $result = $this->orderDetails->update(['id' => $id],['order_status'=>3]);
        // 判断是否是该成功
        if(!empty($result)) {
            // 返回成功
            return responseMsg($result);
        }
        // 返回失败
        return responseMsg('发货操作失败',400);
    }
}
