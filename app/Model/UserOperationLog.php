<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserOperationLog extends Model
{
    /**
     * 用户日志操作表
     * @var string
     */
    protected $table = 'log_users_operation';

    /**
     * @var array
     */
    protected $fillable = ['operator_id', 'login_ip', 'content','events'];

}
