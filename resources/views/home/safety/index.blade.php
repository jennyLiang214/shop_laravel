@extends('home.layouts.master')

@section('title')
    个人资料
@stop

@section('externalCss')
    <link href="/css/personal.css" rel="stylesheet" type="text/css">
    <link href="/css/infstyle.css" rel="stylesheet" type="text/css">
@stop

@section('header')
    @include('home.public.header')
@stop

@section('nav')
    @include('home.public.navTab')
@stop

@section('content')
    <div class="center">
        <div class="col-main">
            <div class="main-wrap">

                <!--标题 -->
                <div class="user-safety">
                    <div class="am-cf am-padding">
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">账户安全</strong> /
                            <small>Set&nbsp;up&nbsp;Safety</small>
                        </div>
                    </div>
                    <hr/>
                    <!-- 头像 -->
                    @include('home.public.personalHeader')
                    <div class="check">
                        <ul>
                            <li>
                                <i class="i-safety-lock"></i>
                                <div class="m-left">
                                    <div class="fore1">登录密码</div>
                                    <div class="fore2">
                                        <small>为保证您购物安全，建议您定期更改密码以保护账户安全。</small>
                                    </div>
                                </div>
                                <div class="fore3">
                                    <a href="{{  route('home.safety.changePassword')  }}">
                                        <div class="am-btn am-btn-secondary">重置</div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <i class="i-safety-iphone"></i>
                                <div class="m-left">
                                    <div class="fore1">手机验证</div>
                                    <div class="fore2">
                                        @if(!empty(\Session::get('userInfo')->tel))
                                        <small>您验证的手机：{{ substr_replace(\Session::get('userInfo')->tel,'****',3,4) }} 若已丢失或停用，请立即更换</small>
                                        @else
                                         <small>您还未绑定手机号码,请进行绑定。</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="fore3">
                                    <a href="{{ route('home.safety.changeMobile') }}">
                                        <div class="am-btn am-btn-secondary"> @if(!empty(\Session::get('userInfo')->tel))更换@else绑定@endif</div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <i class="i-safety-mail"></i>
                                <div class="m-left">
                                    <div class="fore1">邮箱验证</div>
                                    <div class="fore2">
                                        @if(!empty(\Session::get('userInfo')->email))
                                            <small>您验证的邮箱：{{ substr_replace(\Session::get('userInfo')->email,'****',2,4) }} 可用于快速找回登录密码</small>
                                        @else
                                            <small>您还未绑定电子邮箱,请进行绑定。</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="fore3">
                                    <a href="{{ url('home/safety/changeEmail') }}">
                                        <div class="am-btn am-btn-secondary">@if(!empty(\Session::get('userInfo')->email))更换@else绑定@endif</div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <i class="i-safety-idcard"></i>
                                <div class="m-left">
                                    <div class="fore1">实名认证</div>
                                    <div class="fore2">
                                        <small>用于提升账号的安全性和信任级别，认证后不能修改认证信息。</small>
                                    </div>
                                </div>
                                <div class="fore3">
                                    <a href="{{ url('/home/safety/idCard') }}">
                                        <div class="am-btn am-btn-secondary">@if(!empty(\Session::get('userInfo')->id_number))已认证@else认证@endif</div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <!--底部-->
            @include('home.public.footer')
        </div>

        @include('home.public.aside')
    </div>
@stop
@section('customJs')

    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var token= "{{ csrf_token() }}";
        var imgUrl = "{{ env('QINIU_DOMAIN') }}";
    </script>
    <script src="{{ asset('/handle/member/uploadAvatar.js') }}" type="text/javascript"></script>

@stop
