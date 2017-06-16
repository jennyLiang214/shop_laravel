<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelGoodsActivity extends Model
{
    /**
     * 商品活动关联表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'rel_goods_activitys';

    /**
     * 允许批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['activity_id', 'cargo_id', 'number', 'promotion_price'];

    /**
     * 获取某一个货品
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author zhulinjie
     */
    public function cargo(){
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    /**
     * 获取某一个活动
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author zhulinjie
     */
    public function activity(){
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}
