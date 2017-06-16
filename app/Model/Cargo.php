<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    /**
     * 货品表
     *
     * @var string
     * @author zhulinjie
     */
    protected $table = 'data_cargo';
    
    /**
     * 允许批量赋值的字段
     * 
     * @var array
     * @author zhulinjie
     */
    protected $fillable = [
        'category_id', 'goods_id', 'cargo_ids', 'cargo_cover', 'inventory', 'cargo_name', 'cargo_price', 'cargo_discount', 'cargo_original', 'cargo_info'
    ];

    /**
     * 多对一关联 / 一个货品属于某一个商品 
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author zhulinjie
     */
    public function good()
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }
    
    /**
     * 多对多关联关系 / 一个货品可以在多个活动中
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author zhulinjie
     */
    public function activitys()
    {
        return $this->belongsToMany(Activity::class, 'rel_goods_activitys', 'cargo_id', 'activity_id')->withTimestamps();
    }
    
    /**
     * 一对多关联 一个货品对应多个关注
     */
    public function goodscollection()
    {
        return $this->hasMany(GoodsCollection::class, 'cargo_id');
    }
}
