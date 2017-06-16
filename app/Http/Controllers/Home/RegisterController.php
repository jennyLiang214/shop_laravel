<?php

namespace App\Http\Controllers\Home;

use App\Repositories\IndexUserLoginRepository;
use App\Repositories\LogUserLoginRepository;
use App\Repositories\RegisterRepository;
use App\Repositories\UserInfoRepository;
use App\Tools\CodeSnippet;
use App\Tools\Common;
use App\Tools\LogOperation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use iscms\Alisms\SendsmsPusher as Sms;
use Mockery\Exception;

class RegisterController extends Controller
{

    /**
     * log日志
     *
     * @var LogOperation
     */
    protected $log;

    /**
     * 代码片段
     *
     * @var CodeSnippet
     */
    protected $codeSnippet;
    /**
     * @var RegisterRepository
     */
    protected $register;

    /**
     * @var UserInfoRepository
     */
    protected $userInfo;
    /**
     * @var IndexUserLoginRepository
     */
    protected $indexUserLogin;
    /**
     * @var LogUserLoginRepository
     */
    protected $logUserLogin;


    /**
     * 注入
     *
     * RegisterController constructor.
     * @param LogOperation $logOperation
     * @param RegisterRepository $registerRepository
     * @param UserInfoRepository $userInfoRepository
     * @param IndexUserLoginRepository $indexUserLoginRepository
     * @param LogUserLoginRepository $logUserLoginRepository
     * @param CodeSnippet $codeSnippet
     * @author zhangyuchao
     */
    public function __construct
    (
        LogOperation $logOperation,
        RegisterRepository $registerRepository,
        UserInfoRepository $userInfoRepository,
        IndexUserLoginRepository $indexUserLoginRepository,
        LogUserLoginRepository $logUserLoginRepository,
        CodeSnippet $codeSnippet

    )
    {

        // log日志
        $this->log = $logOperation;
        // 注册原始数据表辅助模型
        $this->register = $registerRepository;
        // 用户信息表
        $this->userInfo = $userInfoRepository;
        // 用户登录索引表
        $this->indexUserLogin = $indexUserLoginRepository;
        // 用户登录日志表
        $this->logUserLogin = $logUserLoginRepository;
        // 代码片段
        $this->codeSnippet = $codeSnippet;

    }

    /**
     * 返回用户注册视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function register()
    {
        return view('home.register');
    }

    /**
     * 发送手机验证码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function sendMobileCode(Request $request)
    {
        // 去用户登录表里边查询
        $result = $this->indexUserLogin->find(['login_name' => $request['tel']]);
        if ($result) {
            // 返回错误信息
            return responseMsg('手机号码已注册!', 400);
        }
        // 调用发送验证码 代码片段
        $smsResult = $this->codeSnippet->mobileCodeForSms($request['tel'], config('subassembly.autograph'), config('subassembly.template_id'));
        if (!is_bool($smsResult)) {
            return responseMsg($smsResult, 400);
        }
        return responseMsg('验证码已发送!');

    }

    /**
     * 发送有邮箱验证码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function sendEmailCode(Request $request)
    {
        $result = $this->indexUserLogin->find(['login_name' => $request['email']]);
        if ($result) {
            // 返回错误信息
            return responseMsg('邮箱已注册!', 400);
        }
        // 判断邮箱是否重复发送
        $emailResult = $this->codeSnippet->sendCodeForEmail(config('subassembly.sendCloud_template'), $request['email']);
        if (!is_bool($emailResult)) {
            return responseMsg($emailResult, 400);
        }
        return responseMsg('验证码已发送!', 400);
    }


    /**
     * 用户注册
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function createUser(Request $request)
    {
        // 密码处理
        $password = trim($request['password']);
        // 俩次密码判断
        if ($password != $request['rel_password']) {
            // 返回错误信息
            return responseMsg('两次密码输入不一致', 400);
        }

        // 判断手机还是邮箱
        if ($request['registerType'] == 1) {
            // 从redis里获取手机验证码
            $code = \Redis::get(STRING_USER_VERIFY_CODE_ . $request['tel']);
            // 拼装需要保存的数据
            $request['login_name'] = $request['tel'] = trim($request['tel']);
        } else {
            // 从redis里获取邮箱验证码
            $code = \Redis::get(STRING_USER_VERIFY_CODE_ . $request['email']);
            // 拼装需要保存的数据
            $request['login_name'] = $request['email'] = trim($request['email']);
        }
        // redis里的验证码是否存在
        if (empty($code)) {
            // 返回错误信息
            return responseMsg('验证码已失效', 400);
        }
        // 判断验证码是否正确
        if ($code != $request['code']) {
            // 返回错误信息
            return responseMsg('验证码错误', 400);
        }
        // 密码加密
        $request['password'] = bcrypt($password);
        // 拼装各个表需要的数据
        $request['login_ip'] = $request['last_login_ip'] = $request['register_ip'] = $request->getClientIp();
        // 初始化昵称
        $request['nickname'] = 'nickname';
        try {
            // 开始事物
            \DB::beginTransaction();
            // 向用户注册原始表 添加一条数据
            $registerResult = $this->register->insert($request->all());
            // 判断用户注册原始是否成功
            if (empty($registerResult)) {
                // 抛出异常
                throw new Exception(config('log.systemLog')[3]);
            }
            // 用户注册原始表的ID是用户基本表、用户登录索引表的user_id
            $request['user_id'] = $registerResult->id;
            // 向用户基本表添加一条数据
            $userInfoResult = $this->userInfo->insert($request->all());
            // 判断用户基本信息 是否成功
            if (empty($userInfoResult)) {
                // 抛出异常
                throw new Exception(config('log.systemLog')[4]);
            }
            // 向用户登录索引表添加一条数据
            $indexUserResult = $this->indexUserLogin->insert($request->all());
            // 判断登录索引表是否成功
            if (empty($indexUserResult)) {
                // 抛出异常
                throw new Exception(config('log.systemLog')[5]);
            }
            // 全部正确 事物提交
            \DB::commit();
            // 记录登录类型
            $request['third_party'] = 1;
            // 记录用户登录日志
            $logUserLoginResult = $this->logUserLogin->insert($request->all());
            // 用户登录日志记录失败 改为记录文件日志
            if (empty($logUserLoginResult)) {
                // 拼装系统日志信息
                $logMessage = Common::logMessageForOutside(config('log.systemLog')[6]);
                // 写入系统日志
                $this->log->writeSystemLog($logMessage);
            }
            // 存入用户登录信息
            \Session::set('user', $indexUserResult);
            // 存入用户基本信息
            \Session::set('userInfo', $userInfoResult);

            // 返回注册成功提示
            return responseMsg('注册成功', 200);
        } catch (Exception $e) {
            // 事物回滚
            \DB::rollBack();
            // 组装填写log日志
            $logMessage = Common::logMessageForOutside($e->getMessage());
            // 写入log日志
            $this->log->writeSystemLog($logMessage);
            // 返回失败信息
            return responseMsg('注册失败', 400);
        }


    }

    /**
     * 用户登录
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function login()
    {
        return view('home.login');
    }

}
