<?php

namespace App\Http\Controllers\Home;

use App\Repositories\CargoRepository;
use App\Repositories\GoodsCollectionRepository;
use App\Repositories\RelLabelCargoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsCollectionController extends Controller
{
    /**
     * @var GoodsCollectionRepository
     */
    protected $goodsCollection;

    protected $cargo;

    protected $relLabelCargo;


    public function __construct
    (
        GoodsCollectionRepository $collectionRepository,
        CargoRepository $cargoRepository,
        RelLabelCargoRepository $relLabelCargoRepository

    )
    {
        $this->goodsCollection = $collectionRepository;
        $this->cargo = $cargoRepository;
        $this->relLabelCargo = $relLabelCargoRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 拼装收藏列表条件
        $where['user_id'] = \Session::get('user')->user_id;
        //$res = $this->goodsCollection->find($where);
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
                    $category_attr_id = $this->relLabelCargo->find(['cargo_id' => $value->cargo_id]);
                    $str='';
                    if(!empty($category_attr_id)){
                        $tmp = json_decode($category_attr_id->category_attr_ids,1);
                        foreach ($tmp as $k => $v)
                        {
                            $str .= $k.'_'.$v.',';
                        }
                    }
                    $data[$key]['category_attr_id'] = rtrim($str,'%');
                }
            }

        }

        return view('home.goodsCollection.index',['data' => $data,'page' => $result]);

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
        // 判断是否登录
        if(empty(\Session::get('user')->user_id)) {
            return responseMsg('',401);
        }
        // 拼装参数
        $param['user_id'] = \Session::get('user')->user_id;
        $param['cargo_id'] = $request['cargo_id'];

        // 用户是否已经收藏该货品
        $exist = $this->goodsCollection->find($param);

        if(empty($exist)) {
            // 没有收藏添加，添加收藏记录

            $result = $this->goodsCollection->insert($param);
            if(!empty($result)) {
                // 操作成功  返回当前收藏总数
                $count = $this->goodsCollection->count(['cargo_id' => $param['cargo_id']]);
                return responseMsg($count, 200);
            }

        } else {
            // 已经收藏 移除收藏
            $result = $this->goodsCollection->delete(['id'=>$exist->id]);
            // 判断操作是否成功
            if(!empty($result)) {
                // 操作成功  返回当前收藏总数
                $count = $this->goodsCollection->count(['cargo_id'=>$param['cargo_id']]);
                return responseMsg($count,300);
            }

        }
        // 判断操作是否成功

        return responseMsg('操作失败',400);

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
}
