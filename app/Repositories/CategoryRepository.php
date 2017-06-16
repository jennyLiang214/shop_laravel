<?php

namespace App\Repositories;

use App\Model\Category;

/**
 * Class CategoryRepository
 * @package App\Repositories
 */
class CategoryRepository
{
    use BaseRepository;
    
    /**
     * @var Category
     */
    protected $model;

    /**
     * 服务注入
     *
     * CategoryRepository constructor.
     * @param $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    /**
     * 根据 id 查找数据 / 附带父类信息
     *
     * @param $id
     * @return mixed
     * @author: Luoyan
     */
    public function findByIdWithParent($id)
    {
        return $this->model->with('parentCategory')->find($id);
    }

    /**
     * 恢复软删除的数据
     *
     * @param $id
     * @return mixed
     * @author: Luoyan
     */
    public function softRstore($id)
    {
        return $this->model->withTrashed()->where('id', $id)->restore();
    }
}