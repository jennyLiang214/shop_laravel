<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{

    /**
     * 购物车表
     *
     * @var string
     */
    protected $table = 'data_shopping_cart';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'cargo_id', 'shopping_number', 'price'];

}
