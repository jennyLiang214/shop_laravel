<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * 商品评论表
     *
     * @var string
     */
    protected $table = 'data_goods_comment';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['goods_id', 'user_id', 'cargo_id', 'star','order_id', 'comment_type','comment_info'];

    /**
     * 关联商品模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author zhangyuchao
     */
    public function cargoMessage()
    {
        return $this->hasOne('App\Model\Cargo','id','cargo_id');
    }

    /**
     * 关联用户模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author zhangyuchao
     */
    public function userMessage()
    {
        return $this->hasOne('App\Model\UserInfo','user_id','user_id');
    }
}
