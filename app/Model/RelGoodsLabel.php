<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelGoodsLabel extends Model
{
    /**
     * 商品标签关联表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'rel_goods_label';

    /**
     * 可以批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['goods_id', 'goods_label_id'];

    
}
