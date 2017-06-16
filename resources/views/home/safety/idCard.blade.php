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
                    <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">实名认证</strong> /
                        <small>Real&nbsp;authentication</small>
                    </div>
                </div>
                <hr>
                <!--进度条-->
                <div class="m-progress">
                    <div class="m-progress-list">
							<span class="@if(\Session::get('userInfo')->id_number) step-2 @else step-1 @endif step">
                                <em class="u-progress-stage-bg"></em>
                                <i class="u-stage-icon-inner">1<em class="bg"></em></i>
                                <p class="stage-name">实名认证</p>
                            </span>
                        <span class=" @if(\Session::get('userInfo')->id_number) step-1 @else step-2 @endif  step">
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
                <form class="am-form am-form-horizontal" method="post" action="{{ url('/home/safety/handleIdCard') }}">
                    {{ csrf_field() }}
                    <div class="am-form-group">
                        <label for="user-name" class="am-form-label">真实姓名</label>
                        <div class="am-form-content">
                            <input type="text" id="user-name" placeholder="请输入您的真实姓名" name="realname" value="{{ \Session::get('userInfo')->realname }}">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label for="user-IDcard" class="am-form-label">身份证号</label>
                        <div class="am-form-content">
                            <input type="tel" id="user-IDcard" placeholder="请输入您的身份证信息" name="id_number" value="{{ \Session::get('userInfo')->id_number }}">
                        </div>
                    </div>
                    <div class="info-btn">
                        <button type="submit" class="am-btn am-btn-danger" id="button">保存</button>
                    </div>
                </form>
                <div class="info-btn">
                    <div id="errorMessage"
                         style="color:red">@if(isset($_GET['message'])) {{ decrypt($_GET['message']) }}  @endif</div>
                </div>

            </div>
            <!--底部-->
            @include('home.public.footer')
        </div>

        @include('home.public.aside')
    </div>
@stop
@section('customJs')
    <script src="{{ asset('/handle/member/validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/member/safety_idCard.js') }}" type="text/javascript"></script>
@stop