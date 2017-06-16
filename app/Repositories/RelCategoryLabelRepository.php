<?php

namespace App\Repositories;

use App\Model\RelCategoryLabel;

/**
 * Class CategoryLabelRepository
 * @package App\Repositories
 */
class RelCategoryLabelRepository
{
    use BaseRepository;
    
    /**
     * @var RelCategoryLabel
     * @author Luoyan
     */
    protected $model;

    /**
     * 关联表模型注入
     *
     * RelCategoryLabelRepository constructor.
     * @param $relCategoryLabel
     * @author Luoyan
     */
    public function __construct(RelCategoryLabel $relCategoryLabel)
    {
        $this->model = $relCategoryLabel;
    }
}