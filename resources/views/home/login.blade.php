@extends('home.layouts.master')

@section('title')
    登录
@stop

@section('coreCss')
    <link rel="stylesheet" href="/AmazeUI-2.4.2/assets/css/amazeui.css"/>
@stop

@section('externalCss')
    <link href="/css/dlstyle.css" rel="stylesheet" type="text/css">
@stop

@section('header')
    @include('home.public.loginBoxtitle')
@stop

@section('content')
    <div class="login-banner">
        <div class="login-main">
            <div class="login-banner-bg"><span></span><img src="/images/big.jpg"/></div>
            <div class="login-box">

                <h3 class="title">登录商城</h3>

                <div class="clear"></div>
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <div class="login-form" style="margin-top:40px">
                    <form>
                        <div class="user-name">
                            <label for="user"><i class="am-icon-user"></i></label>
                            <input type="text" name="" id="loginName" placeholder="邮箱/手机/用户名">
                        </div>
                        <div id="loginNameErrorMessage" style="color:red;font-size:12px;margin:2px 30px"></div>
                        <div class="user-pass">
                            <label for="password"><i class="am-icon-lock"></i></label>
                            <input type="password" name="" id="password" placeholder="请输入密码">
                        </div>
                        <div id="passwordErrorMessage" style="color:red;font-size:12px;margin:2px 30px"></div>
                    </form>
                </div>

                <div class="login-links">
                    {{--<label for="remember-me"><input id="remember-me" type="checkbox">记住密码</label>--}}
                    {{--<a href="#" class="am-fr">忘记密码</a>--}}
                    <a href="{{ url('/home/register') }}" class="zcnext am-fr am-btn-default">注册</a>
                    <br/>
                </div>
                <div class="am-cf" style="margin-top:40px">
                    <input type="submit" name="" value="登 录" class="am-btn am-btn-primary am-btn-sm submit">
                </div>
                <div id="message" style="color:red;width:100%;text-align:center"></div>
                {{--<div class="partner">--}}
                    {{--<h3>合作账号</h3>--}}
                    {{--<div class="am-btn-group">--}}
                        {{--<li><a href="#"><i class="am-icon-qq am-icon-sm"></i><span>QQ登录</span></a></li>--}}
                        {{--<li><a href="#"><i class="am-icon-weibo am-icon-sm"></i><span>微博登录</span> </a></li>--}}
                        {{--<li><a href="#"><i class="am-icon-weixin am-icon-sm"></i><span>微信登录</span> </a></li>--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>
        </div>
    </div>

    @include('home.public.footer')
@stop
@section('customJs')
    <script src="{{ asset('/handle/member/validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script type="text/javascript"></script>
    <script src="{{ asset('/handle/login.js') }}" type="text/javascript"></script>
    <script>
        var loginUrl = "{{ url('/home/doLogin') }}";
        var token= "{{ csrf_token() }}";
    </script>

@stop
