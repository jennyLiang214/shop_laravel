<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    /**
     * 商品表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'data_goods';

    /**
     * 允许批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['category_id', 'goods_title', 'goods_label', 'goods_original', 'goods_info'];
    
    /**
     * 多对多关联关系 / 一个商品下面有多个标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author zhulinjie
     */
    public function labels()
    {
        return $this->belongsToMany(GoodsLabel::class, 'rel_goods_label', 'goods_id', 'goods_label_id')->withTimestamps();
    }

    /**
     * 多对多关联关系 / 一个商品下面有多个标签值
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author zhulinjie
     */
    public function attrs()
    {
        return $this->belongsToMany(GoodsAttribute::class, 'rel_goods_attrs', 'goods_id', 'goods_attr_id')->withTimestamps();
    }
}
