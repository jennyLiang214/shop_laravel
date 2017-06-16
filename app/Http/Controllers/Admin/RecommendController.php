<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RecommendRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class RecommendController
 * @package App\Http\Controllers\Admin
 */
class RecommendController extends Controller
{
    /**
     * @var RecommendRepository
     * @author Luoyan
     */
    protected $recommend;

    /**
     * RecommendController constructor.
     * @param $recommend
     * @author Luoyan
     */
    public function __construct(RecommendRepository $recommend)
    {
        $this->recommend = $recommend;
    }

    /**
     * 推荐列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Luoyan
     */
    public function index()
    {
        return view('admin.recommend.index');
    }

    /**
     * 创建推荐位页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Luoyan
     */
    public function create()
    {
        return view('admin.recommend.insert');
    }

    /**
     * 创建推荐位
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     * @author: Luoyan
     */
    public function store(Request $request)
    {
        // 创建一个楼位并且判断是否成功
        if ($this->recommend->insert($request->all())) {

            // 成功跳转到推荐位列表
            return redirect()->route('recommend.index');
        }

        // 失败返回上一个页面并且附带表单值
        return back()->withErrors('创建失败!')->withInput();
    }

    /**
     * 推荐位列表
     *
     * @param Request $request
     * @return mixed
     * @author: Luoyan
     */
    public function recommendList(Request $request)
    {
        return $this->recommend->paging($request->get('where'), $request->get('perPage'));
    }

    /**
     * 修改分类信息
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author: Luoyan
     */
    public function update(Request $request, $id)
    {
        // 除去请求中得 _token 字段
        $data = $request->except(['_token']);
        // 修改分类数据, 判断返回结果
        if ($this->recommend->update(['id' => $id], $data)) {
            // 查询更新后的值
            $data = $this->recommend->find(['id' => $id]);

            // 成功返回修改数据
            return responseMsg($data);
        }

        // 修改失败
        return responseMsg('修改失败!', 400);
    }
}
