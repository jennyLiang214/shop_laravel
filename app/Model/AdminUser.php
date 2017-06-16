<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class AdminUser extends Authenticatable
{
    /**
     *  权限控制
     */
    use LaratrustUserTrait;
    /**
     *  软删除
     */
    use SoftDeletes;
    /**
     * 管理员表
     * @var string
     */
    protected $table = 'data_admin_users';

    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'last_login_at'];

    /**
     * @var array
     */
    protected $fillable = ['nickname', 'tel', 'password', 'avatar', 'last_login_ip', 'last_login_at'];

    /**
     * @var array
     */
    protected $hidden = ['password'];

}
