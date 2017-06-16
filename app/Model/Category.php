<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 * @package App\Model
 */
class Category extends Model
{
    use SoftDeletes;

    /**
     * 分类表
     *
     * @var string
     */
    protected $table = 'data_category';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['pid', 'name', 'level', 'describe', 'img'];

    /**
     * ORM 对应关系 / 获取父类信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author: Luoyan
     */
    public function parentCategory()
    {
        return $this->belongsTo(static::class, 'pid', 'id');
    }

    /**
     * ORM 对应关系 / 获取子类信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author zhulinjie
     */
    public function childrenCategory()
    {
        return $this->hasMany(static::class, 'pid', 'id');
    }

    /**
     * 多对多关联关系 / 一个分类下面有多个标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author: Luoyan
     */
    public function labels()
    {
        return $this->belongsToMany(CategoryLabel::class, 'rel_category_labels', 'category_id', 'category_label_id');
    }

    /**
     * 多对多关联关系 / 一个分类下面有多个标签值
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author: Luoyan
     */
    public function attrs()
    {
        
        return $this->belongsToMany(CategoryAttribute::class, 'rel_category_attributes', 'category_id', 'category_attribute_id');
    }
}
