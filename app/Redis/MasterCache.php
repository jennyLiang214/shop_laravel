<?php
/**
 * redis底层公共类
 * User: 郭庆
 * Date: 2017/01/11
 * Time: 15:51
 * @author:郭庆
 */
namespace App\Redis;

use Redis;

class MasterCache
{
    /**
     * 判断key是否存在
     * @param $key string redis的key
     * @return bool
     * @author 郭庆
     */
    public function exists($key)
    {
        return Redis::exists($key);  //查询key是否存在
    }

    /**
     * 获取redis缓存里某一个list中的指定页的所有元素
     * @param $key string list的key
     * @param $nums int 每页显示条数
     * @param $nowPage int  当前页数
     * @return array
     * @author 郭庆
     */
    public function getPageLists($key, $nums, $nowPage)
    {
        //起始偏移量
        $offset = $nums * ($nowPage - 1);

        //获取条数
        $totals = $offset + $nums - 1;

        //获取缓存的列表索引并返回
        return $this->getBetweenList($key, $offset, $totals);

    }

    /**
     * 获取指定范围内的list数据
     * @param $key string 指定list Key
     * @param $start int 开始位置
     * @param $end int 结束位置
     * @return array
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');
     * $redis->lRange('key1', 0, -1); // array('A', 'B', 'C')
     * </pre>
     * @author 郭庆
     */
    public function getBetweenList($key, $start, $end)
    {
        return Redis::lrange($key, $start, $end);
    }

    /**
     * 获取hash的全部字段数据
     * @param $key string hash的key
     * @param $time int 如果需要单独设置时间则传第二个参数
     * @return [] 成功： array 全部字段的键值对 失败：bool false
     * @author 郭庆
     */
    public function getHash($key, $time = HASH_OVERTIME)
    {
        $data = Redis::hGetAll($key);
        if (!$data) return false;
        //设置生命周期
        $this->setTime($key, $time);
        return $data;
    }

