<?php

namespace App\Repositories;

use App\Model\RelGoodsAttr;

/**
 * Class RelGoodsLabelRepository
 * @package App\Repositories
 */
class RelGoodsAttrRepository
{
    use BaseRepository;
    
    /**
     * @var RelGoodsAttr
     * @author zhulinjie
     */
    protected $model;

    /**
     * RelGoodsAttrRepository constructor.
     * @param RelGoodsAttr $relGoodsAttr
     */
    public function __construct(RelGoodsAttr $relGoodsAttr)
    {
        $this->model = $relGoodsAttr;
    }

    /**
     * 删除指定ID集合的数据
     *
     * @param $ids
     * @return mixed
     * @author zhulinjie
     */
    public function deleteWhereIn($ids)
    {
        return $this->model->whereIn('goods_attr_id', $ids)->delete();
    }
}