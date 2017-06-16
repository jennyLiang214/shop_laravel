<?php

namespace App\Console\Commands;

use App\Repositories\CargoRepository;
use App\Repositories\OrderDetailsRepository;
use App\Repositories\OrdersRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Order extends Command
{
    /**
     * @var OrdersRepository
     */
    protected $order;
    /**
     * @var OrderDetailsRepository
     */
    protected $orderDetails;
    /**
     * @var CargoRepository
     */
    protected $cargo;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public function __construct
    (
        OrdersRepository $ordersRepository,
        OrderDetailsRepository $orderDetailsRepository,
        CargoRepository $cargoRepository
    )
    {
        parent::__construct();
        $this->order = $ordersRepository;
        $this->orderDetails = $orderDetailsRepository;
        $this->cargo = $cargoRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->queryOrder();
    }

    /**
     * 查询24小时内未支付的订单
     *
     * @author zhangyuchaos
     */
    public function queryOrder()
    {

        // 查询所有待付款的订单
        $orderResult = $this->order->select(['pay_status' => 1]);
        if(!empty($orderResult)) {
            // 便利订单
            foreach ($orderResult as $item) {
                // 查找超过24小时没有支付的订单
                if(Carbon::parse($item->created_at)->timestamp+86400 > time()) {
                    // 自动 设置为取消
                    $this->order->update(['id' => $item->id],['pay_status' => 3]);
                    // 自动 设置为取消
                    $this->orderDetails->update(['order_guid' => $item->guid],['order_status'=>6]);
                    // 设置自动取消之后 把库存还原
                    $cargoes = json_decode($item->goods_message,1);
                    foreach ($cargoes as $cargo) {
                        $cargoResult = $this->cargo->incrementForField(['id' =>$cargo['id']],'inventory',$item['shopping_number']);
                            if(empty($cargoResult)) {
                                \Log::info('库存修改失败',$item);
                        }
                    }
                }

            }
        }
    }
}
