<?php

namespace App\Tools;

use Naux\Mail\SendCloudTemplate;
use Ramsey\Uuid\Uuid;

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

    /**
     * 处于登录状态下的操作日志信息拼装
     *
     * @param int $operator_id
     * @param string $substance
     * @param array $param
     * @return array
     * @author zhangyuchao
     */

    public static function logMessageForInside($operator_id = 0, $substance = '', $param = [])
    {
        return [
            'operator_id' => $operator_id,
            'time' => date('Y-m-d,H:i:s', time()),
            'login_ip' => request()->ip(),
            'url' => request()->url(),
            'param' => empty($param) ? request()->all() : $param,
            'content' => $substance
        ];
    }

    /**
     * 处于未登录状态下的操作日志信息拼装
     *
     * @param string $substance
     * @param array $param
     * @return array
     * @author zhangyuchao
     */
    public static function logMessageForOutside($substance = '', $param = [])
    {
        return [
            'time' => date('Y-m-d,H:i:s', time()),
            'login_ip' => request()->ip(),
            'url' => request()->url(),
            'param' => empty($param) ? request()->all() : $param,
            'content' => $substance
        ];
    }


    /**
     * 邮件发送函数
     *
     * @param $callName
     * @param $data
     * @param $user
     * @return bool
     * @author zhangyuchao
     */
    public static function sendEmail($callName, $data, $user)
    {

        $template = new SendCloudTemplate($callName, $data);

        \Mail::raw($template, function ($message) use ($user) {
            $message->from(env('SEND_CLOUD_EMAIL_FORM'), env('SEND_CLOUD_EMAIL_FORM_NAME'));

            $message->to($user);
        });

        return true;
    }

    /**
     * 获取uuid
     *
     * @return string
     * @author zhangyuchao
     */
    public static function getUuid()
    {
        //生成uuid
        $temp = Uuid::uuid1();
        $uuid = $temp->getHex();

        return $uuid;
    }

    /**
     * 查找家谱树
     * 
     * @param $arr
     * @param $id
     * @return array
     * @author zhulinjie
     */
    public static function tree($arr, $id)
    {
        $tree = array();
        while ($id != 0) {
            foreach ($arr as $v) {
                if ($v['id'] == $id) {
                    $tree[] = $v;

                    $id = $v['pid'];
                    break;
                }
            }
        }
        return $tree;
    }
}
