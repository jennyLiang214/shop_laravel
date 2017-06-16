<?php

namespace App\Repositories;


use App\Model\Order;

class OrdersRepository
{
    use BaseRepository;
    /**
     * @var Order
     */
    protected $model;

    /**
     * OrdersRepository constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->model = $order;
    }
    public function a()
    {
       return  $this->model->with(['detailsMessage'])->get();
    }
}