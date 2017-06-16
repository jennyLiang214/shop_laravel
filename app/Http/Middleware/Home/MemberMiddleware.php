<?php

namespace App\Http\Middleware\Home;

use Closure;

class MemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 判断用户是否认证
        if (empty(\Session::get('user'))) {
            // 用户未认证返回登陆页面
            return redirect()->route('home.login');
        }
        return $next($request);
    }
}
