<?php

namespace App\Presenters;
use App\Repositories\FriendLinkRepository;

class HomeLinksPresenter
{
    /**
     * @var LinkRepository
     */
    protected $links;

    /**
     * 服务注入
     */
    public function __construct(FriendLinkRepository $linksRepository)
    {
        //注入友情链接操作类
        $this->links = $linksRepository;
    }

    /**
     * 数据操作方法
     * @param $attr
     * @return mixed
     * @author wutao
     */
    public function getLinks()
    {
        //数据操作,返回数据
        return $this->links->select();
    }
}