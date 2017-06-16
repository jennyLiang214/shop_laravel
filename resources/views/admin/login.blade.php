@extends('admin.layouts.master')
@section('title')
    后台登陆
@stop
@section('header') @stop
@section('sidebar') @stop
@section('content')
    <div class="container">
        <form class="form-signin" action="{{ route('user.store') }}" id="profileForm" method="post">
            <h2 class="form-signin-heading">管理员登录</h2>
            {{ csrf_field() }}
            <div class="login-wrap">
                <!-- Errors Messages -->
                @include('notice.error')
                <div class="form-group">
                    <input type="text" value="{{ old('tel') }}" class="form-control" name="tel" placeholder="手机号码" autofocus>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="密码">
                </div>
                <div class="form-line form-group" style="position: relative">
                    <input type="text" name="captcha" class="form-control pull-left" placeholder="验证码">
                    <img id="captcha" style="position: absolute;right: 1px;z-index: 999" src="{{ captcha_src() }}" class="pull-right" data-captcha-config="default">
                </div>
                <button class="btn btn-lg btn-login btn-block" type="submit">登陆</button>
            </div>
        </form>
    </div>
@stop
@section('customJs')
    <script>
        // 切换图片
        $('#captcha').on('click', function () {
            var captcha = $(this);
            var url = '/captcha/' + captcha.data('captcha-config') + '?' + Math.random();
            captcha.attr('src', url);
        });
        $('#profileForm').bootstrapValidator({
            fields: {
                tel: {
                    validators: {
                        notEmpty: {
                            message: '手机号码不能为空!'
                        },
                        stringLength: {
                            min: 11,
                            max: 11,
                            message: '请输入正确的手机号码!'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '密码不能为空!'
                        }
                    }
                },
                captcha: {
                    validators: {
                        notEmpty: {
                            message: '验证码不能为空!'
                        }
                    }
                }
            }
        });
    </script>
@stop