<?php

namespace App\Http\Controllers\Home;

use App\Http\Requests\ChangePassword;
use App\Repositories\IndexUserLoginRepository;
use App\Repositories\UserInfoRepository;
use App\Tools\CodeSnippet;
use App\Tools\Common;
use App\Tools\LogOperation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;
use function Qiniu\base64_urlSafeEncode;


class SafetyController extends Controller
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
     * @var IndexUserLoginRepository
     */
    protected $indexUserLogin;
    /**
     * @var UserInfoRepository
     */
    protected $userInfo;

    /**
     * 注入
     *
     * SafetyController constructor.
     * @param LogOperation $logOperation
     * @param IndexUserLoginRepository $indexUserLoginRepository
     * @param CodeSnippet $codeSnippet
     * @param UserInfoRepository $userInfoRepository
     */
    public function __construct
    (
        LogOperation $logOperation,
        IndexUserLoginRepository $indexUserLoginRepository,
        CodeSnippet $codeSnippet,
        UserInfoRepository $userInfoRepository

    )
    {
        $this->log = $logOperation;
        $this->indexUserLogin = $indexUserLoginRepository;
        $this->codeSnippet = $codeSnippet;
        $this->userInfo = $userInfoRepository;
    }

    public function index()
    {

        return view('home.safety.index');
    }

    /**
     * 更新用户密码 视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword()
    {
        return view('home.safety.changePassword');
    }

    /**
     * 重置密码
     *
     * @param ChangePassword $changePassword
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author zhangyuchao
     */
    public function modifyChangePassword(ChangePassword $changePassword)
    {

        // 获取用户
        $user = \Session::get('user');
        // 判断旧密码是否输入正确
        if (!\Hash::check($changePassword['oldPassword'], $user->password)) {

            return back()->withErrors('旧密码输入不正确');
        }
        // 判断新密码与旧密码是否一样
        if (\Hash::check($changePassword['newPassword'], $user->password)) {

            return back()->withErrors('密码没有发生变化');
        }
        // 判断密码首尾不可以存在空格
        $newPassword = trim($changePassword['newPassword']);
        // 再次判断密码长度
        if (strlen($newPassword) < 6) {
            // 返回信息提示
            return back()->withErrors('新密码中不可以存在空格');
        }
        // 更新密码
        $indexUserLoginResult = $this->indexUserLogin->update(['user_id' => $user->user_id], ['password' => bcrypt($newPassword)]);

        // 判断是否更新成功
        if (empty($indexUserLoginResult)) {
            // 返回错误信息
            return back()->withErrors('密码重置失败');
        }

        // 成功记录用户操作日志
        $logMessage = Common::logMessageForInside($user->user_id, config('log.userLog')[3]);
        // 填写操作日志
        $this->log->writeUserLog($logMessage);

        // 返回视图
        return redirect('home/safety/changePassword?success=1');
    }

    /**
     * 绑定手机视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function changeMobile()
    {
        return view('home.safety.changeMobile');
    }

    /**
     * 绑定邮箱视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function changeEmail()
    {
        return view('home.safety.changeEmail');
    }

    /**
     * 更改绑定 确认原账号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function confirmMobileCode(Request $request)
    {
        // 判断原账号类型
        if ($request['sendType'] == 1) {
            // 原账号为手机
            $tel = \Session::get('userInfo')->tel;
            // 调用阿里大鱼发送短信
            $result = $this->codeSnippet->mobileCodeForSms($tel, config('subassembly.autograph'), config('subassembly.template_id'));
        } else {
            // 原账号为邮箱
            $email = \Session::get('userInfo')->email;
            // 调用邮件发送验证码
            $result = $this->codeSnippet->sendCodeForEmail(config('subassembly.sendCloud_template'), $email);
        }
        // 判断返回信息
        if (!is_bool($result)) {
            return responseMsg($result, 400);
        }
        // 返回页面信息
        if ($result) {
            return responseMsg('验证码已发送', 200);
        }
        return responseMsg('发送失败', 400);


    }

    /**
     * 更改绑定 验证码初次验证
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function checkVerifyCode(Request $request)
    {
        // 判断验证类型 邮箱或者手机号
        if ($request['sendType'] == 1) {
            // 获取参数
            $param = \Session::get('userInfo')->tel;
        } else {
            // 获取参数
            $param = \Session::get('userInfo')->email;
        }
        // 从redis取出验证码
        $code = \Redis::get(STRING_USER_VERIFY_CODE_ . $param);
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

        // 返回正确信息
        return responseMsg('验证成功');
    }

    /**
     * 发送验证码 (手机与邮箱)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function bindSendCode(Request $request)
    {
        // 判断验证码 是否已发送(防止频繁对数据库查询)
        $exists = \Redis::exists(STRING_USER_VERIFY_CODE_ . $request['login_name']);
        if (!empty($exists)) {
            // 返回错误信息
            return responseMsg('验证码已发送', 400);
        }
        // 检查登录账号是否重复
        $indexUserResult = $this->indexUserLogin->find(['login_name' => $request['login_name']]);
        // 判断绑定类型
        if ($request['sendType'] == 1) {
            // 根据绑定类型 返回错误信息
            if (!empty($indexUserResult)) {
                return responseMsg('手机号码已使用', 400);
            }
            // 调用阿里大鱼发送短信
            $result = $this->codeSnippet->mobileCodeForSms($request['login_name'], config('subassembly.autograph'), config('subassembly.template_id'));
        } else {
            // 调用邮箱发送验证码函数
            $result = $this->codeSnippet->sendCodeForEmail(config('subassembly.sendCloud_template'), $request['login_name']);
        }
        // 判断返回值
        if (!is_bool($result)) {
            // 返回错误信息
            return responseMsg($result, 400);
        } elseif ($result) {
            // 返回正确信息
            return responseMsg('验证码已发送,请注意查收!');
        } else {
            // 返回错误信息
            return responseMsg('验证码发送失败', 400);
        }

    }

    /**
     * 更换与绑定账号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function bindLoginUser(Request $request)
    {

        $code = \Redis::get(STRING_USER_VERIFY_CODE_ . $request['loginName']);
        // redis里的验证码是否存在
        if (empty($code)) {
            // 返回错误信息
            return responseMsg('验证码已失效', 400);
        }
        // 判断验证码是否正确
        if ($code != $request['newCode']) {
            // 返回错误信息
            return responseMsg('验证码错误', 400);
        }
        // 获取用户
        $user = \Session::get('user');
        // 更新用户基本表使用数据
        if ($request['bindType'] == 1) {
            $userInfoParam['tel'] = $request['loginName'];
        } else {
            $userInfoParam['email'] = $request['loginName'];
        }
        try {
            // 开始事物
            \DB::beginTransaction();
            // 用户登录索引变  1 更新 2 新添加
            if ($request['bindStatus'] == 1) {
                $userLoginResult = $this->indexUserLogin->update(
                    ['user_id' => $user->id],
                    ['login_name' => $request['loginName']]
                );
            } else {
                $param = [
                    'login_name' => $request['loginName'],
                    'password' => $user->password,
                    'user_id' => $user->user_id,
                    'last_login_ip' => request()->ip(),
                ];
                $userLoginResult = $this->indexUserLogin->insert($param);
            }
            if (empty($userLoginResult)) {
                throw new Exception(config('log.systemLog')[7]);
            }
            // 更新用户基本信息表
            $userInfoResult = $this->userInfo->update(['user_id' => $user->user_id], $userInfoParam);
            if (empty($userInfoResult)) {

                throw new Exception(config('log.systemLog')[8]);
            }
            \DB::commit();
            if ($request['bindType'] == 1) {
                \Session::get('userInfo')->tel = $userInfoParam['tel'];
            } else {
                \Session::get('userInfo')->email = $userInfoParam['email'];
            }
            $logMessage = Common::logMessageForInside(\Session::get('user')->user_id, config('log.userLog')[4]);
            $this->log->writeUserLog($logMessage);
            return responseMsg('绑定成功');
        } catch (Exception $e) {
            // 事物回滚
            \DB::rollBack();
            // 组装填写log日志
            $logMessage = Common::logMessageForOutside($e->getMessage());
            // 写入log日志
            $this->log->writeSystemLog($logMessage);
            // 返回失败信息
            return responseMsg('绑定失败', 400);
        }

    }


    /**
     * 初次绑定邮箱
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function bingEmail(Request $request)
    {
        $exists = \Redis::exists(STRING_USER_VERIFY_CODE_ . $request['email']);
        if (!empty($exists)) {
            // 返回错误信息
            return responseMsg('邮件已发送', 400);
        }
        $IndexUserLoginResult = $this->indexUserLogin->find(['login_name' => $request['email']]);
        if (!empty($IndexUserLoginResult)) {
            return responseMsg('电子邮箱已使用');
        }
        $data = [
            'name' => \Session::get('userInfo')->nickname,
            'token' => 'email=' . $request['email'] . '&token=' . md5($request['email'])
        ];
        //Common::sendEmail('bing_email', $data, $request['email']);
        $this->codeSnippet->sendCodeForEmail(config('subassembly.sendCloud_bind_email'),  $request['email'],$data);
        \Redis::sEtex(STRING_USER_VERIFY_CODE_ . $request['email'], 86400, md5($request['email']));
        return responseMsg('邮件已发送,请注意查收');

    }

    /**
     * 初次绑定邮箱验证
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author zhangyuchao
     */
    public function checkEmail(Request $request)
    {
        $url = '/home/safety/changeEmail?message=';
        if (empty($request['email']) || empty($request['token'])) {
            return redirect($url . base64_urlSafeEncode('非法操作'));
        }
        $email = \Redis::get(STRING_USER_VERIFY_CODE_ . $request['email']);
        if (empty($email)) {

            return redirect($url . base64_urlSafeEncode('邮件已逾期,请重新获取'));
        }
        if ($email != $request['token']) {

            return redirect($url . base64_urlSafeEncode('验证失败,请重新获取'));
        }
        try {
            // 开始事物
            \DB::beginTransaction();
            $user = \Session::get('user');
            $param = [
                'login_name' => $request['email'],
                'password' => $user->password,
                'user_id' => $user->user_id,
                'last_login_ip' => request()->ip(),
            ];
            $userLoginResult = $this->indexUserLogin->insert($param);
            if (empty($userLoginResult)) {
                throw new Exception(config('log.systemLog')[7]);
            }
            // 更新用户基本信息表
            $userInfoResult = $this->userInfo->update(['user_id' => $user->user_id], ['email' => $request['email']]);
            if (empty($userInfoResult)) {

                throw new Exception(config('log.systemLog')[8]);
            }
            \DB::commit();
            \Session::get('userInfo')->email = $request['email'];

            return redirect($url . encrypt('绑定成功'));
        } catch (Exception $e) {
            // 事物回滚
            \DB::rollBack();
            // 组装填写log日志
            $logMessage = Common::logMessageForInside($user->user_id, $e->getMessage());
            // 写入log日志
            $this->log->writeSystemLog($logMessage);
            // 返回失败信息
            return redirect($url . encrypt('绑定失败'));
        }

    }

    /**
     * 绑定身份证号视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function idCard()
    {
        return view('home.safety.idCard');
    }

    /**
     * 实名认证操作处理
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author zhangyuchao
     */
    public function handleIdCard(Request $request)
    {
        unset($request['_token']);
        // 更新实名认证信息
        $userInfoResult = $this->userInfo->update(['user_id' => \Session::get('userInfo')->user_id], $request->all());
        // 判断是否更新成功
        if (!empty($userInfoResult)) {
            // 成功保存session
            \Session::get('userInfo')->realname = $request['realname'];
            \Session::get('userInfo')->id_number = $request['id_number'];
            // 重定向原页面
            return redirect('/home/safety/idCard');
        }
        // 带错误信息重定向到原页面
        return redirect('/home/safety/idCard?message=' . encrypt('认证失败'));
    }
}
