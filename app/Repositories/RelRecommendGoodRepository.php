<?php

namespace App\Repositories;

use App\Model\RelRecommendGood;

/**
 * Class RelReommendGood
 * @package App\Repositories
 */
class RelRecommendGoodRepository
{
    use BaseRepository;

    /**
     * @var
     * @author zhulinjie
     */
    protected $model;

    /**
     * RelRecommendGoodRepository constructor.
     * @param RelRecommendGood $recommendGood
     */
    public function __construct(RelRecommendGood $recommendGood)
    {
        $this->model = $recommendGood;
    }

    /**
     * 删除指定货品并且recommend_id不等于指定范围的数据
     * 
     * @param $cargo_id
     * @param array $data
     * @return mixed
     * @author zhulinjie
     */
    public function whereNotInRecommendIds($cargo_id, array $data)
    {
        return $this->model->where('cargo_id', $cargo_id)->whereNotIn('recommend_id', $data)->delete();
    }
}