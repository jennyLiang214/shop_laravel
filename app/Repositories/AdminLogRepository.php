<?php

namespace App\Repositories;

use App\Model\AdminOperationLog;

/**
 * Class AdminLogRepository
 * @package App\Repositories
 */
class AdminLogRepository
{
    use BaseRepository;
    /**
     * @var AdminOperationLog
     */
    protected $model;

    /**
     * 注入管理员操作日志的模型
     *
     * AdminLogRepository constructor.
     * @param AdminOperationLog $adminLog
     * @author zhangyuchao
     */
    public function __construct(AdminOperationLog $adminLog)
    {
        $this->model = $adminLog;
    }

}