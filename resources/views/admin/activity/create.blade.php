@extends('admin.layouts.master')

@section('title')
    添加活动
@stop

@section('externalCss')
    <link rel="stylesheet" type="text/css" href="/admins/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
@stop

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            活动添加
                        </header>
                        <div class="panel-body">
                            <form id="activity" class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="name" class="col-lg-1 control-label">活动名称</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="name" v-model="name" class="form-control" id="name" placeholder="活动名称">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="desc" class="col-lg-1 control-label">活动描述</label>
                                    <div class="col-lg-11">
                                        <textarea name="desc" v-model="desc" class="form-control" id="desc"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-1">活动类型</label>
                                    <div class="col-lg-11">
                                        <label class="radio-inline">
                                            <input type="radio" name="type" v-model="type" value="1"> 秒杀
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="type" v-model="type" value="2"> 特惠
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="type" v-model="type" value="3"> 团购
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="type" v-model="type" value="4"> 超值
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="start_timestamp" class="col-lg-1 control-label">开始时间</label>
                                    <div class="input-group date form_datetime col-lg-5" data-date="{{ date('Y-m-d H:i:s') }}" data-date-format="yyyy-mm-dd hh:ii" data-link-field="start_timestamp">
                                        <input class="form-control" name="start_timestamp" size="16" type="text" value="" readonly>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <input type="hidden" id="dtp_input1" value="" /><br/>
                                </div>
                                <div class="form-group">
                                    <label for="length" class="col-lg-1 control-label">活动时长</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="length" v-model="length" class="form-control" id="length" placeholder="活动时长">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-1 col-lg-11">
                                        <button type="button" class="btn btn-danger" @click="addActivity">添 加</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>

                </div>
            </div>
        </section>
    </section>
@stop

@section('externalJs')
    <script type="text/javascript" src="/admins/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="/admins/assets/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
@stop

@section('customJs')
    <!--当前页面 js-->
    <script src="/admins/handle/activity/create.js"></script>
    <!-- 页面表单验证 js -->
    <script src="/admins/handle/activity/create_validation.js"></script>
    <script>
        // 日期时间选择器
        $('.form_datetime').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            startDate: '{{ $activity ? date("Y-m-d H:i:s", $activity->end_timestamp) : date("Y-m-d H:i:s") }}',
        });
    </script>
@stop