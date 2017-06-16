<?php

namespace App\Repositories;

use App\Model\Goods;

/**
 * Class GoodsRepository
 * @package App\Repositories
 */
class GoodsRepository
{
    use BaseRepository;
    
    /**
     * @var
     */
    protected $model;
    
    /**
     * GoodsRepository constructor.
     * @param Goods $goods
     */
    public function __construct(Goods $goods)
    {
        $this->model = $goods;
    }
}