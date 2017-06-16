<?php

namespace App\Repositories;

use App\Model\RelCategoryAttribute;

/**
 * Class CategoryLabelRepository
 * @package App\Repositories
 */
class RelCategoryAttributeRepository
{
    use BaseRepository;

    /**
     * @var RelCategoryLabelAttribute
     * @author zhulinjie
     */
    protected $model;

    /**
     * RelCategoryAttributeRepository constructor.
     * @param RelCategoryAttribute $relCategoryAttribute
     */
    public function __construct(RelCategoryAttribute $relCategoryAttribute)
    {
        $this->model = $relCategoryAttribute;
    }
}