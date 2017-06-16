<?php

namespace App\Model;

use Laratrust\LaratrustPermission;

/**
 * Class Permission
 * @package App\Model
 */
class Permission extends LaratrustPermission
{
    /**
     * 填充字段
     *
     * @var array
     * @author Luoyan
     */
    protected $fillable = ['name', 'display_name', 'description'];
}
