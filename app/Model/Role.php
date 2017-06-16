<?php

namespace App\Model;

use Laratrust\LaratrustRole;

/**
 * Class Role
 * @package App\Model
 */
class Role extends LaratrustRole
{
    /**
     * 可填充字段
     *
     * @var array
     * @author Luoyan
     */
    protected $fillable = ['name', 'display_name', 'description'];

}
