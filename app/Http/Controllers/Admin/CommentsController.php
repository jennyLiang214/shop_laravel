<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CommentsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    /**
     * @var CommentsRepository
     */
    protected $comment;

    /**
     * CommentsController constructor.
     * @param CommentsRepository $commentsRepository
     */
    public function __construct(CommentsRepository $commentsRepository)
    {
        $this->comment = $commentsRepository;
    }

    /**
     * 返回页面视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function index()
    {
        return view('admin.comments.index');
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
        //
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
     * 删除评论操作
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function destroy($id)
    {

         if($this->comment->delete(['id' => $id])) {

            return responseMsg('成功');
         }

         return responseMsg('删除失败',400);
    }

    /**
     * 评论列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function commentList(Request $request)
    {
        // 初始化查询数组
        $where = [];
        // 判断搜索条件
       if(!empty($request['where']['type'])) {
           $where['star'] = $request['where']['type'];
       }
        // 获取用户列表数据
        $result = $this->comment->commentsList($where, $request['perPage']);
        // 判断是否执行成功
        if (!empty($result)) {
            // 成功
            return responseMsg($result, 200);

        }
        // 失败
        return responseMsg('', 400);
    }

}
