<?php
/**
 * Created by PhpStorm.
 * User: zhulinjie
 * Date: 2017/4/21
 * Time: 16:05
 */

namespace App\Repositories;

use App\Model\RelLabelCargo;

/**
 * Class RelLabelCargoRepository
 * @package App\Repositories
 */
class RelLabelCargoRepository
{
    use BaseRepository;
    
    /**
     * @var RelLabelCargo
     * @author zhulinjie
     */
    protected $model;

    /**
     * RelLabelCargoRepository constructor.
     * @param RelLabelCargo $relLabelCargo
     */
    public function __construct(RelLabelCargo $relLabelCargo)
    {
        $this->model = $relLabelCargo;
    }
}