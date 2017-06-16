<?php

namespace App\Repositories;


use App\Model\GoodsCollection;

class GoodsCollectionRepository
{
    use BaseRepository;
    /**
     * @var GoodsCollection
     */
    protected $model;

    /**
     * GoodsCollectionRepository constructor.
     * @param GoodsCollection $goodsCollection
     */
    public function __construct(GoodsCollection $goodsCollection)
    {
        $this->model = $goodsCollection;
    }


}