@extends('admin.layouts.master')

@section('title')
    添加商品
@stop

@section('externalCss')
    <link rel="stylesheet" type="text/css" href="/admins/assets/gritter/css/jquery.gritter.css"/>
    <link rel="stylesheet" type="text/css" href="/admins/assets/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css" href="/admins/assets/bootstrap-colorpicker/css/colorpicker.css"/>
    <link rel="stylesheet" type="text/css" href="/admins/assets/bootstrap-daterangepicker/daterangepicker.css"/>
@stop

@section('content')
    @include('vendor.ueditor.assets')
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            商品添加
                        </header>
                        <div class="panel-body">
                            <form id="goods" class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="category" class="col-md-1 control-label">商品分类</label>
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select class="form-control" name="level1" v-model="level1" @change="lv1">
                                                <option :value="level.id"
                                                        v-for="level in lv1s">@{{ level.name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="level2" v-model="level2" @change="lv2">
                                                <option :value="level.id"
                                                        v-for="level in lv2s">@{{ level.name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="level3" v-model="level3" @change="lv3">
                                                <option :value="level.id"
                                                        v-for="level in lv3s">@{{ level.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="goods_title" class="col-md-1 control-label">商品名称</label>
                                    <div class="col-md-11">
                                        <input type="text" name="goods_title" v-model="goods_title" class="form-control" id="goods_title"
                                               placeholder="商品名称">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="goods_label_id" class="col-md-1 control-label">商品规格</label>
                                    <div class="col-md-11">
                                        <div class="checkboxes">
                                            <div class="row" v-if="isGoodsLabels">
                                                <div class="col-md-3" v-for="(obj, index) in goodsLabels">
                                                    <label class="label_check" :for="'checkbox-0'+(index+1)" @click="selectLabel">
                                                        <input name="goods_label[]" :id="'checkbox-0'+(index+1)" :value="obj.id" type="checkbox" />@{{ obj.goods_label_name }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" v-model="goodsLabel"
                                                           placeholder="商品标签名">
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="button" class="btn btn-success" value="添加标签" @click="
                                                    addGoodsLabel">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="goods_original" class="col-md-1 control-label">商品图片</label>
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-xs-2 col-md-2" v-for="val in goodsImgs">
                                                <div class="thumbnail" style="cursor: pointer;">
                                                    <img src="/admins/img/goods_default.gif" @click="uploadGoodsImg">
                                                    <input type="file" style="display: none;">
                                                    <input type="hidden" name="goods_original[]" class="goods_original">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button class="btn btn-success" style="margin-top: 15px;" @click="addGoodsImg">添加商品图片</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="goods_info" class="col-md-1 control-label">商品详情</label>
                                    <div class="col-md-11">
                                        <!-- 编辑器容器 -->
                                        <textarea id="goods_info" name="goods_info" style="width: 95%; height: 300px"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-1 col-md-11">
                                        <button type="button" class="btn btn-danger" id="validateBtn" @click="addGoods">确 定</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
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
    <script src="/admins/handle/goods/create.js"></script>
    <!-- 页面表单验证 js -->
    <script src="/admins/handle/goods/create_validation.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('goods_info');
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@stop