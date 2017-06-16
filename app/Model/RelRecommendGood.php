<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelRecommendGood extends Model
{
    /**
     * 推荐位与货品关联表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'rel_recommend_goods';
    
    /**
     * 允许批量赋值的字段
     * 
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['recommend_id', 'cargo_id'];
}
