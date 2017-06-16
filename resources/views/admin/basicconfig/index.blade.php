@extends('admin.layouts.master')

@section('title')
    网站基础配置
@stop

@section('externalCss')
    <link href="/admins/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/admins/css/owl.carousel.css" type="text/css">
@stop

@section('content')

    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading" style="padding: 15px;">
                                    网站基础配置
                            @if(!empty($data))
                                <button type="button" style="float: right" class="btn btn-primary" ><a style="color:white" href="{{ url('/admin/basicconfig') }}/{{ $data->id }}/edit">修改网站配置</a></button>
                            @else
                                <button type="button" style="float: right" class="btn btn-primary" ><a style="color:white" href="{{ url('/admin/basicconfig/create') }}">添加网站配置</a></button>
                            @endif

                        </header>

                        <div class="panel-body config-add">
                            @if(!empty($data))
                                <form class="form-horizontal tasi-form"  id="webconfigForUpdate" method="post" action="{{ route('basicconfig.store') }}" @submit.prevent="submit($event)">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">网站名称</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="site_name" name="site_name" value="{{$data->site_name}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">网站描述</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="site_describe" name="site_describe" value="{{$data->site_describe}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">400电话</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="telephone" name="telephone" value="{{$data->telephone}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">网站logo</label>
                                        <div class="col-sm-1">
                                            {{--<input type="text" class="form-control" id="logo" name="logo" value="{{$data->logo}}" disabled>--}}
                                            <div class="thumbnail" style="cursor: pointer;">
                                                <img src="@if(empty($data->logo)) /admins/img/goods_default.gif @else {{ env('QINIU_DOMAIN') }}{{$data->logo}} @endif">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">分类层级</label>
                                        <div class="col-sm-10">
                                            {{--<input type="text" class="form-control" id="level_set" name="level_set" value="{{$data->level_set}}" disabled>--}}
                                            <select id="" name="level_set" class="form-control" disabled>
                                                <option value="0">请选择</option>
                                                @if ($data->level_set == 1)
                                                <option value="1" selected>一级分类</option>
                                                @elseif ($data->level_set == 2)
                                                <option value="2" selected>二级分类</option>
                                                @else
                                                <option value="3" selected>三级分类</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">网站备案号</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="record_number" name="record_number" value="{{$data->record_number}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">网站地址</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="address" name="address" value="{{$data->address}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">版权信息</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="copyright" name="copyright" value="{{$data->copyright}}" disabled>
                                        </div>
                                    </div>

                                </form>
                            @endif

                        </div>
                    </section>
                </div>
            </div>

        </section>

    </section>
@stop

@section('customJs')
    <script>
        var QINIU_DOMAIN = '{{ env("QINIU_DOMAIN") }}';
    </script>

@stop