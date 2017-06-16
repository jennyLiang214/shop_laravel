<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserLoginRequest;
use App\Repositories\AdminUserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    /**
     * @var
     */
    protected $users;

    /**
     * UserController constructor.
     * @param $users
     */
    public function __construct(AdminUserRepository $users)
    {
        $this->users = $users;
        // 中间件
        $this->middleware('user:admin')->only('logout');
    }

    /**
     * 后台登录页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function login()
    {
        return view('admin.login');
    }

    /**
     * 登陆方法
     *
     * @param UserLoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author: Luoyan
     */
    public function store(UserLoginRequest $request)
    {
        // 后台用户登陆
        if (!$this->attemptLogin($request)) {
            // 登陆失败返回错误信息
            return back()->withErrors('手机号或密码错误!')->withInput();
        }
        // 友好提示
        \Flashy::info('欢迎回来! ^_^');

        // 返回后台首页
        return redirect()->route('admin.index');
    }

    /**
     * 登陆认证
     *
     * @param Request $request
     * @return bool
     * @author: Luoyan
     */
    public function attemptLogin(Request $request)
    {
        // 调用认证方法
        return $this->guard()->attempt(
        // 获取需要认证的字段
            $this->credentials($request)
        );
    }

    /**
     * 获取认证数据/获取手机号码密码
     *
     * @param Request $request
     * @return array
     * @author: Luoyan
     */
    protected function credentials(Request $request)
    {
        return $request->only('tel', 'password');
    }

    /**
     * 获取认证类型/后台认证
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     * @author: Luoyan
     */
    public function guard()
    {
        return \Auth::guard('admin');
    }

    /**
     * 用户推出认证
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author: Luoyan
     */
    public function logout()
    {
        // 退出认证
        $this->guard()->logout();

        // 跳转到登陆页面
        return redirect()->route('admin.login');
    }
}
