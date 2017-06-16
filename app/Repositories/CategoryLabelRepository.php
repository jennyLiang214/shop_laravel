<?php

namespace App\Repositories;

use App\Model\CategoryLabel;

/**
 * Class CategoryLabelRepository
 * @package App\Repositories
 */
class CategoryLabelRepository
{
    use BaseRepository;

    /**
     * 分类标签模型
     *
     * @var CategoryLabel
     * @author Luoyan
     */
    protected $model;

    /**
     * 模型注入
     *
     * CategoryLabelRepository constructor.
     * @param $categoryLabel
     * @author Luoyan
     */
    public function __construct(CategoryLabel $categoryLabel)
    {
        $this->model = $categoryLabel;
    }
}