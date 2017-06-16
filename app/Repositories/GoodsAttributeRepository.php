<?php
/**
 * Created by PhpStorm.
 * User: zhulinjie
 * Date: 2017/4/21
 * Time: 9:46
 */

namespace App\Repositories;

use App\Model\GoodsAttribute;

/**
 * Class GoodsAttributeRepository
 * @package App\Repositories
 */
class GoodsAttributeRepository
{
    use BaseRepository;
    
    /**
     * 商品标签值操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $model;

    /**
     * GoodsAttributeRepository constructor.
     * @param GoodsAttribute $goodsAttribute
     */
    public function __construct(GoodsAttribute $goodsAttribute)
    {
        // 注入商品标签值操作类
        $this->model = $goodsAttribute;
    }
}