<?php

namespace App\Repositories;

use App\Model\Recommend;

/**
 * Class CategoryRepository
 * @package App\Repositories
 */

/**
 * Class RecommendRepository
 * @package App\Repositories
 */
class RecommendRepository
{
    use BaseRepository;
    
    /**
     * @var Recommend
     * @author Luoyan
     */
    protected $model;

    /**
     * 服务注入
     *
     * RecommendRepository constructor.
     * @param $recommend
     * @author Luoyan
     */
    public function __construct(Recommend $recommend)
    {
        $this->model = $recommend;
    }
    
    /**
     * 获取所有推荐位 / 包括推荐位下面得商品
     *
     * @return mixed
     * @author: Luoyan
     */
    public function recommendWithGoods()
    {
        return $this->model->with('cargos')->get();
    }
}