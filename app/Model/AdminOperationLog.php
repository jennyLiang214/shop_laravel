<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminOperationLog extends Model
{
    //
    /**
     * 管理员操作日志表
     * @var string
     */
    protected $table = 'log_admin_users_operation';

    /**
     * @var array
     */
    protected $fillable = ['operator_id', 'login_ip', 'events', 'content'];

}
