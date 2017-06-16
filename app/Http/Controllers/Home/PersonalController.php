<?php

namespace App\Http\Controllers\Home;

use App\Repositories\CargoRepository;
use App\Repositories\GoodsCollectionRepository;
use App\Repositories\OrderDetailsRepository;
use App\Repositories\RelLabelCargoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PersonalController extends Controller
{
    /**
     * @var GoodsCollectionRepository
     */
    protected $goodsCollection;

    protected $cargo;

    protected $relLabelCargo;

    protected $orderDetails;


    public function __construct
    (
        GoodsCollectionRepository $collectionRepository,
        CargoRepository $cargoRepository,
        RelLabelCargoRepository $relLabelCargoRepository,
        OrderDetailsRepository $orderDetailsRepository

    )
    {
        $this->goodsCollection = $collectionRepository;
        $this->cargo = $cargoRepository;
        $this->relLabelCargo = $relLabelCargoRepository;
        $this->orderDetails = $orderDetailsRepository;
    }

    /**
     * 个人中心首页
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function index()
    {
        // 拼装收藏列表条件
        $where['user_id'] = \Session::get('user')->user_id;
        // 查询收藏列表
        $result = $this->goodsCollection->paging($where);
        // 初始化返回数组
        $data = [];
        // 判断是否查询成功
        if(!empty($result)) {
            // 便利收藏列表
            foreach ($result as $key => $value) {
                // 根据货品ID查询货品
                $cargo = $this->cargo->find(['id' => $value->cargo_id]);

                if(!empty($cargo)) {
                    $data[$key]['cargo_id'] = $cargo->id;
                    $data[$key]['category_id'] = $cargo->category_id;   // 分类ID
                    $data[$key]['goods_id']    = $cargo->goods_id;      // 商品ID
                    $data[$key]['cargo_name']  = $cargo->cargo_name;    // 货品名称
                    $data[$key]['cargo_cover'] = $cargo->cargo_cover;   // 货品封面
                    $data[$key]['cargo_price'] = $cargo->cargo_price;   // 货品原价
                    $data[$key]['cargo_discount'] = $cargo->cargo_discount; //商品现价
                    $data[$key]['cargo_status'] = $cargo->cargo_status; // 商品上下架
                    // 收藏数
                    $data[$key]['cargo_collection'] = $this->goodsCollection->count(['cargo_id' =>$value->cargo_id ]);
                    // 相似
                    $data[$key]['category_attr_id'] = $this->relLabelCargo->find(['cargo_id' => $value->cargo_id]);
                }

            }
            // 获取订单状态数据
            $orderDetails = $this->orderDetails->select(['user_id' => \Session::get('user')->user_id]);
            // 定义新数组
            $orderStatus = [
                'payment' => [],
                'delivery' => 0,
                'recipient' => 0,
                'comment' =>0
            ];
            // 判断赋值
            if(!empty($orderDetails)){
                foreach ($orderDetails as $key => $val) {

                    switch ($val->order_status) {
                        case 1:
                            $orderStatus['payment'][$val->order_guid] = 1;
                            break;
                        case 2:
                            $orderStatus['delivery'] += 1;
                            break;
                        case 3:
                            $orderStatus['recipient'] += 1;
                            break;
                        case 4:
                            $orderStatus['comment'] += 1;
                            break;
                    }
                }

            }


        }
        return view('home.personal.index',['data' => $data,'page' => $result,'orderStatus'=>$orderStatus]);

    }
    
    public function information(){
        return view('home.personal.information');
    }
}
