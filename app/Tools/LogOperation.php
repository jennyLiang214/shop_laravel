<?php

namespace App\Tools;

use App\Repositories\AdminLogRepository;
use App\Repositories\UserLogRepository;
use Illuminate\Log\Writer;
use Monolog\Logger;

class LogOperation
{
    /**
     * @var AdminLogRepository
     */
    protected $adminLog;

    /**
     * @var UserLogRepository
     */
    protected $userLog;
    /**
     *  后台管理员操作日志名称
     * @var string
     */
    protected $adminLogName = '/admin/';

    /**
     * 前台用户操作日志名称
     * @var string
     */
    protected $userLogName = '/user/';
    /**
     * 前台程序报错日志
     * @var string
     */
    protected $systemLog = '/system/';
    /**
     *  日志错误等级
     * @var string
     */
    protected $logLevel = 'info';
    /**
     * 日志在storage目录的位置
     * @var string
     */
    protected $logPath = '/logs/';
    /**
     * 日志保存的天数
     * @var int
     */
    protected $logSaveTime = 45;

    /**
     * 注入写入日志需要的文件(前台用户和管理员操作)
     *
     * LogOperation constructor.
     * @param AdminLogRepository $adminOperationLog
     * @param UserLogRepository $userLogRepository
     * @author zhangyuchao
     */
    public function __construct(AdminLogRepository $adminOperationLog, UserLogRepository $userLogRepository)
    {
        $this->adminLog = $adminOperationLog;
        $this->userLog = $userLogRepository;
    }

    /**
     * 写入后台管理员操作日志
     *
     * @param array $message
     * @param bool $logType true 保存到文件 false 保存到数据库
     * @author zhangyuchao
     */
    public function writeAdminLog(array $message, $logType = true)
    {
        // 拼接记录日志需要的内容
        $message['content'] = $this->prompt($message);
        $event = $message['param'];
        $event['url'] = $message['url'];
        // 选择日志类型
        if ($logType) {
            // 保存到log日志
            $this->writeForFile($this->adminLogName, $message['content'], $event);
        } else {
            // 数据库的event字段为json类型
            $message['events'] = json_encode($event);
            // 操作日志保存到数据库
            $result = $this->writeForDB($message, 1);
            // 操作日志保存数据库失败,到文件
            if (empty($result)) $this->writeForFile($this->adminLogName, $message['content'], $event, true);
        }
    }

    /**
     * 前台用户的操作日志
     *
     * @param array $message
     * @param bool $logType true 保存到文件 false 保存到数据库
     * @author zhangyuchao
     */
    public function writeUserLog(array $message, $logType = true)
    {
        $message['content'] = $this->prompt($message);
        $event = $message['param'];
        $event['url'] = $message['url'];
        // 选择日志类型
        if ($logType) {
            // 保存到log日志
            $this->writeForFile($this->userLogName, $message['content'], $event);
        } else {

            // 数据库的event字段为json类型
            $message['events'] = json_encode($event);
            // 操作日志保存到数据库
            $result = $this->writeForDB($message, 2);
            // 操作日志保存数据库失败,到文件
            if (empty($result)) {

                $this->writeForFile($this->userLogName, $message['content'], $event, true);
            }
        }
    }

    /**
     * 记录系统日志
     *
     * @param array $message
     * @author zhangyuchao
     */
    public function writeSystemLog(array $message)
    {
        $event = $message['param'];
        $event['url'] = $message['url'];
        $this->writeForFile($this->systemLog, $message['content'], $event);
    }

    /**
     * 操作日志保存到数据库
     *
     * @param $param
     * @param $platformType 1 保存到管理员操作日志表   其他 保存到用户操作日志表
     * @return static
     * @author zhangyuchao
     */
    private function writeForDB($param, $platformType)
    {
        if ($platformType == 1) {

            return $this->adminLog->insert($param);
        } else {

            return $this->userLog->insert($param);
        }

    }

    /**
     * 文件记录操作日志
     *
     * @param $logName
     * @param $record
     * @param array $param
     * @param bool $again
     * @author zhangyuchao
     */
    private function writeForFile($logName, $record, array $param, $again = false)
    {
        if ($again) {
            $record = '数据库填写日志失败，改写为log日志' . $record;
        }
        $this->log($logName, $record, $param);
    }

    /**
     * 拼接操作日志内容
     *
     * @param array $message
     * @return string
     * @author zhangyuchao
     */
    private function prompt(array $message)
    {
        return 'ID为' . $message['operator_id'] . '的用户,在' . $message['time'] . '进行' . $message['content'] . ',IP地址为' . $message['login_ip'] . ':';
    }

    /**
     * 根据操作存放不同的log文件
     *
     * @param $logName
     * @param $record
     * @param $param
     * @author zhangyuchao
     */
    private function log($logName, $record, $param)
    {
        $log = new Writer(new Logger('signin'));
        $log->useDailyFiles(storage_path() . $logName . $this->logPath, $this->logSaveTime);
        $log->write($this->logLevel, $record, $param);
    }

}