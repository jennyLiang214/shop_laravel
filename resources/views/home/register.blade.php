@extends('home.layouts.master')

@section('title')
    注册
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
    <div class="res-banner">
        <div class="res-main">
            <div class="login-banner-bg"><span></span><img src="/images/big.jpg"/></div>
            <div class="login-box">
                <div class="am-tabs" id="doc-my-tabs">
                    <ul class="am-tabs-nav am-nav am-nav-tabs am-nav-justify">
                        <li class="am-active"><a href="">邮箱注册</a></li>
                        <li><a href="">手机号注册</a></li>
                    </ul>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                    <div class="am-tabs-bd">
                        <div class="am-tab-panel am-active">
                            <form method="post">
                                <div class="user-email">
                                    <label for="email"><i class="am-icon-envelope-o"></i></label>
                                    <input type="email" name="email" id="email" placeholder="请输入邮箱账号">
                                </div>
                                <div id="emailErrorMessage" style="color:red;font-size:12px;margin:2px 30px"></div>
                                <div class="verification">
                                    <label for="code"><i class="am-icon-code-fork"></i></label>
                                    <input type="text" name="code" id="emailCode" placeholder="请输入验证码">
                                    <a class="btn" href="javascript:void(0);">
                                        <button type="button" id="dyMobileButton" class="sendEmail dyEmailButton" style="width:40%" >获取验证码</button>
                                    </a>
                                </div>
                                <div id="emailCodeErrorMessage" style="color:red;font-size:12px;margin:2px 30px"></div>
                                <div class="user-pass">
                                    <label for="password"><i class="am-icon-lock"></i></label>
                                    <input type="password" name="password" id="emailPwd" placeholder="设置密码">
                                </div>
                                <div id="emailPwdErrorMessage" style="color:red;font-size:12px;margin:2px 30px"></div>

                                <div class="user-pass">
                                    <label for="passwordRepeat"><i class="am-icon-lock"></i></label>
                                    <input type="password" name="rel_password" id="relEmailPwd" placeholder="确认密码">
                                </div>
                                <div id="relEmailPwdErrorMessage"
                                     style="color:red;font-size:12px;margin:2px 30px"></div>
                            </form>
                            <div class="login-links" style="margin:10px auto">
                                <label for="reader-me">
                                    <input id="reader-me" type="checkbox" class="emailAgree"> 点击表示您同意商城《服务协议》
                                </label>
                            </div>
                            <div class="am-cf" style="margin:10px auto">
                                <input type="submit" name="" value="注册" class="am-btn am-btn-primary am-btn-sm am-fl"
                                       onclick="submitParamForEmail()">
                            </div>

                        </div>

                        <div class="am-tab-panel">
                            <form method="post">
                                <div class="user-phone">
                                    <label for="phone"><i class="am-icon-mobile-phone am-icon-md"></i></label>
                                    <input type="tel" name="tel" id="phone" placeholder="请输入手机号" minlength="2">
                                </div>
                                <div id="telErrorMessage" style="color:red;font-size:12px;margin:2px 30px"></div>
                                <div class="verification">
                                    <label for="code"><i class="am-icon-code-fork"></i></label>
                                    <input type="text" name="code" id="telCode" placeholder="请输入验证码">
                                    <a class="btn" href="javascript:void(0);" onclick="sendMobileCode();"
                                       id="sendMobileCode">
                                        <button type="button" id="dyMobileButton" class="dyMobileButton" style="width:40%">获取验证码</button></a>
                                </div>
                                <div id="telCodeErrorMessage" style="color:red;font-size:12px;margin:2px 30px"></div>
                                <div class="user-pass">
                                    <label for="password"><i class="am-icon-lock"></i></label>
                                    <input type="password" name="password" class="password" id="telPwd"
                                           placeholder="设置密码">
                                </div>
                                <div id="telPwdErrorMessage" style="color:red;font-size:12px;margin:2px 30px"></div>
                                <div class="user-pass">
                                    <label for="passwordRepeat"><i class="am-icon-lock"></i></label>
                                    <input type="password" name="rel_password" id="relTelPwd" placeholder="确认密码">
                                </div>
                                <div id="relTelRelPwdErrorMessage"
                                     style="color:red;font-size:12px;margin:2px 30px"></div>
                            </form>
                            <div class="login-links" style="margin:10px auto">
                                <label for="reader-me">
                                    <input id="reader-me" type="checkbox" class="telAgree"> 点击表示您同意商城《服务协议》
                                </label>
                            </div>

                            <div class="am-cf" style="margin:10px auto">
                                <input type="submit" name="" value="注册" class="am-btn am-btn-primary am-btn-sm am-fl"
                                       onclick="submitParamForTel(1)">
                            </div>
                        </div>

                        <script>
                            $(function () {
                                $('#doc-my-tabs').tabs();
                            })
                        </script>
                        <div id="message" style="color:red;font-size: 14px;width:100%;text-align: center"></div>

                    </div>
                </div>

            </div>
        </div>

        @include('home.public.footer')
    </div>
@stop

@section('customJs')

    <script src="{{ asset('/handle/member/validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/function.js') }}" type="text/javascript"></script>
    <script>
        // 获取手机验证码路由
        var telVerifyCodeUrl = "{{ url('/home/register/sendMobileCode') }}";
        // 获取邮箱验证码路由
        var emailVerifyCodeUrl = "{{ url('/home/register/sendEmailCode') }}";
        // 注册路由
        var registerUrl = "{{ url('home/register/createUser') }}";
        var token= "{{ csrf_token() }}";
        var wait = 60;
    </script>
    <script src="{{ asset('/handle/register.js') }}" type="text/javascript"></script>
@endsection

