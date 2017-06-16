<?php
namespace App\Tools;


class Common
{
    /**
     * 数组转换对象
     *
     * @param $e
     * @return object|void
     * @author zhangyuchao
     */
    public static function arrayToObject($e)
    {

        if (gettype($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $e[$k] = (object)self::arrayToObject($v);
        }

        return (object)$e;
    }

    /**
     * 对象转换数组
     *
     * @param $e
     * @return array|void
     * @author zhangyuchao
     */
    public static function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array)self::objectToArray($v);
        }

        return $e;
    }



}
