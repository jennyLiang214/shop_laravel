@extends('admin.layouts.master')
@section('title','用户列表')
@section('content')
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading" style="padding: 20px;">
                            <font><font>
                                    用户详情
                                </font></font>
                            <a href=""><button type="button" style="float: right;" class="btn btn-default">购买记录</button></a>
                            <a href=""></a><button type="button" style="float: right;margin-right:20px" class="btn btn-default">收货地址</button></a>
                            <a href=""><button type="button" style="float: right;margin-right:20px" class="btn btn-primary">基本信息</button></a>
                        </header>
                    </section>
                </div>
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            基本信息
                        </header>
                        <div class="panel-body">
                            <form class="form-horizontal tasi-form" method="get">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">用户昵称</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="disabledInput" type="text" value="{{ $data->nickname or '未填写' }}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">电话号码</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="disabledInput" type="text" value="{{ $data->tel or '未绑定' }}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">电子邮箱</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="disabledInput" type="text" value="{{ $data->email or '未绑定' }}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">真实姓名</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="disabledInput" type="text" value="{{ $data->realname or '未填写' }}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">身份证号</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="disabledInput" type="text" value="{{ $data->id_number or '未填写' }}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="disabledInput" type="text" value="@if($data->sex ==1)男@elseif($data->sex==2)女@else未填写@endif" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">生&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="disabledInput" type="text" value="{{ $data->birthday or  '未填写'}}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;保</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="disabledInput" type="text" value="@if(!empty($data->answer_1) || !empty($data->answer_2)) 已填写 @else 未填写 @endif" readonly="readonly">
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