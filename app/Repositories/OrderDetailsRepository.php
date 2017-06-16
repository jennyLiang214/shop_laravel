<?php

namespace App\Repositories;


use App\Model\OrderDetails;

class OrderDetailsRepository
{
    use BaseRepository;

    /**
     * @var OrderDetails
     */
    protected $model;

    public function __construct(OrderDetails $orderDetails)
    {
        $this->model = $orderDetails;
    }

    /**
     * 一次添加多条数据
     *
     * @param array $param
     * @return mixed
     * @author zhangyuchao
     */
    public function insertManyData(array $param)
    {
       return  $this->model->insert($param);
    }

    /**
     * 分页获取订单详情列表(后台使用)
     * 
     * @param array $where
     * @param int $perPage
     * @return mixed
     * @author zhangyuchao
     */
    public function getListPage(array $where, $perPage = 20)
    {
        return $this->model->where($where)->with('userMessage')->with('cargoMessage')->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * 获取列表(个人中心使用)
     *
     * @param array $where
     * @return mixed
     * @author zhangyuchao
     */
    public function getList(array $where)
    {
        return $this->model->where($where)->with('cargoMessage')->orderBy('id', 'desc')->get();
    }
}