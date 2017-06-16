<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    /**
     * 收货地址表
     *
     * @var string
     */
    protected $table = 'data_receiving_address';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'consignee', 'tel', 'province', 'city','county','detailed_address','status'];

    /**
     * 设置user_id
     *
     * @author zhangyuchao
     */
    protected function setUserIdAttribute()
    {
        $this->attributes['user_id'] = \Session::get('user')->user_id;
    }
}
