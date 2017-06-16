<?php

namespace App\Repositories;

use App\Model\RelGoodsActivity;

class RelGoodsActivityRepository
{
    use BaseRepository;
    
    /**
     * @var RelGoodsActivity
     * @author zhulinjie
     */
    protected $model;
    
    /**
     * ActivityRepository constructor.
     * @param RelGoodsActivity $relGoodsActivity
     */
    public function __construct(RelGoodsActivity $relGoodsActivity)
    {
        $this->model = $relGoodsActivity;
    }
}