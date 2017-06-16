<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * 商品活动表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = "data_activitys";

    /**
     * 允许批量赋值的字段
     *
     * @var array
     * @author zhulinjie
     */
    protected $fillable = ['name', 'desc', 'type', 'start_timestamp', 'length', 'end_timestamp'];

    /**
     * 一对多关联
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author zhulinjie
     */
    public function relGoodsActivitys()
    {
        return $this->hasMany(RelGoodsActivity::class);
    }

    /**
     * 多对多关联关系 / 一场活动下面有多个货品
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author zhulinjie
     */
    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'rel_goods_activitys', 'activity_id', 'cargo_id')->withTimestamps();
    }

    /**
     * 限制查询只包括已经结束但未添加结束标记的活动
     *
     * @param $query
     * @param $ctime
     * @return mixed
     * @author zhulinjie
     */
    public function scopeOver($query, $ctime)
    {
        return $query->where('is_over', 0)->where('end_timestamp', '<', $ctime);
    }
}
