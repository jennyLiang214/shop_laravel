<?php

namespace App\Repositories;

use App\Model\UserInfo;

/**
 * Class UserInfoRepository
 * @package App\Repositories
 */
class UserInfoRepository
{
    use BaseRepository;
    /**
     * @var UserInfo
     */
    protected $model;

    /**
     * UserInfoRepository constructor.
     * @param UserInfo $userInfo
     * @author zhangyuchao
     */
    public function __construct(UserInfo $userInfo)
    {
        $this->model = $userInfo;
    }

}