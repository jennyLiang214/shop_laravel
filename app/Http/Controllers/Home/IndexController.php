<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\RecommendRepository;
use App\Tools\Common;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @var RecommendRepository
     * @author zhulinjie
     */
    protected $recommend;

    /**
     * @var CategoryRepository
     * @author zhulinjie
     */
    protected $category;

    /**
     * @var ActivityRepository
     * @author zhulinjie
     */
    protected $activity;

    /**
     * IndexController constructor.
     * @param RecommendRepository $recommend
     * @param CategoryRepository $categoryRepository
     */
    public function __construct
    (
        RecommendRepository $recommend,
        CategoryRepository $categoryRepository,
        ActivityRepository $activityRepository
    )
    {
        $this->recommend = $recommend;
        $this->category = $categoryRepository;
        $this->activity = $activityRepository;
    }

    /**
     * 商城首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Luoyan
     */
    public function index()
    {
        // 获取分类信息
        $categorys = $this->category->select(['level'=>1]);
        foreach ($categorys as $category){
            $category->children = $this->category->select(['pid'=>$category->id]);
            foreach ($category->children as $children){
                $children->grandchild = $this->category->select(['pid'=>$children->id]);
            }
        }

        // 获取最近的一次活动
        $currentTimestamp = time();
        $activity = $this->activity->activities($currentTimestamp);

        // 获取楼层和楼层下面得商品
        $recommends = $this->recommend->recommendWithGoods();

        $data['categorys'] = $categorys;
        $data['activity'] = $activity;
        $data['recommends'] = $recommends;

        return view('home.index', compact('data'));
    }
}
