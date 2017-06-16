<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;
use App\Repositories\RelGoodsActivityRepository;
use Illuminate\Http\Request;

class CargoActivityController extends Controller
{
    /**
     * 活动操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $activity;

    /**
     * 商品活动关联操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $relGoodsActivity;

    /**
     * CargoActivityController constructor.
     * @param ActivityRepository $activityRepository
     * @param RelGoodsActivityRepository $relGoodsActivityRepository
     */
    public function __construct
    (
        ActivityRepository $activityRepository,
        RelGoodsActivityRepository $relGoodsActivityRepository
    )
    {
        // 注入活动操作类
        $this->activity = $activityRepository;
        // 注入商品活动关联操作类
        $this->relGoodsActivity = $relGoodsActivityRepository;
    }

    /**
     * 货品-活动列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.cargoActivity.index');
    }
    
    /**
     * 获取所有活动 暂时只做秒杀
     *
     * @author zhulinjie
     */
    public function getActivity()
    {
        $activitys = $this->activity->select(['type' => 1]);

        if(empty($activitys)){
            return responseMsg('暂无活动', 404);
        }
        
        return responseMsg($activitys);
    }

    /**
     * 获取做活动的货品列表
     *
     * @author zhulinjie
     */
    public function cargoActivityList(Request $request)
    {
        $data = $request->all();
        
        $cargoActivitys = $this->relGoodsActivity->paging($data['where'], $data['perPage']);

        foreach ($cargoActivitys as $cargoActivity){
            // 获取货品详情
            $cargoActivity->cargo = $cargoActivity->cargo;
            // 获取活动详情
            $cargoActivity->activity = $cargoActivity->activity;
        }

        if(empty($cargoActivitys)){
            return responseMsg('暂无数据', 404);
        }

        return responseMsg($cargoActivitys);
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
     * 对货品做活动
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function store(Request $request)
    {
        $req = $request->all();

        // 添加之前判断活动是否存在
        $param['activity_id'] = $req['activity_id'];
        $param['cargo_id'] = $req['cargo_id'];
        $res = $this->relGoodsActivity->find($param);
        if($res){
            return responseMsg('该活动已存在', 400);
        }

        // 添加操作
        $res = $this->relGoodsActivity->insert($req);

        if(!$res){
            return responseMsg('操作失败', 400);
        }

        return responseMsg('操作成功');
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
