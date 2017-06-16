<?php

namespace App\Repositories;


use App\Model\ShoppingCart;

class ShoppingCartRepositories
{
    use BaseRepository;
    
    /**
     * @var ShoppingCart
     */
    protected $model;

    /**
     * 注入
     *
     * ShoppingCartRepositories constructor.
     * @param ShoppingCart $shoppingCart
     */
    public function __construct(ShoppingCart $shoppingCart)
    {
        $this->model = $shoppingCart;
    }
}