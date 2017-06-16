<?php

namespace App\Repositories;


trait BaseRepository
{
    /**
     * 添加数据
     *
     * @param array $param
     * @return bool
     * @author zhangyuchao
     */
    public function insert(array $param)
    {
        if(empty($param)) {

            return false;
        }

       return $this->model->create($param);
    }

    /**
     * 查询单条数据
     *
     * @param array $where
     * @param array $columns
     * @return bool
     * @author zhangyuchao
     */
    public function find(array $where, $columns = ['*'])
    {
        if (empty($where)) {
            return false;
        }
        return $this->model->where($where)->select($columns)->first();
    }

    /**
     * 获取多条数据指定字段
     *
     * @param array $where
     * @param array $columns
     * @return bool
     * @author zhangyuchao
     */
    public function lists(array $where, $columns)
    {
        if (empty($where) || empty($columns)) {

            return false;
        }
        
        return $this->model->where($where)->pluck(implode(',', $columns));
    }

    /**
     * 获取多条数据并排序
     *
     * @param array $where
     * @param string $fieldName
     * @param string $direction
     * @return bool
     * @author zhangyuchao
     */
    public function select(array $where = [], $fieldName = 'id', $direction = 'asc')
    {
        return $this->model->where($where)->orderBy($fieldName, $direction)->get();
    }

    /**
     * 获取分页数据并排序
     *
     * @param array $where
     * @param int $perPage
     * @param string $fieldName
     * @param string $direction
     * @return mixed
     * @author zhangyuchao
     */
    public function paging(array $where, $perPage = 20, $fieldName = 'id', $direction = 'asc')
    {
        return $this->model->where($where)->orderBy($fieldName, $direction)->paginate($perPage);
    }

    /**
     * 计算数据条数
     *
     * @param array $where
     * @return mixed
     * @author zhangyuchao
     */
    public function count(array $where)
    {
        return $this->model->where($where)->count();
    }

    /**
     * 修改数据
     *
     * @param array $where
     * @param array $param
     * @return mixed
     * @author zhangyuchao
     */
    public function update(array $where, array $param)
    {
        if (empty($where) || empty($param)) {

            return false;
        }

        return $this->model->where($where)->update($param);
    }

    /**
     * 删除数据
     * 
     * @param $where
     * @return bool
     * @author zhangyuchao
     */
    public function delete(array $where)
    {
        if (empty($where)) {
            
            return false;
        }
        
        return $this->model->where($where)->delete();
    }

}