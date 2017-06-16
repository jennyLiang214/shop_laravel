<?php

namespace App\Repositories;

use App\Model\UserOperationLog;

/**
 * Class UserLogRepository
 * @package App\Repositories
 */
class UserLogRepository
{
    use BaseRepository;
    /**
     * @var UserOperationLog
     */
    protected $model;

    /**
     * UserLogRepository constructor.
     * @param UserOperationLog $userOperationLog
     * @author zhangyuchao
     */
    public function __construct(UserOperationLog $userOperationLog)
    {
        $this->model = $userOperationLog;
    }
}