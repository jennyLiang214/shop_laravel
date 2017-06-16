@extends('admin.layouts.master')

@section('title')
    货品列表
@stop

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            货品列表
                            <small class="pull-right"><a href="/admin/goods">返回</a></small>
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                                <tr>
                                    <th>ID编号</th>
                                    <th>缩略图</th>
                                    <th>货品名称</th>
                                    <th>货品原价</th>
                                    <th>货品折扣价</th>
                                    <th>货品状态</th>
                                    <th>库存</th>
                                    <th>推荐位</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in cargos">
                                    <td>@{{ item.id }}</td>
                                    <td><img :src="'{{ env('QINIU_DOMAIN') }}'+item.cargo_cover+'?imageView2/1/w/60/h/60'" alt="" width="60px"></td>
                                    <td><a :href="'/home/goodsDetail/'+item.id" target="_blank">@{{ item.cargo_name }}</a></td>
                                    <td>@{{ item.cargo_price }}</td>
                                    <td>@{{ item.cargo_discount }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-xs" v-if="item.cargo_status == 1" @click="updateStatus" :data-index="index" :data-cargo_id="item.id" :data-status="item.cargo_status">待售</button>
                                        <button class="btn btn-success btn-xs" v-else-if="item.cargo_status == 2" @click="updateStatus" :data-index="index" :data-cargo_id="item.id" :data-status="item.cargo_status">上架</button>
                                        <button class="btn btn-danger btn-xs" v-else @click="updateStatus" :data-index="index" :data-cargo_id="item.id" :data-status="item.cargo_status">下架</button>
                                    </td>
                                    <td>@{{ item.inventory }}</td>
                                    <td>@{{ recommendStr(item.recommends) }}</td>
                                    <td>
                                        <a href="#myModal-1" data-toggle="modal" :data-cid="item.id" class="btn btn-success btn-xs" title="选择推荐位" @click="getRecommend" :data-index="index"><i class="icon-plus" :data-cid="item.id" :data-index="index"></i></a>
                                        <a href="#myModal-2" data-toggle="modal" :data-cid="item.id" class="btn btn-warning btn-xs" title="做活动" @click="getActivity"><i class="icon-plus" :data-cid="item.id"></i></a>
                                        <a :href="'/admin/cargo/'+item.id+'/edit'" class="btn btn-primary btn-xs"><i class="icon-pencil"></i></a>
                                    </td>
                                </tr>
                                <tr v-if="!isData"><td colspan="10" class="text-center">暂无数据</td></tr>
                            </tbody>
                        </table>
                        <center v-if="isData">@include('common.page')</center>
                        <!-- 选择推荐位 -->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-1" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">
                                            ×
                                        </button>
                                        <h4 class="modal-title">选择推荐位</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="recommend" class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <label for="select-position"
                                                       class="col-lg-2 control-label">选择位置</label>
                                                <div class="col-lg-10">
                                                    <select class="form-control" id="select-position">
                                                        <option value="1">首页</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="select-name"
                                                       class="col-lg-2 control-label">选择名称</label>
                                                <div class="col-lg-10">
                                                    <div class="row">
                                                        <div class="col-lg-3" v-for="item in recommends">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input :checked="inArray(item.id)" type="checkbox" name="recommend_id[]" :value="item.id"> @{{ item.recommend_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <button type="button" class="btn btn-primary" @click="selectRecommend" :data-index="index">确 定</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 做活动 -->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-2" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">
                                            ×
                                        </button>
                                        <h4 class="modal-title">做活动</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="activity" class="form-horizontal" role="form">
                                            <input type="hidden" name="cargo_id" :value="cargo_id">
                                            <div class="form-group">
                                                <label for="select-position"
                                                       class="col-lg-2 control-label">选择活动</label>
                                                <div class="col-lg-10">
                                                    <select name="activity_id" class="form-control" id="select-position">
                                                        <option :value="activity.id" v-for="activity in activitys">@{{ activity.name }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="promotion_price" class="col-lg-2 control-label">促销价</label>
                                                <div class="col-lg-10">
                                                    <input type="text" name="promotion_price" v-model="promotion_price" class="form-control" id="promotion_price" placeholder="促销价">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="number" class="col-lg-2 control-label">数量</label>
                                                <div class="col-lg-10">
                                                    <input type="text" name="number" v-model="number" class="form-control" id="number" placeholder="数量">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <button type="button" class="btn btn-primary" @click="activity">确 定</button>
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
    <script src="{{ asset('admins/assets/jquery-knob/js/jquery.knob.js') }}"></script>
@stop

@section('customJs')
    <script>
        var goods_id = '{{ $id }}';
    </script>
    <script src="{{ asset('admins/handle/cargo/list.js') }}"></script>
    <script src="{{ asset('admins/handle/cargo/list_validation.js') }}"></script>
@stop
