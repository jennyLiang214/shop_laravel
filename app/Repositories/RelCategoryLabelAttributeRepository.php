<?php

namespace App\Repositories;

use App\Model\RelCategoryLabelAttribute;

/**
 * Class CategoryLabelRepository
 * @package App\Repositories
 */
class RelCategoryLabelAttributeRepository
{
    use BaseRepository;

    /**
     * @var RelCategoryLabelAttribute
     * @author zhulinjie
     */
    protected $model;

    /**
     * RelCategoryLabelAttributeRepository constructor.
     * @param RelCategoryLabelAttribute $relCategoryLabelAttribute
     */
    public function __construct(RelCategoryLabelAttribute $relCategoryLabelAttribute)
    {
        $this->model = $relCategoryLabelAttribute;
    }
}