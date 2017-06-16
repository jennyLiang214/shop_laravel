<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelLabelCargo extends Model
{
    /**
     * 分类标签值与货品关联表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'rel_label_cargos';

    /**
     * 允许批量赋值的字段
     * 
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['category_attr_ids', 'goods_id', 'cargo_id'];
}
