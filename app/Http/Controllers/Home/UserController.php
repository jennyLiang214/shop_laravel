<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\IndexUserLoginRepository;
use App\Repositories\LogUserLoginRepository;
use App\Repositories\UserInfoRepository;
use App\Tools\Common;
use App\Tools\LogOperation;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var IndexUserLoginRepository
     */
    protected $indexUserLogin;
    /**
     * @var LogUserLoginRepository
     */
    protected $logUserLogin;
    /**
     * @var LogOperation
     */
    protected $log;
    /**
     *
     */
    protected $userInfo;

    /**
     * 注入
     * UserController constructor.
     * @param IndexUserLoginRepository $indexUserLoginRepository
     * @param LogUserLoginRepository $logUserLoginRepository
     * @param LogOperation $logOperation
     * @param UserInfoRepository $userInfoRepository
     * @author zhangyuchao
     */
    public function __construct
    (
        IndexUserLoginRepository $indexUserLoginRepository,
        LogUserLoginRepository $logUserLoginRepository,
        LogOperation $logOperation,
        UserInfoRepository $userInfoRepository
    )
    {
        $this->indexUserLogin = $indexUserLoginRepository;
        $this->logUserLogin = $logUserLoginRepository;
        $this->log = $logOperation;
        $this->userInfo = $userInfoRepository;
    }
    /**
     * 用户注册
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function register()
    {
        return view('home.register');
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

    /**
     * 用户登录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function doLogin(Request $request)
    {
        // 根据条件查找用户
        $result = $this->indexUserLogin->find(['login_name' =>$request['loginName'] ]);
        // 判断是否找到该用户
        if(empty($result)) {
            // 未找到返回信息
            return responseMsg('用户没有注册',400);
        }
        // 检测密码
        if (!\Hash::check($request['password'], $result->password)) {

            return responseMsg('密码输入不正确', 400);
        }

        // 用户信息存入session
        \Session::set('user',$result);
        // 查询用户基本信息
        $userInfo = $this->userInfo->find(['user_id'=>$result->user_id]);
        // 用户信息表存入session
        \Session::set('userInfo',$userInfo);
        // 拼装登录日志信息
        $request['login_ip'] = $request->getClientIp();
        $request['third_party'] = 1;
        $request['login_name'] = $request['loginName'];
        $request['user_id'] = $result->user_id;
        // 把登录日志写进数据库
        $logUserLoginResult = $this->logUserLogin->insert($request->all());
        // 判断登录日志信息是否写进数据库
        if(!empty($logUserLoginResult)){
            // 数据库写入不成功 记录系统文件日志
            $message = Common::logMessageForOutside(config('log.systemLog')[6]);
            // 写入系统日志
            $this->log->writeSystemLog($message);
        }

        // 返回正确信息
        return responseMsg('登录成功',200);
    }

    /**
     * 退出登录
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function logout(Request $request)
    {
        // 拼装记录日志的参数
        $message = Common::logMessageForInside(\Session::get('user')->user_id, config('log.userLog')[1]);
        // 写入日志
        $this->log->writeUserLog($message);
        // 删除Session
        \Session::forget('user');
        return redirect('/home/index');

    }
}
