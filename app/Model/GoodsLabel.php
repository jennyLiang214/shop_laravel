<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsLabel extends Model
{
    /**
     * 商品标签表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'data_goods_labels';

    /**
     * 可以批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['category_id', 'goods_label_name'];

    /**
     * 一对多关联 / 一个标签拥有多个标签值
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author zhulinjie
     */
    public function attrs()
    {
        return $this->hasMany(GoodsAttribute::class);
    }
}