    /**
     * 获取hash的指定几个字段的数据
     * @param $key string hash的key
     * @param $key array hash的指定几个字段 array('field1', 'field2')
     * @return array
     * @author 郭庆
     */
    public function getHashFileds($key, $fields)
    {
        $i = 0;
        $values = Redis::hMGet($key, $fields);
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $values[$i++];
        }
        return $data;
    }

    /**
     * 将一条记录写入hash
     * @param $key string hash的key
     * @param $data array 存入hash的具体字段和值
     * @param $time int 如果需要单独设置时间则传这个参数
     * @return bool
     * @author 郭庆
     */
    public function addHash($key, $data, $time = HASH_OVERTIME)
    {
        if (empty($key) || empty($data)) return false;

        $result = true;
        if (!$this->exists($key)) {
            //写入hash
            $result = Redis::hMset($key, $data);
        }
        if (!$result) {
            \Log::error('写入hash出错' . $key);
            return false;
        } else {
            //设置生命周期
            return $this->setTime($key, $time);
        }
    }

    /**
     * 修改一条hash记录
     * @param $key string hash的key
     * @param $data array 所要修改的键值对
     * @return bool
     * @author 郭庆
     */
    public function changeOneHash($key, $data)
    {
        //写入hash
        if (!Redis::hMset($key, $data)) return false;
        //设置生命周期
        $this->setTime($key);
        return true;
    }

    /**
     * 删除指定的 keys.
     * @param $key string 所要删除的key(可以为数组也可以为字符串)
     * @return int Number 删除了的条数
     * @example
     * <pre>
     * $redis->set('key1', 'val1');
     * $redis->set('key2', 'val2');
     * $redis->set('key3', 'val3');
     * $redis->set('key4', 'val4');
     * $redis->delete('key1', 'key2');          // return 2
     * $redis->delete(array('key3', 'key4'));   // return 2
     * </pre>
     * @author 郭庆
     */
    public function delKey($key)
    {
        if (empty($key)) return false;
        return Redis::del($key);
    }

    /**
     * 对list进行右推（推一个/多个）
     * @param $key string listkey
     * @param $lists array [guid1,guid2] / $lists string 一次推入一个list
     * @return bool
     * @author 郭庆
     */
    public function rPushLists($key, $lists)
    {
        if (empty($key) || empty($lists)) return false;

        //执行写list操作
        return Redis::rpush($key, $lists);
    }

    /**
     * 对list进行左推（可以推一个也可以多个）
     * @param $key string listkey
     * @param $lists array [guid1,guid2] / $lists string 一次推入一个list
     * @return bool|int 失败返回false，成功插入条数
     * @author 郭庆
     */
    public function lPushLists($key, $lists)
    {
        if (empty($key) || empty($lists)) return false;

        //执行写list操作
        return Redis::lpush($key, $lists);
    }

    /**
     * 将元素插入到指定list元素的前面或者后面
     *
     * @param $key string list key
     * @param $position "after"/"before"
     * @param $old mixed 指定的元素
     * @param $new mixed 所要插入的元素
     * @return int|boolean
     * @author 郭庆
     */
    public function lInsert($key, $position, $old, $new)
    {
        if (empty($key) || empty($position) || empty($old) || empty($new)) return false;

        return Redis::LINSERT($key, $position, $old, $new);
    }

    /**
     * 设置hash缓存的生命周期
     * @param $key  string  需要设置的key
     * @param $time int 如果需要单独设置时间则传这个参数
     * @return bool 设置成功true 否则false
     * @author 郭庆
     */
    public function setTime($key, $time = HASH_OVERTIME)
    {
        return Redis::expire($key, $time);
    }

    /**
     * 获取 现有list 的长度
     * @param $key string list的key
     * @return int 对应key的list长度
     * @author 郭庆
     */
    public function getLength($key)
    {
        return Redis::llen($key);
    }

    /**
     * 删除一条list记录
     * @param $key string list的key
     * @param $guid string 所要删除的list元素
     * @return bool|int 失败返回false，成功删除数目
     * @author 郭庆
     */
    public function delList($key, $guid)
    {
        if ($this->exists($key)) return Redis::lrem($key, 0, $guid);
        return true;
    }

    /**
     * 添加一个新的短存的string redis
     * @param $key string key
     * @param $value
     * @param $time int 设置存活时间
     * @return bool
     * @author 郭庆
     */
    public function addString($key, $value, $time = STRING_OVERTIME)
    {
        if (empty($key)) return false;
        if (!Redis::Set($key, $value)) return false;
        //设置生命周期
        return $this->setTime($key, $time);
    }

    /**
     * 将 string key 中储存的数字值增一
     * @param   string $key
     * @return  int    the new value
     * @author 郭庆
     */
    public function incre($key)
    {
        if (empty($key)) return false;
        return Redis::incr($key);
    }

    /**
     * 给hash中某一个字段加一个值
     * @param $key string hash的key
     * @param $filed string 所要自增的字段
     * @param $value int 所要自增的值
     * @return array
     * @author 郭庆
     */
    public function hIncrBy($key, $filed, $value)
    {
        return Redis::hIncrBy($key, $filed, $value);
    }

    /**
     * 得到一个string
     * @param   string $key
     * @return  string|bool: If key didn't exist, FALSE is returned. Otherwise, the value related to this key is returned.
     * @author 郭庆
     */
    public function getString($key)
    {
        if (empty($key)) return false;
        $data = Redis::get($key);
        if (!$data) return false;
        //设置生命周期
        $this->setTime($key, STRING_OVERTIME);
        return $data;
    }

    /**
     * 清空redis缓存
     * @param
     * @return array
     * @author 郭庆
     */
    public function destroy()
    {
        return Redis::flushAll();
    }

    /**
     * 获取到指定正则匹配的所有key
     * @param string $pattern
     * @return array
     * @author 郭庆
     */
    public function getKeys($pattern)
    {
        return Redis::keys($pattern);
    }

    /**
     * 插入集合
     * @param string $key
     * @param string $string
     * @return mixed
     * @author 张洵之
     */
    public function sadd($key, $string)
    {
        return Redis::sAdd($key, $string);
    }

    /**
     * 得到集合成员数量
     * @param string $key 键
     * @return int
     * @author 张洵之
     */
    public function scard($key)
    {
        return Redis::sCard($key);
    }
}