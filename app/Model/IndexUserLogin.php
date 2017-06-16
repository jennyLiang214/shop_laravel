<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndexUserLogin extends Model
{
    // 用户登录所以表
    protected $table = 'index_users_login';

    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'last_login_time'];

    /**
     * @var array  IndexUserLoginRepository
     */
    protected $fillable = ['user_id', 'login_name', 'password', 'last_login_ip', 'last_login_time'];

    /**
     * @var array
     */
    protected $hidden = ['password'];
}
