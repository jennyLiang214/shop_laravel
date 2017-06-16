<?php
/**
 * Created by PhpStorm.
 * User: jiaohuafeng
 * Date: 17/5/9
 * Time: 下午8:16
 */
namespace App\Presenters;
use App\Repositories\BasicConfigRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BasicConfigPresenter
{

    /**
     * @var BasicConfigRepository
     */
    protected $basicconfig;

    /**
     * 服务注入
     *
     * ClassificationController constructor.
     */
    public function __construct(BasicConfigRepository $basicconfigRepository)
    {

        // 注入网站配置操作类
        $this->basicconfig = $basicconfigRepository;

    }

    /**
     * 过滤标签值
     *
     * @param $allAttrs
     * @param $attr
     * @return mixed
     * @author jiaohuafeng
     */
    public function getBasicConfig()
    {
        return $this->basicconfig->getByBasicConfig();
    }
}