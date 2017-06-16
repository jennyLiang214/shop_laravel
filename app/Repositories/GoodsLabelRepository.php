<?php

namespace App\Repositories;

use App\Model\GoodsLabel;

/**
 * Class GoodsLabelRepository
 * @package App\Repositories
 */
class GoodsLabelRepository
{
    use BaseRepository;
    
    /**
     * @var
     * @author zhulinjie
     */
    protected $model;

    /**
     * GoodsLabelRepository constructor.
     * @param GoodsLabel $goodsLabel
     */
    public function __construct(GoodsLabel $goodsLabel)
    {
        $this->model = $goodsLabel;
    }
}