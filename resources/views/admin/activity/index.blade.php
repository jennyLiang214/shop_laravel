@extends('admin.layouts.master')

@section('title')
    活动列表
@stop

@section('externalCss')
    <link rel="stylesheet" type="text/css" href="/admins/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
@stop

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            活动列表
                        </header>
                        <div class="panel-body">
                            <form class="form-inline" role="form" @submit.prevent="searchList">
                                <div class="form-group">
                                    <label class="sr-only" for="name">Email address</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="活动名称">
                                </div>
                                <button type="submit" class="btn btn-success">搜索</button>
                            </form>
                        </div>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                                <tr>
                                    <th>ID编号</th>
                                    <th>活动名称</th>
                                    <th>类型</th>
                                    <th>活动时长</th>
                                    <th>开始时间</th>
                                    <th>结束时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in activitys">
                                    <td>@{{ item.id }}</td>
                                    <td>@{{ item.name }}</td>
                                    <td>
                                        <template v-if="item.type == 1">秒杀</template>
                                        <template v-if="item.type == 2">特惠</template>
                                        <template v-if="item.type == 3">团购</template>
                                        <template v-if="item.type == 4">超值</template>
                                    </td>
                                    <td>@{{ item.length }}</td>
                                    <td>@{{ timeConvert(item.start_timestamp) }}</td>
                                    <td>@{{ timeConvert(item.end_timestamp) }}</td>
                                    <td>
                                        <a href="#myModal-1" :data-aid="item.id" :data-index="index" data-toggle="modal" class="btn btn-xs btn-primary" @click="modActivity">
                                            <i class="icon-pencil" :data-aid="item.id" :data-index="index"></i>
                                        </a>
                                        <button class="btn btn-danger btn-xs" @click="deleteActivity" :data-aid="item.id" :data-index="index"><i class="icon-trash" :data-aid="item.id" :data-index="index"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="!isData"><td colspan="7" class="text-center">暂无数据</td></tr>
                            </tbody>
                        </table>
                        <center v-if="isData">@include('common.page')</center>

                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1"
                             id="myModal-1" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">
                                            ×
                                        </button>
                                        <h4 class="modal-title">修改活动</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="activity" class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <label for="aname" class="col-lg-2 control-label">活动名称</label>
                                                <div class="col-lg-10">
                                                    <input type="text" name="name" :value="activity.name" class="form-control" id="aname" placeholder="活动名称">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="desc" class="col-lg-2 control-label">活动描述</label>
                                                <div class="col-lg-10">
                                                    <textarea name="desc" :value="activity.desc" class="form-control" id="desc"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-lg-2">活动类型</label>
                                                <div class="col-lg-10">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="type" :checked="activity.type == 1" value="1"> 秒杀
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="type" :checked="activity.type == 2" value="2"> 特惠
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="type" :checked="activity.type == 3" value="3"> 团购
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="type" :checked="activity.type == 4" value="4"> 超值
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="start_timestamp" class="col-lg-2 control-label">开始时间</label>
                                                <div class="input-group date form_datetime col-lg-10" data-date="{{ date('Y-m-d H:i:s') }}" data-date-format="yyyy-mm-dd hh:ii" data-link-field="start_timestamp">
                                                    <input class="form-control" name="start_timestamp" :value="timeConvert(activity.start_timestamp, false)" size="16" type="text" value="" readonly>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <input type="hidden" id="dtp_input1" value="" /><br/>
                                            </div>
                                            <div class="form-group">
                                                <label for="length" class="col-lg-2 control-label">活动时长</label>
                                                <div class="col-lg-10">
                                                    <input type="text" name="length" :value="activity.length" class="form-control" id="length" placeholder="活动时长">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <button type="button" id="updateActivity" class="btn btn-danger" :data-aid="activity.id" @click="update">修 改</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
@stop

@section('externalJs')
    <script type="text/javascript" src="/admins/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="/admins/assets/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
@stop

@section('customJs')
    <!-- 当前页面 js -->
    <script src="{{ asset('admins/handle/activity/index.js') }}"></script>
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
        });
    </script>
@stop
