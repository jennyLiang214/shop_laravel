<?php
/**
 * Created by PhpStorm.
 * User: zhulinjie
 * Date: 2017/4/21
 * Time: 9:46
 */

namespace App\Repositories;

use App\Model\CategoryAttribute;

/**
 * Class CategoryAttributeRepository
 * @package App\Repositories
 */
class CategoryAttributeRepository
{
    use BaseRepository;
    
    /**
     * 分类标签值操作类
     *
     * @var CategoryAttribute
     * @author zhulinjie
     */
    protected $model;

    public function __construct(CategoryAttribute $categoryAttribute)
    {
        // 注入分类标签值操作类
        $this->model = $categoryAttribute;
    }

    /**
     * 通过一组$fields获取多条记录
     * 
     * @param $fields
     * @param $ids
     * @return mixed
     * @author zhulinjie
     */
    public function selectByWhereIn($fields, $ids)
    {
        return $this->model->whereIn($fields, $ids)->get();
    }
}