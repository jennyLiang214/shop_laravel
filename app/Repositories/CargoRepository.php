<?php
/**
 * Created by PhpStorm.
 * User: zhulinjie
 * Date: 2017/4/21
 * Time: 16:05
 */

namespace App\Repositories;

use App\Model\Cargo;
use App\Model\IndexGoods;
use App\Tools\Analysis;

/**
 * Class CargoRepository
 * @package App\Repositories
 */
class CargoRepository
{

    use BaseRepository;

    /**
     * @var Cargo
     * @author zhulinjie
     */
    protected $model;

    /**
     * CargoRepository constructor.
     * @param Cargo $cargo
     */
    public function __construct(Cargo $cargo)
    {
        $this->model = $cargo;
    }

    /**
     * 通过whereIn获取多条数据
     *
     * @param $fields
     * @param array $ids
     * @param array $sort
     * @return mixed
     * @author zhulinjie
     */
    public function selectWhereIn($fields, array $ids, array $sort = [])
    {
        if(empty($sort)){
            return $this->model->whereIn($fields, $ids)->get();
        }else{
            return $this->model->whereIn($fields, $ids)->orderBy($sort[0], $sort[1])->get();
        }
    }

    /**
     * 字段数量自增
     *
     * @param array $where
     * @param $field
     * @param $num
     * @return mixed
     * @author zhangyuchao
     */
    public function incrementForField(array $where,$field,$num = 1)
    {
        return $this->model->where($where)->increment($field,$num);
    }

    /**
     * 字段数量自减
     *
     * @param array $where
     * @param $field
     * @param $num
     * @return mixed
     * @author zhangyuchao
     */
    public function decrementForField(array $where,$field,$num = 1)
    {
        return $this->model->where($where)->decrement($field,$num);
    }

}