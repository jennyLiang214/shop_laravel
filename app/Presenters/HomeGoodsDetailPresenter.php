<?php

namespace App\Presenters;

class HomeGoodsDetailPresenter
{
    /**
     * 选中商品标签
     *
     * @param $label_id  标签ID
     * @param $attr_id  标签值ID
     * @param $cargo  当前货品信息
     * @param $cids  当前货品拥有的商品规格组合
     * @return bool|string
     * @author zhulinjie
     */
    public function selectedAttr($label_id, $attr_id, $cargo, $cids)
    {
        $cargo_ids = json_decode($cargo->cargo_ids, 1);
        if(array_key_exists($label_id, $cargo_ids) && $cargo_ids[$label_id] == $attr_id){
            return 'selected';
        }else if(in_array($label_id.':'.$attr_id, $cids)){
            return 'normal';
        }
        return false;
    }

    /**
     * 过滤标签值
     *
     * @param $allAttrs
     * @param $attr
     * @return mixed
     * @author zhulinjie
     */
    public function filterAttr($allAttrs, $attr)
    {
        $ids = array_intersect($allAttrs->pluck('id')->toArray(), $attr->pluck('id')->toArray());
        return $attr->whereIn('id', $ids);
    }
}