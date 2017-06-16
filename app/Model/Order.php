<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{


    /**
     * 订单主表
     *
     * @var string
     */
    protected $table = 'data_orders';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'guid', 'goods_message', 'address_message', 'pay_transaction','pay_type','pay_status','total_amount',];

}
