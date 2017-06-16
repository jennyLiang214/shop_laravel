@extends('admin.layouts.master')

@section('title')
    商品列表
@stop

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            商品列表
                        </header>
                        <div class="panel-body">
                            <form class="form-inline" role="form" @submit.prevent="searchList">
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail2">Email address</label>
                                    <input type="text" name="goods_title" class="form-control" id="exampleInputEmail2" placeholder="商品名称">
                                </div>
                                <button type="submit" class="btn btn-success">搜索</button>
                            </form>
                        </div>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                                <tr>
                                    <th>ID编号</th>
                                    <th>缩略图</th>
                                    <th>商品名称</th>
                                    <th>商品状态</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in goods">
                                    <td>@{{ item.id }}</td>
                                    <td><img :src="'{{ env('QINIU_DOMAIN') }}'+JSON.parse(item.goods_original)[0]+'?imageView2/1/w/60/h/60'" alt="" width="60px"></td>
                                    <td><a :href="'/admin/cargoList/'+item.id">@{{ item.goods_title }}</a></td>
                                    <td>
                                        <button class="btn btn-primary btn-xs" v-if="item.goods_status == 1" @click="updateStatus" :data-index="index" :data-goods_id="item.id" :data-status="item.goods_status">待售</button>
                                        <button class="btn btn-success btn-xs" v-else-if="item.goods_status == 2" @click="updateStatus" :data-index="index" :data-goods_id="item.id" :data-status="item.goods_status">上架</button>
                                        <button class="btn btn-danger btn-xs" v-else @click="updateStatus" :data-index="index" :data-goods_id="item.id" :data-status="item.goods_status">下架</button>
                                    </td>
                                    <td>
                                        <a :href="'/admin/addCargo/'+item.id" class="btn btn-success btn-xs"><i class="icon-plus"></i></a>
                                        <a :href="'/admin/goods/'+item.id+'/edit'" class="btn btn-primary btn-xs"><i class="icon-pencil"></i></a>
                                    </td>
                                </tr>
                                <tr v-if="!isData"><td colspan="5" class="text-center">暂无数据</td></tr>
                            </tbody>
                        </table>
                        <center v-if="isData">@include('common.page')</center>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
@stop

@section('customJs')
    <!-- 当前页面 js -->
    <script src="{{ asset('admins/handle/goods/index.js') }}"></script>
@stop
