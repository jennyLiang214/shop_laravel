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
                                    用户列表
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
                                            <option value="1"><font><font>手机号码</font></font></option>
                                            <option value="2"><font><font>电子邮箱</font></font></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" v-model="search.value">
                                    </div>
                                    <button type="submit" @click.prevent="searchLists" class="btn btn-info"><font><font>搜索</font></font></button>
                                </div>
                            </form>
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th>用户昵称</th>
                                <th>电话号码</th>
                                <th>电子邮箱</th>
                                <th>注册方式</th>
                                <th>注册IP</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data,index) in datas">
                                <td><font><font>@{{ data.message.nickname }}</font></font></td>
                                <td v-if="data.message.tel"><font><font>@{{ data.message.tel }}</font></font></td>
                                <td v-else><font><font>未绑定</font></font></td>
                                <td v-if="data.message.email"><font><font>@{{ data.message.email }}</font></font></td>
                                <td v-else><font><font>未绑定</font></font></td>
                                <td v-if="data.tel"><font><font>手机</font></font></td>
                                <td v-else-if="data.email"><font><font>邮箱</font></font></td>
                                <td v-else><font><font>第三方</font></font></td>
                                <td><font><font>@{{ data.register_ip }}</font></font></td>
                                <td><font><font>@{{ data.created_at }}</font></font></td>
                                <td>
                                    <button  class="btn btn-success btn-xs" @click="showDetails(data.id)">详细信息</button>
                                    <button  class="btn btn-warning btn-xs" data-toggle="modal" data-target="#updatePassword" data-whatever="@getbootstrap" @click="getUserId(data.id)" >重置密码</button>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <center>@include('common.page')</center>
                    </section>
                </div>
                <!-- update admin password form  start -->
                <div class="modal fade updateUserPassword" id="updatePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="exampleModalLabel">重置密码</h4>
                            </div>
                            <div class="modal-body">
                                <form class="userForm">
                                    <div class="form-group">
                                        <label for="message-text" class="control-label">密码:</label>
                                        <input type="password" class="form-control"  name="password"  v-model="data.password">
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="control-label">确认密码:</label>
                                        <input type="password" class="form-control"  name="rel_password"  v-model="data.rel_password">
                                    </div>
                                    <div class="modal-footer" style="margin-top: 45px;">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                        <button type="button"  @click="userUpdate()" class="btn btn-primary">提交</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- update admin password form  end -->
            </div>
        </section>
    </section>
@stop
@section('customJs')
    {{--<!-- 定义token -->--}}
    <script> var token = "{{ csrf_token() }}" </script>
    {{--<!-- 获取列表页js -->--}}
    <script src="{{ asset('admins/handle/subscribers/index.js') }}"></script>
    {{--<!-- 重置管理员信息表单 -->--}}
    <script src="{{ asset('admins/handle/subscribers/update.js') }}"></script>
    {{--<!-- 页面表单验证 js -->--}}
    <script src="{{ asset('admins/handle/subscribers/insert_form_validation.js') }}"></script>
@stop