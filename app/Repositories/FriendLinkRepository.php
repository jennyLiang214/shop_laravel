<?php
namespace App\Repositories;
use App\Model\FriendLink;
/**
 * Class FriendLinkRepository
 * @package App\Repositories
 */
class FriendLinkRepository
{
    use BaseRepository;

    /**
     * 友情链接模型
     *
     * @var FriendLink
     * @author wutao
     */
    protected $model;

    /**
     * 模型注入
     *
     * FriendLinkRepository constructor.
     * @param $friendLink
     * @author
     */
    public function __construct(FriendLink $friendLink)
    {
        $this->model = $friendLink;
    }

    /**
     * @param array $where
     * @param string $fieldName
     * @param string $direction
     * @return mixed
     */
    public function select(array $where = [], $fieldName = 'id', $direction = 'asc')
    {
        return $this->model->where($where)->orderBy($fieldName, $direction)->get();
    }
}