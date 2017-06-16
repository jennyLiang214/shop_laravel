<?php
/**
 * Created by PhpStorm.
 * User: luoyan
 * Date: 13/3/2017
 * Time: 5:26 PM
 */

namespace App\Redis;


use App\Model\Procurement;

class GoodsCache extends MasterCache
{
    private $lUser = LIST_ADMIN_PROCU;  // 列表 list
    private $hUser = HASH_ADMIN_PROCU_;  // 列表 hash

    /**
     * 缓存列表
     *
     * @param $currentPage
     * @param $perPage
     * @return array|bool
     * @author: Luoyan
     */
    public function cacheList($currentPage, $perPage)
    {
        // 判断当前 list 是否存在
        if (!$this->exists($this->lUser)) {
            // 创建 list 索引，并且判断创建结果
            if (!$this->createListIndex()) return false;
        }
        // 获取数据的索引
        $index = $this->getPageLists($this->lUser, $perPage, $currentPage);
        if (!$index) return false;
        // 根据索引获取数据
        $users = $this->getProHash($index);
        // 获取索引总条数
        $userListLength = $this->getLength($this->lUser);

        return $this->paginateSet($users, $userListLength, $perPage);
    }

    /**
     * 页面分页数据返回
     *
     * @param $data
     * @param $total
     * @param $perPage
     * @author: Luoyan
     */
    public function paginateSet($data, $total, $perPage)
    {
        return [
            'current_page' => request('page'),
            'last_page'    => ceil($total / $perPage),
            'to'           => request('page') * $perPage,
            'data'         => $data,
            'total'        => $total
        ];
    }

    /**
     * 获取用户 hash
     *
     * @param array $indexs
     * @return array|bool
     * @author: Luoyan
     */
    public function getProHash(array $indexs)
    {
        // 根据索引获取数据 （如果为空返回空数组）
        return collect($indexs)->map(function ($index) {
            // 拼接 key
            $key = $this->hUser . $index;
            // 判断 hash 是否存在
            if ($this->exists($key)) {
                return $this->getHash($key);
            } else {
                // 通过 id 查找数据
                $user = Procurement::findById($index);
                if (!$user) return [];
                $this->createHash($user);

                return $user;
            }
        })->toArray();
    }

    /**
     * 创建 hash
     *
     * @param $data
     * @author: Luoyan
     */
    public function createHash($data)
    {
        // 数组转换
        $data = collect($data)->toArray();
        $index = $this->hUser . $data['id'];
        // 插入 hash 并判断值
        if (!$this->addHash($index, $data)) {
            \Log::info('Redis Hash 添加失败', ['data' => $data]);
        }
    }

    /**
     * 创建 list 索引
     * @author: Luoyan
     */
    public function createListIndex()
    {
        // 查询出所有用户的 id
        $userIds = Procurement::fetchListsFor('id');
        // 判断查询结果
        if (!$userIds) return false;
        // 将 id 压入 list 队列中
        $result = $this->rPushLists($this->lUser, $userIds->toArray());
        if (!$result) return false;

        return true;
    }
}