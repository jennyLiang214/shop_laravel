<?php

namespace App\Presenters;


class ShoppingCartPresenter
{
    /**
     * @var string
     */
    protected $listShoppingCart;
    /**
     * @var string
     */
    protected $hashShoppingCart;

    /**
     * ShoppingCartPresenter constructor.
     */
    public function __construct()
    {
        $this->hashShoppingCart = HASH_SHOPPING_CART_INFO_;
        $this->listShoppingCart = LIST_SHOPPING_CART_INFO_;
    }

    /**
     * 计算货品总数量
     *
     * @param $data
     * @return int
     * @author zhangyuchao
     */
    public function numberForCargo($data)
    {
        // 初始化货品数量
        $number = 0;
        // 计算货品数量
        foreach ($data as $item) {
            $number += $item['shopping_number'];
        }
        // 返回
        return $number;
    }

    /**
     * 计算总价格
     *
     * @param $data
     * @return int
     * @author zhangyuchao
     */
    public function totalPrice($data)
    {
        // 初始化总价格
        $price = 0;
        // 计算总价格
        foreach ($data as $item) {
            $item['price'] = empty($item['price']) ? $item['cargo_price'] : $item['price'];
            $price += $item['shopping_number'] * $item['price'];
        }
        // 返回
        return $price;
    }

    /**
     * 计算购物车内商品数量
     *
     * @return int
     * @author zhangyuchao
     */
    public function shoppingCount()
    {
        // 初始化购物车数量
        $count = 0;
        // 查看用户是否登录
        if (!empty(\Session::get('user'))) {
            // 获取用户ID
            $userId = \Session::get('user')->user_id;
            // 获取购物车列表
            $lists = \Redis::lRange($this->listShoppingCart . $userId, 0, -1);
            // 返回购物车内货品数量
            if (empty($lists)) {
                return $count;
            }
            // 便利购物车内数据
            foreach ($lists as $item) {
                // 计算商品数量
                $hash = \Redis::hGetAll($item);
                $count += $hash['shopping_number'];
            }
            // 返回数量
            return $count;

        }

        // 返回数量
        return $count;
    }
}