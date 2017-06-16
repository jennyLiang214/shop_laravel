<?php

namespace App\Repositories;

use App\Model\Activity;

class ActivityRepository
{
    use BaseRepository;

    /**
     * @var Activity
     * @author zhulinjie
     */
    protected $model;

    /**
     * ActivityRepository constructor.
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->model = $activity;
    }

    /**
     * 获取最近的一次活动
     *
     * @param $ctime            当前时间戳
     * @author zhulinjie
     */
    public function activities($ctime)
    {
        return $this->model->where('end_timestamp', '>', $ctime)->orderBy('start_timestamp')->first();
    }
    
    /**
     * 获取正在进行的活动
     *
     * @param $ctime            当前时间戳
     * @return mixed
     * @author zhulinjie
     */
    public function ongoingActivities($ctime)
    {
        return $this->model->where('start_timestamp', '<=', $ctime)->where('end_timestamp', '>', $ctime)->first();
    }
    
    /**
     * 获取已经结束但未添加结束标记的活动
     * 
     * @param $ctime
     * @return mixed
     * @author zhulinjie
     */
    public function overActivity($ctime)
    {
        return $this->model->over($ctime)->get();
    }
}