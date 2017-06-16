<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{

    /**
     * 订单详情表
     *
     * @var string
     */
    protected $table = 'data_orders_details';
    /**
     * 不需要自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['order_guid', 'user_id', 'goods_id', 'cargo_id', 'order_status','commodity_number','cargo_price','return_status','comment_status','addtime'];

    /**
     * 与用户进行关联
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author zhangyuchao
     */
    public function userMessage()
    {
        return $this->hasOne('App\Model\UserInfo','user_id','user_id');
    }

    /**
     * 与货品进行关联
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author zhangyuchao
     */
    public function cargoMessage()
    {
        return $this->hasOne('App\Model\Cargo','id','cargo_id');
    }
}
