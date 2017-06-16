<?php

namespace App\Repositories;


use App\Model\BasicConfig;

/**
 * Class BasicConfigRepository
 * @package App\Repositories
 */
class BasicConfigRepository
{
    use BaseRepository;
    /**
     * @var BasicConfig
     */
    protected $model;

    /**
     * 服务注入
     *
     * BasicConfigRepository constructor.
     * @param $basicconfig
     */
    public function __construct(BasicConfig $basicconfig)
    {
        $this->model = $basicconfig;
    }

    /**
     * 创建网站基础配置
     *
     * @param array $data
     * @return static
     * @author: jiaohuafeng
     */
    public function createByBasicConfig(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * 获取网站基础配置
     *
     * @param array $data
     * @return static
     * @author: jiaohuafeng
     */
    public function getByBasicConfig()
    {
        return $this->model->first();
    }


    /**
     * 根据ID获取网站基础配置
     *
     * @param array $data
     * @return static
     * @author: jiaohuafeng
     */
    public function getBasicConfigById($id)
    {
        return $this->model->where('id', $id)->first();
    }


}