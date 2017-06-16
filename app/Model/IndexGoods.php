<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndexGoods extends Model
{
    /**
     * @var string
     */
    protected $table = 'index_goods';

    /**
     * @var array
     */
    protected $fillable = ['goods_id', 'cargo_id', 'body'];
}
