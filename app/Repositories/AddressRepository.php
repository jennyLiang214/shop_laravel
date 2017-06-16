<?php

namespace App\Repositories;


use App\Model\Address;

class AddressRepository
{
    use BaseRepository;
    /**
     * @var Address
     */
    protected $model;

    public function __construct(Address $address)
    {
        $this->model = $address;
    }

}