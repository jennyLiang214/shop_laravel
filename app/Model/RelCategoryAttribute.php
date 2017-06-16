<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelCategoryAttribute extends Model
{
    /**
     * 允许批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['category_id', 'category_attribute_id'];
}
