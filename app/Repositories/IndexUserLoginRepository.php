<?php

namespace App\Repositories;

use App\Model\IndexUserLogin;

/**
 * Class IndexUserLoginRepository
 * @package App\Repositories
 */
class IndexUserLoginRepository
{
    use BaseRepository;
    /**
     * @var IndexUserLogin
     */
    protected $model;

    /**
     * IndexUserLoginRepository constructor.
     * @param IndexUserLogin $indexUserLogin
     * @author zhangyuchao
     */
    public function __construct(IndexUserLogin $indexUserLogin)
    {
        // 用户登录索引表
        $this->model = $indexUserLogin;
    }

}