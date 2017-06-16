@extends('admin.layouts.master')

@section('title')
    修改商品
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
                            修改商品
                            <small class="pull-right"><a href="/admin/goods">返回</a></small>
                        </header>
                        <div class="panel-body">
                            <form id="goods" class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="category" class="col-md-1 control-label">商品分类</label>
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-4" id="level1">
                                                <select class="form-control" name="level1" @change="lv1">
                                                    <option value="-1">请选择</option>
                                                    <template v-for="level in lv1s">
                                                        <template v-if="goodsCategory.length != 0">
                                                            <option :value="level.id" v-if="level.id == goodsCategory[0].id" selected>@{{ level.name }}</option>
                                                            <option :value="level.id" v-else>@{{ level.name }}</option>
                                                        </template>
                                                        <template v-else>
                                                            <option :value="level.id">@{{ level.name }}</option>
                                                        </template>
                                                    </template>
                                                </select>
                                            </div>
                                            <div class="col-md-4" id="level2">
                                                <select class="form-control" name="level2" @change="lv2">
                                                    <option value="-1">请选择</option>
                                                    <template v-for="level in lv2s">
                                                        <template v-if="goodsCategory.length != 0">
                                                            <option :value="level.id" v-if="level.id == goodsCategory[1].id" selected>@{{ level.name }}</option>
                                                            <option :value="level.id" v-else>@{{ level.name }}</option>
                                                        </template>
                                                        <template v-else>
                                                            <option :value="level.id">@{{ level.name }}</option>
                                                        </template>
                                                    </template>
                                                </select>
                                            </div>
                                            <div class="col-md-4" id="level3">
                                                <select class="form-control" name="level3" @change="lv3">
                                                    <option value="-1">请选择</option>
                                                    <template v-for="level in lv3s">
                                                        <template v-if="goodsCategory.length != 0">
                                                            <option :value="level.id" v-if="level.id == goodsCategory[2].id" selected>@{{ level.name }}</option>
                                                            <option :value="level.id" v-else>@{{ level.name }}</option>
                                                        </template>
                                                        <template v-else>
                                                            <option :value="level.id">@{{ level.name }}</option>
                                                        </template>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="goods_title" class="col-md-1 control-label">商品名称</label>
                                    <div class="col-md-11">
                                        <input type="text" name="goods_title" class="form-control" id="goods_title"
                                               placeholder="商品名称" :value="goods.goods_title">
                                    </div>
                                </div>
                                <div class="form-group" v-if="!cargo">
                                    <label for="goods_label_id" class="col-md-1 control-label">商品规格</label>
                                    <div class="col-md-11">
                                        <div class="checkboxes">
                                            <div class="row" v-if="isGoodsLabels">
                                                <div class="col-md-3" v-for="(obj, index) in goodsLabels">
                                                    <template v-if="inArray(obj.id, goodsLabelIds)">
                                                        <label class="label_check c_on" @click="selectLabel">
                                                            <input name="goods_label[]" :value="obj.id" type="checkbox" checked />@{{ obj.goods_label_name }}
                                                        </label>
                                                    </template>

                                                    <template v-else>
                                                        <label class="label_check c_off" @click="selectLabel">
                                                            <input name="goods_label[]" :value="obj.id" type="checkbox" />@{{ obj.goods_label_name }}
                                                        </label>
                                                    </template>
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
                                            <div class="col-xs-2 col-md-2" v-for="item in goodsImgs">
                                                <div class="thumbnail" style="cursor: pointer;">
                                                    <img v-if="item.indexOf('images') != -1" :src="'{{ env('QINIU_DOMAIN') }}'+item" @click="uploadGoodsImg">
                                                    <img src="/admins/img/goods_default.gif" @click="uploadGoodsImg" v-else>
                                                    <input type="file" style="display: none;">
                                                    <input type="hidden" name="goods_original[]" class="goods_original" :value="item">
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
                                        <textarea id="goods_info" name="goods_info" style="width: 95%; height: 300px">{!! $goods->goods_info !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-1 col-md-11">
                                        <button type="button" class="btn btn-danger" id="validateBtn" @click="updateGoods">确 定</button>
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
        var goods_id = '{{ $goods->id }}';
    </script>
    <script src="/admins/handle/goods/edit.js"></script>
    <!-- 页面表单验证 js -->
    <script src="/admins/handle/goods/edit_validation.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('goods_info');
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@stop