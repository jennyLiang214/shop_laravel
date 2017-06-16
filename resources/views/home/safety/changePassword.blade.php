@extends('home.layouts.master')

@section('title')
    个人资料
@stop

@section('externalCss')
    <link href="/css/personal.css" rel="stylesheet" type="text/css">
    <link href="/css/stepstyle.css" rel="stylesheet" type="text/css">
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

                <div class="am-cf am-padding">
                    <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">重置密码</strong> /
                        <small>Password</small>
                    </div>
                </div>
                <hr/>
                @if (count($errors) > 0)
                    <div class="alert alert-danger" style="width:100%;text-align: center;color:red;margin:20px auto;background-color:#f2dede;padding:20px;font-size:10px">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!--进度条-->
                <div class="m-progress">
                    <div class="m-progress-list">
							<span class="@if(isset($_GET['success']))step-2 @else step-1 @endif step">
                                <em class="u-progress-stage-bg"></em>
                                <i class="u-stage-icon-inner">1<em class="bg"></em></i>
                                <p class="stage-name">重置密码</p>
                            </span>
                        <span class="@if(isset($_GET['success']))step-1 @else step-2 @endif step">
                                <em class="u-progress-stage-bg"></em>
                                <i class="u-stage-icon-inner">2<em class="bg"></em></i>
                                <p class="stage-name">完成</p>
                            </span>
                        <span class="u-progress-placeholder"></span>
                    </div>
                    <div class="u-progress-bar total-steps-2">
                        <div class="u-progress-bar-inner"></div>
                    </div>
                </div>
                <form class="am-form am-form-horizontal" action="{{ route('home.safety.modifyChangePassword') }}" method="post">
                    {{ csrf_field() }}
                    <div class="am-form-group">
                        <label for="user-old-password" class="am-form-label">原密码</label>
                        <div class="am-form-content">
                            <input type="password" id="user-old-password" name="oldPassword" placeholder="请输入原登录密码">
                        </div>
                    </div>
                    <div class="am-form-group" style="color:red">
                        <div class="am-form-content" id="oldPasswordErrorMessage">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-new-password" class="am-form-label">新密码</label>
                        <div class="am-form-content">
                            <input type="password" id="user-new-password" name="newPassword" placeholder="由数字、字母组合">
                        </div>
                    </div>
                    <div class="am-form-group" style="color:red">
                        <div class="am-form-content" id="newPasswordErrorMessage">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-confirm-password" class="am-form-label">确认密码</label>
                        <div class="am-form-content">
                            <input type="password" id="user-confirm-password" name="newPassword_confirmation" placeholder="请再次输入上面的密码">
                        </div>
                    </div>
                    <div class="am-form-group" style="color:red">
                        <div class="am-form-content" id="relPasswordErrorMessage">
                        </div>
                    </div>
                    <div class="info-btn">
                        <input type="submit" class="am-btn am-btn-danger" id="submit" value="确认重置">
                    </div>

                </form>

            </div>
            <!--底部-->
            @include('home.public.footer')
        </div>

        @include('home.public.aside')
    </div>
@stop
@section('customJs')
    <script src="{{ asset('/handle/member/validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/member/safety_changePassword.js') }}" type="text/javascript"></script>
@stop