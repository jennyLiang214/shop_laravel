<?php

namespace App\Repositories;


use App\Model\Role;

/**
 * Class RoleRepository
 * @package App\Repositories
 */
class RoleRepository
{
    use BaseRepository;

    /**
     * @var Role
     * @author Luoyan
     */
    protected $model;

    /**
     * 模型注入
     *
     * RoleRepository constructor.
     * @param $role
     * @author Luoyan
     */
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    /**
     * 获取角色所拥有权限的 id
     *
     * @param $id
     * @return mixed
     * @author: Luoyan
     */
    public function fetchPermissionsTheIds($id)
    {
        return $this->model->where('id', $id)->first()->permissions()->pluck('id');
    }
}