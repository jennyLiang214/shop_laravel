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
                @if(!empty(\Session::get('userInfo')->email))
                    <div class="am-cf am-padding">
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">重新绑定</strong> /
                            <small>Bind&nbsp;Email</small>
                        </div>
                    </div>
                    <hr/>
                    <!--进度条-->
                    <div class="m-progress">
                        <div class="m-progress-list">
							<span class="step-1 step" id="confirm-1">
                                <em class="u-progress-stage-bg"></em>
                                <i class="u-stage-icon-inner">1<em class="bg"></em></i>
                                <p class="stage-name">绑定邮箱</p>
                            </span>
                            <span class="step-2 step " id="confirm-2">
                                <em class="u-progress-stage-bg"></em>
                                <i class="u-stage-icon-inner">2<em class="bg"></em></i>
                                <p class="stage-name">重新绑定</p>
                            </span>
                            <span class="step-2 step" id="confirm-3">
                                <em class="u-progress-stage-bg"></em>
                                <i class="u-stage-icon-inner">3<em class="bg"></em></i>
                                <p class="stage-name">完成</p>
                            </span>
                            <span class="u-progress-placeholder"></span>
                        </div>
                        <div class="u-progress-bar total-steps-2">
                            <div class="u-progress-bar-inner"></div>
                        </div>
                    </div>
                    <form class="am-form am-form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                        <!-- 更换电子邮箱 开始-->
                        <!-- 第一步获取原始电子邮箱验证码  开始-->
                        <div id="Step-One">
                            <div class="am-form-group bind">
                                <label for="user-phone" class="am-form-label">验证邮箱</label>
                                <div class="am-form-content">
                                    <span id="user-phone">{{ substr_replace(\Session::get('userInfo')->email,'****',3,4) }}</span>
                                </div>
                            </div>
                            <div class="am-form-group code" style="width:60%">
                                <label for="user-code" class="am-form-label">验证码</label>
                                <div class="am-form-content" style="width:45%">
                                    <input type="tel" id="user-code" placeholder="邮箱验证码">
                                </div>
                                <a class="btn" href="javascript:void(0);" id="sendMobileCode" style="padding:0px">
                                    <button class="am-btn am-btn-danger" type="button" id="codeTime"
                                            style="width:100%;height:100%">获取验证码
                                    </button>
                                </a>
                            </div>
                            <div class="info-btn" style="width:70%">
                                <div class="am-btn am-btn-danger nextStep">下一步</div>
                            </div>
                        </div>
                        <!-- 获取原始电子邮箱验证码 结束 -->
                        <!-- 第二步 更换电子邮箱 开始-->
                        <div id="Step-two" style="display:none">
                            <div class="am-form-group ">
                                <label for="user-new-phone" class="am-form-label">验证手机</label>
                                <div class="am-form-content">
                                    <input type="tel" id="user-new-phone" placeholder="绑定新电子邮箱">
                                </div>
                            </div>
                            <div class="am-form-group code" style="width:60%;">
                                <label for="user-new-code" class="am-form-label">验证码</label>
                                <div class="am-form-content" style="width:45%" id="bindCode">
                                    <input type="tel" id="user-new-code" placeholder="邮箱验证码">
                                </div>
                                <a class="btn" href="javascript:void(0);" style="padding:0px">
                                    <button class="am-btn am-btn-danger" type="button" id="secondSend"
                                            style="width:100%;height:100%">获取验证码
                                    </button>
                                </a>
                            </div>
                            <div class="info-btn" style="width:70%">
                                <div class="am-btn am-btn-danger" id="bingTel">提交</div>
                            </div>
                        </div>

                        <!--第二步更换电子邮箱 结束-->
                        <!-- 更换电子邮箱 结束 -->
                    </form>
                    <div class="info-btn">
                        <div id="errorMessage" style="width:70%;color:red"></div>
                    </div>
                @else
                    <div class="am-cf am-padding">
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">绑定邮箱</strong> /
                            <small>Bind&nbsp;Email</small>
                        </div>
                    </div>
                    <hr/>
                    <!--进度条-->
                    <div class="m-progress">
                        <div class="m-progress-list">
							<span class="step-1 step" id="confirm-1">
                                <em class="u-progress-stage-bg"></em>
                                <i class="u-stage-icon-inner">1<em class="bg"></em></i>
                                <p class="stage-name">绑定邮箱</p>
                            </span>
                            <span class="step-2 step" id="confirm-3">
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
                    <form class="am-form am-form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                        <div id="Step-two" style="display:block">
                            <div class="am-form-group code" style="width:70%;">
                                <label for="user-new-code" class="am-form-label">绑定邮箱</label>
                                <div class="am-form-content" style="width:55%" id="bindCode">
                                    <input type="tel" id="user-new-email" placeholder="电子邮箱">
                                </div>
                                <a class="btn" href="javascript:void(0);" style="padding:0px">
                                    <button class="am-btn am-btn-danger" type="button" id="sendEmail"
                                            style="width:100%;height:100%">点击绑定
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="info-btn">
                        <div id="errorMessage"
                             style="width:70%;color:red">@if(isset($_GET['message'])) {{ decrypt($_GET['message']) }} @endif</div>
                    </div>
                @endif

            </div>
            <!--底部-->
            @include('home.public.footer')
        </div>

        @include('home.public.aside')
    </div>
@stop
@section('customJs')
    <script src="{{ asset('/handle/member/validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/function.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var token= "{{ csrf_token() }}";
        var wait = 60;
    </script>

    @if(!empty(\Session::get('userInfo')->email))
        <!-- 更换电子邮箱 -->
        <script src="{{ asset('/handle/member/safety_changeEmail.js') }}" type="text/javascript"></script>
    @else
        <!-- 绑定电子邮箱 -->
        <script src="{{ asset('/handle/member/safety_bingEmail.js') }}" type="text/javascript"></script>
    @endif
@stop