<?php

namespace App\Repositories;

use App\Model\LogUserLogin;

/**
 * Class LogUserLoginRepository
 * @package App\Repositories
 */
class LogUserLoginRepository
{
    use BaseRepository;
    /**
     * @var LogUserLogin
     */
    protected $model;

    /**
     * LogUserLoginRepository constructor.
     * @param LogUserLogin $logUserLogin
     * @author zhangyuchao
     */
    public function __construct(LogUserLogin $logUserLogin)
    {
        $this->model = $logUserLogin;
    }

}