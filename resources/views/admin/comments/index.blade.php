@extends('admin.layouts.master')
@section('title','评论管理')
@section('content')
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading" style="padding: 20px;">
                            <font><font>
                                    评论列表
                                </font></font>
                        </header>
                    </section>
                </div>
                <div class="col-lg-12">
                    <section class="panel subscriberList">
                        <header class="panel-heading" style="padding: 15px;">
                            <form class="form-horizontal tasi-form">
                                <div class="form-group">

                                    <div class="col-lg-2">
                                        <select class="form-control m-bot15" v-model="search.type">
                                            <option value="0"><font><font>全部</font></font></option>
                                            <option value="1"><font><font>好评</font></font></option>
                                            <option value="2"><font><font>中评</font></font></option>
                                            <option value="3"><font><font>差评</font></font></option>
                                        </select>
                                    </div>
                                    <button type="submit" @click.prevent="searchLists()" class="btn btn-info"><font><font>搜索</font></font></button>
                                </div>
                            </form>
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th>评价用户</th>
                                <th>关联商品</th>
                                <th>商品评价</th>
                                <th>评价时间</th>
                                <th>评论内容</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data,index) in datas">
                                <td width="15%"><font><font>@{{ data.user_message.nickname }}</font></font></td>
                                <td width="40%"><font><font>@{{ data.cargo_message.cargo_name }}</font></font></td>
                                <td width="8%">
                                    <font v-if="data.star ==1"> <font>好评</font></font>
                                    <font v-else-if="data.star ==2"> <font>中评</font></font>
                                    <font v-else="data.star ==3"> <font>差评</font></font>
                                </td>
                                <td width="15%"><font><font>@{{ data.created_at }}</font></font></td>
                                <td width="15%">
                                    <button  class="btn btn-success btn-xs"  data-toggle="modal" @click="showMessage(data.comment_info)">点击查看</button>
                                </td>
                                <td>
                                    <button  class="btn btn-danger btn-xs"  data-toggle="modal" @click="delComment(data.id,index)">删除</button>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                        <center>@include('common.page')</center>
                    </section>
                </div>
            </div>
        </section>
    </section>
@stop
@section('customJs')
    <script> var token = "{{ csrf_token() }}" </script>
    <script src="{{ asset('/admins/handle/comment/index.js') }}"></script>
@stop