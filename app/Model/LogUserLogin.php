<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogUserLogin extends Model
{
    /**
     * 用户基本信息表
     * @var string
     */
    protected $table = 'log_users_login';

    /**
     * @var array
     */
    protected $dates = ['deleted_at','login_time'];

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'login_name', 'third_party', 'login_ip','login_time'];
}
