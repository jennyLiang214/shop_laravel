<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelCategoryLabelAttribute extends Model
{
    /**
     * 允许批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['cid', 'lid', 'aid'];
}
