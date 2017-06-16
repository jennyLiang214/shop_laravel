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

                <div class="user-info">
                    <!--标题 -->
                    <div class="am-cf am-padding">
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">个人资料</strong> /
                            <small>Personal&nbsp;information</small>
                        </div>
                    </div>
                    <hr/>
                    @if(!empty($userInfo))

                    @include('home.public.personalHeader')
                    <!--个人信息 -->
                    <div class="info-main">
                        <form class="am-form am-form-horizontal" action="{{ route('home.userInfo.updateMessage')}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" id="birthday" value="@if(!empty($userInfo->birthday)){{ $userInfo->birthday }}@else 1 @endif">
                            <div class="am-form-group">
                                <label for="user-name2" class="am-form-label">昵称</label>
                                <div class="am-form-content">
                                    <input type="text" id="user-name2" name="nickname" placeholder="用户昵称" @if(!empty($userInfo->nickname)) value="{{ $userInfo->nickname }}" @endif>

                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="user-name" class="am-form-label">姓名</label>
                                <div class="am-form-content">
                                    <input type="text" id="user-name2" name="realname" placeholder="真实姓名"  value="{{ $userInfo->realname }}" readonly="readonly">

                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-form-label">性别</label>
                                <div class="am-form-content sex">

                                    <label class="am-radio-inline">
                                        <input type="radio" name="sex" value="1" data-am-ucheck @if($userInfo->sex == 1) checked="checked" @endif> 男
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="sex" value="2"  data-am-ucheck @if($userInfo->sex == 2) checked="checked" @endif> 女
                                    </label>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="user-birth" class="am-form-label">生日</label>
                                <div class="am-form-content birth">
                                    <div class="birth-select">
                                        <select id="year" name="year"></select>
                                        <em>年</em>
                                    </div>
                                    <div class="birth-select2">
                                        <select id="month" name="month"></select>
                                        <em>月</em></div>
                                    <div class="birth-select2">
                                        <select id="day" name="day">
                                        </select>
                                        <em>日</em></div>
                                </div>

                            </div>
                            <div class="info-main"> <div class="info-btn"  id="dayErrorMessage" style="color:red;font-size: 12px">
                                </div></div>
                            <div class="am-form-group">
                                <label for="user-phone" class="am-form-label">电话</label>
                                <div class="am-form-content">
                                    <input id="user-phone" placeholder="telephonenumber" type="tel" readonly="readonly" @if(!empty($userInfo->tel)) value="{{ $userInfo->tel }}" @endif>

                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="user-email" class="am-form-label">电子邮件</label>
                                <div class="am-form-content">
                                    <input id="user-email" placeholder="Email" type="email" readonly="readonly" @if(!empty($userInfo->email)) value="{{ $userInfo->email }}" @endif>

                                </div>
                            </div>
                            <div class="info-btn">
                                <input type="submit" class="am-btn am-btn-danger" value="确认更新">
                            </div>

                        </form>
                    </div>
                    @else
                            <div class="info-main" style="margin-top:200px">

                                    <div class="info-btn">
                                        <div class="am-btn am-btn-danger">获取数据失败!!</div>
                                    </div>

                            </div>

                    @endif

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
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var token= "{{ csrf_token() }}";
        var imgUrl = "{{ env('QINIU_DOMAIN') }}";
    </script>
    <script src="{{ asset('/handle/member/uploadAvatar.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/member/userInfo_information.js') }}" type="text/javascript"></script>
@stop