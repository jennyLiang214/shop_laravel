<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsCollection extends Model
{

    use SoftDeletes;
    /**
     * 收货地址表
     *
     * @var string
     */
    protected $table = 'data_cargo_collection';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'cargo_id'];

    /**
     * 根据货品关注获取货品。
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

}
