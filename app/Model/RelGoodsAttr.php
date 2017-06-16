<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RelGoodsAttr
 * @package App\Model
 */
class RelGoodsAttr extends Model
{
    /**
     * 商品标签值关联表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'rel_goods_attrs';

    /**
     * 允许批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['goods_id', 'goods_attr_id'];
}
