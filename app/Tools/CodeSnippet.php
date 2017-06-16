<?php

namespace App\Tools;

use iscms\Alisms\SendsmsPusher as Sms;

class CodeSnippet
{
    /**
     * 阿里大鱼
     *
     * @var Sms
     */
    protected $sms;
    protected $log;

    public function __construct(Sms $sms, LogOperation $log)
    {
        $this->sms = $sms;
        $this->log = $log;
    }

    /**
     * 阿里大鱼发送验证码 代码片段
     *
     * @param $tel
     * @param $autograph
     * @param $templateId
     * @return array|bool
     * @author zhangyuchao
     */
    public function mobileCodeForSms($tel, $autograph, $templateId)
    {

        // 判断手机号码是否重复发送
        $exists = \Redis::exists(STRING_USER_VERIFY_CODE_ . $tel);
        if (!empty($exists)) {
            // 返回错误信息
            return '重复发送';
        }
        // 生成验证码
        $num = rand(100000, 999999);
        // 需要变化
        $content = json_encode(['code' => "$num"]);
        // 发送短信验证码
        $data = $this->sms->send("$tel", "$autograph", "$content", "$templateId");
        if (property_exists($data, 'result')) {
            // 存入redis 防止频繁发送验证码
            \Redis::sEtex(STRING_USER_VERIFY_CODE_ . $tel, 600, $num);
            return true;
        } else {
            // 获取失败原因
            $reason = array_merge(request()->all(), Common::objectToArray($data));
            // 拼装日志信息
            $logMessage = Common::logMessageForOutside(config('log.systemLog')[1], $reason);
            // 写入日志
            $this->log->writeSystemLog($logMessage);
            // 返回结果
            return false;
        }

    }

    /**
     * 使用sendcloud发送邮箱验证码
     *
     * @param $callName 模板名称
     * @param $email 收件人
     * @param array $data 传递参数
     * @return string
     * @author zhangyuchao
     */
    public function sendCodeForEmail($callName, $email, $data = [])
    {
        // 判断邮件是否重复发送
        $exists = \Redis::exists(STRING_USER_VERIFY_CODE_ . $email);
        if (!empty($exists)) {
            // 返回错误信息
            return '重复发送';
        }
        // 生成验证码
        $data['code'] = rand(100000, 999999);
        // 调用邮件发送模板
        Common::sendEmail($callName, $data, $email);
        // 写入redis
        \Redis::sEtex(STRING_USER_VERIFY_CODE_ . $email, 600, $data['code']);
        // 返回正确信息
        return true;
    }
}