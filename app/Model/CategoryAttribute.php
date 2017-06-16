<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    /**
     * 分类标签值表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'data_category_attributes';

    /**
     * 允许批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['category_label_id', 'attribute_name'];

    
}
