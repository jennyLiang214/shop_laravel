<?php

namespace App\Repositories;

use App\Model\AdminUser;

/**
 * Class AdminUserRepository
 * @package App\Repositories
 */
class AdminUserRepository
{
    use BaseRepository;
    /**
     * @var AdminUser
     */
    protected $model;

    /**
     * AdminUserRepository constructor.
     * @param AdminUser $adminUser
     * @author zhangyuchao
     */
    public function __construct(AdminUser $adminUser)
    {
        $this->model = $adminUser;
    }

    /**
     * 获取角色所拥有权限的 id
     *
     * @param $id
     * @return mixed
     * @author: Luoyan
     */
    public function fetchRolesTheIds($id)
    {
        return $this->model->where('id', $id)->first()->roles()->pluck('id');
    }
}