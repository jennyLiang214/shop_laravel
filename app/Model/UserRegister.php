<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRegister
 * @package App\Model
 */
class UserRegister extends Model
{

    /**
     * 用户注册原始表 不可删除 不可修改
     * @var string
     */
    protected $table = 'data_users_register';

    /**
     * @var array
     */
    protected $fillable = ['email','tel', 'password', 'third_party_id', 'register_ip'];

    /**
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * 用户基本信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author zhangyuchao
     */
    public function message()
    {
        return $this->hasOne('App\Model\UserInfo','user_id');
    }

}
