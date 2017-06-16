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
                                    添加网站基础配置
                        </header>

                        <div class="panel-body ">

                                <form class="form-horizontal tasi-form config-add"  enctype="multipart/from-data">
                                    {{ csrf_field() }}
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label">网站名称</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="site_name" name="site_name" value="">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label">网站描述</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="site_describe" name="site_describe" value="" >
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label">400电话</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="telephone" name="telephone" value="" >
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label goods_original">网站logo</label>
                                        <div class="col-sm-5">

                                            <div class="row">
                                                <div class="col-md-3">
                                                <div class="thumbnail" style="cursor: pointer;">
                                                    <img src="/admins/img/goods_default.gif" @click="uploadLogoImg">
                                                    <input type="file" style="display: none;">
                                                    <input type="hidden" name="logo" class="logo">

                                                </div>
                                                </div>
                                                <div class="col-md-3">
                                                <button class="btn btn-success" style="margin-top: 15px;" @click="addLogoImg">添加LOGO图片</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label">分类层级</label>
                                        <div class="col-sm-5">
                                            <select id="" name="level_set" class="form-control">
                                                <option value="0">请选择</option>
                                                <option value="1">一级分类</option>
                                                <option value="2">二级分类</option>
                                                <option value="3">三级分类</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label">网站备案号</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="record_number" name="record_number" value="" >
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label">网站地址</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="address" name="address" value="" >
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label">版权信息</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="copyright" name="copyright" value="" >
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-5">
                                            <button type="button" class="btn btn-primary" @click="addConfig">确认添加</button>
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
    <script src="/admins/js/jquery-ui-1.9.2.custom.min.js"></script>
    <!--custom switch-->
    <script src="/admins/js/bootstrap-switch.js"></script>
    <!--custom tagsinput-->
    <script src="/admins/js/jquery.tagsinput.js"></script>
    <!--custom checkbox & radio-->
    <script type="text/javascript" src="/admins/js/ga.js"></script>
    <script type="text/javascript" src="/admins/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="/admins/assets/bootstrap-daterangepicker/date.js"></script>
    <script type="text/javascript" src="/admins/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="/admins/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
    <script type="text/javascript" src="/admins/assets/gritter/js/jquery.gritter.js"></script>
@stop

@section('customJs')
    <!--当前页面 js-->
    <script src="/admins/js/form-component.js"></script>
    <script src="/admins/js/gritter.js" type="text/javascript"></script>
    <script>
        var QINIU_DOMAIN = '{{ env("QINIU_DOMAIN") }}';
    </script>
    <script src="/admins/handle/webconfig/create.js"></script>
    <!-- 页面表单验证 js -->
    <script src="/admins/handle/webconfig/insert_form_validation.js"></script>

@stop