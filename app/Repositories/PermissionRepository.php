<?php

namespace App\Repositories;

use App\Model\Permission;

/**
 * Class RoleRepository
 * @package App\Repositories
 */
class PermissionRepository
{
    use BaseRepository;
    
    /**
     * @var Permission
     * @author Luoyan
     */
    protected $model;

    /**
     * RoleRepository constructor.
     * @param Permission $permission
     * @author Luoyan
     */
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }
}