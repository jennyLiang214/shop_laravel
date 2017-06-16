<?php

namespace App\Repositories;


use App\Model\Comment;

class CommentsRepository
{
    use BaseRepository;
    /**
     * @var Comment
     */
    protected $model;
    public $perPage;
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
        $this->perPage = 20;
    }

    /**
     * 获取评论列表
     *
     * @param array $where
     * @param int $perPage
     * @return mixed
     * @author zhangyuchao
     */
    public function commentsList(array $where,$perPage = 20)
    {

        return $this->model->where($where)->with('userMessage')->with('cargoMessage')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * 详情页获取评论数据
     *
     * @param array $where
     * @param $page
     * @param int $perPage
     * @return mixed
     * @author zhangyuchao
     */
    public function commentPaging(array $where,$page,$perPage = 20)
    {

        return $this->model->where($where)->orderBy('created_at', 'desc')->forPage($page,$perPage)->get();
    }

    /**
     * 获取分页数量
     *
     * @param array $where
     * @return float|int
     * @author zhangyuchao
     */
    public function getPage(array $where)
    {

        return $this->model->where($where)->count()/$this->perPage;
    }
}