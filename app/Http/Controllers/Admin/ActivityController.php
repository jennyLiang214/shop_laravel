<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;
use App\Repositories\RelGoodsActivityRepository;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * @var ActivityRepository
     * @author zhulinjie
     */
    protected $activity;

    /**
     * @var
     * @author zhulinjie
     */
    protected $relGoodsActivity;

    /**
     * ActivityController constructor.
     * @param ActivityRepository $activityRepository
     * @param RelGoodsActivityRepository $relGoodsActivityRepository
     */
    public function __construct
    (
        ActivityRepository $activityRepository,
        RelGoodsActivityRepository $relGoodsActivityRepository
    )
    {
        $this->activity = $activityRepository;
        $this->relGoodsActivity = $relGoodsActivityRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.activity.index');
    }
    
    /**
     * 获取活动列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zhulinjie
     */
    public function activityList(Request $request)
    {
        $data = $request->all();

        // 获取活动列表
        $res = $this->activity->paging($data['where'], $data['perPage']);

        // 判断商品是否存在
        if(empty($res)){
            return responseMsg('暂无数据', 404);
        }

        return responseMsg($res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 获取最近的一次活动
        $currentTimestamp = time();
        // 要等上一场活动结束以后才可以添加下一场活动
        $activity = $this->activity->activities($currentTimestamp);
        return view('admin.activity.create', compact('activity'));
    }
    
    /**
     * 获取某一个活动信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function findActivity(Request $request)
    {
        $data = $request->all();
        $activity = $this->activity->find($data);
        if(!$activity){
            return responseMsg('暂无活动', 404);
        }
        return responseMsg($activity);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $req = $request->all();

        $data['name'] = $req['name'];
        $data['desc'] = $req['desc'];
        $data['type'] = $req['type'];
        $data['start_timestamp'] = strtotime($req['start_timestamp']);
        $data['length'] = $req['length'];
        $data['end_timestamp'] = $data['start_timestamp'] + $data['length'] * 60;

        $res = $this->activity->insert($data);

        if(!$res){
            return responseMsg('添加活动失败', 400);
        }

        return responseMsg('添加活动成功');
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
     * 修改活动
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $req = $request->all();
        $req['start_timestamp'] = strtotime($req['start_timestamp']);
        $req['end_timestamp'] = $req['start_timestamp'] + $req['length']*60;
        $res = $this->activity->update(['id' => $id], $req);
        if(!$res){
            return responseMsg('修改失败', 400);
        }
        // 修改成功获取修改成功后的数据
        $activity = $this->activity->find(['id' => $id]);
        return responseMsg($activity);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $relGoodsActivity = $this->relGoodsActivity->find(['activity_id' => $id]);
        if($relGoodsActivity){
            return responseMsg('有货品正在做活动，不能删除', 400);
        }
        $res = $this->activity->delete(['id' => $id]);
        if(!$res){
            return responseMsg('删除失败', 400);
        }

        return responseMsg('删除成功');
    }
}
