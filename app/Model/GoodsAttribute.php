<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsAttribute extends Model
{
    /**
     * 商品标签值表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'data_goods_attributes';

    /**
     * 允许批量赋值的字段
     * 
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['goods_label_id', 'goods_label_name'];
}
