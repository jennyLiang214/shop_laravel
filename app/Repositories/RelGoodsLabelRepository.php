<?php

namespace App\Repositories;

use App\Model\RelGoodsLabel;

/**
 * Class RelGoodsLabelRepository
 * @package App\Repositories
 */
class RelGoodsLabelRepository
{
    use BaseRepository;
    
    /**
     * @var RelGoodsLabel
     * @author zhulinjie
     */
    protected $model;

    /**
     * RelGoodsLabelRepository constructor.
     * @param RelGoodsLabel $relGL
     */
    public function __construct(RelGoodsLabel $relGL)
    {
        $this->model = $relGL;
    }

    /**
     * 删除商品标签
     *
     * @param $where
     * @param $ids
     * @author zhulinjie
     */
    public function deleteWhereNotIn($where, $ids)
    {
        $this->model->where($where)->whereNotIn('goods_label_id', $ids)->delete();
    }
}