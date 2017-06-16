<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrdersRepository;
use App\Repositories\RegisterRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @var RegisterRepository
     */
    protected $user;
    /**
     * @var OrdersRepository
     */
    protected $order;

    /**
     * IndexController constructor.
     * @param RegisterRepository $registerRepository
     * @param OrdersRepository $ordersRepository
     */
    public function __construct
    (
        RegisterRepository $registerRepository,
        OrdersRepository $ordersRepository
    )
    {
        $this->order = $ordersRepository;
        $this->user = $registerRepository;
    }
    /**
     * 后台首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * 统计信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function count(Request $request)
    {
        // 计算用户数量
        $header['user'] = $this->user->count([]);
        // 订单统计
        $header['order'] = $this->order->count([]);
        // 付款的订单
        $header['success_order'] =  $this->order->count(['pay_status' =>2]);
        // 付款订单的总金额
        $price = $this->order->lists(['pay_status' =>2],['total_amount']);
        $header['price'] = empty($price)?0:array_sum($price->toArray());
         // 饼图统计 用户统计
        $main['user']['email'] = $this->user->count(['tel' => null]);
        $main['user']['tel'] = $this->user->count(['email' => null]);
        //  饼图统计 订单统计
        // 成功支付
        $main['order']['payment'] = $header['success_order'];
        // 未支付
        $main['order']['Unpaid'] = $this->order->count(['pay_status' =>1]);
        // 系统取消
        $main['order']['cancel'] = $this->order->count(['pay_status' =>3]);

        return responseMsg(['header' => $header, 'main' => $main]);
    }

    
}
