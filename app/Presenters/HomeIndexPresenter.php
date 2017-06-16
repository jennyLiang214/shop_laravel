<?php

namespace App\Presenters;

class HomeIndexPresenter
{
    /**
     * 获取距离活动开始的秒数或者距离活动结束的秒数
     *
     * @param $startTime
     * @param $length
     * @return int
     * @author zhulinjie
     */
    public function diffSeconds($startTime, $length)
    {
        $diffTime = time() - $startTime;

        // $diffTime 大于或等于0的情况 说明活动已经开始 返回的就是距离活动结束的秒数
        if($diffTime == 0){
            return $length * 60;
        }

        if($diffTime > 0){
            return $length * 60 - $diffTime;
        }

        // 说明活动还未开始 返回的就是距离活动开始的秒数
        return $diffTime;
    }
}