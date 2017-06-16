<?php

namespace App\Repositories;

use App\Model\UserRegister;

/**
 * Class RegisterRepository
 * @package App\Repositories
 */
class RegisterRepository
{
    use BaseRepository;
    /**
     * @var UserRegister
     */
    protected $model;

    /**
     * 注入用户注册model
     *
     * RegisterRepository constructor.
     * @param UserRegister $userRegister
     * @author zhangyuchao
     */
    public function __construct(UserRegister $userRegister)
    {
        $this->model = $userRegister;
    }

    /**
     * 分页获取用户列表数据
     *
     * @param $where
     * @param $perPage
     * @author zhangyuchao
     */
    public function userList($where,$perPage)
    {
       return $this->model->where($where)->with('message')->orderBy('created_at','desc')->paginate($perPage);
    }



}