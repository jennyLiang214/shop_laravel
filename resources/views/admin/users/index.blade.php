@extends('admin.layouts.master')
@section('title','管理员列表')
@section('content')
    <section id="main-content" class="has-js">
        <section class="wrapper">
            <!-- 绑定角色模态框模态框 Start -->
            <div class="modal fade" id="bindModal" tabindex="-1" role="dialog" aria-labelledby="bindModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="exampleModalLabel">授权职务</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="addName" class="control-label">用户名称:</label>
                                    <span class="form-control">@{{ user.nickname }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="addName" class="control-label">角色:</label>
                                    <div class="radios">
                                        <div class="row" id="labelsC">
                                            <div class="col-md-6"  v-for="role in roles">
                                                <label class="label_radio" :class="{'r_on':role.checked}">
                                                    <input
                                                            :value="role.id"
                                                            type="radio"/> @{{ role.display_name }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" @click="doneBind()" class="btn btn-primary">
                                    完成
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- 绑定角色模态框 End -->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading" style="padding: 20px;">
                            <font><font>
                                    管理员列表
                                </font></font>
                            <button type="button" style="float: right" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal" data-whatever="@getbootstrap">添加管理员
                            </button>
                        </header>
                    </section>
                </div>
                <div class="col-lg-12">
                    <section class="panel " id="userList">
                        <header class="panel-heading" style="padding: 15px;">
                            <form class="form-horizontal tasi-form">
                                <div class="form-group">
                                    <div class="col-lg-2">
                                        <select class="form-control m-bot15" v-model="search.type">
                                            <option value="0"><font><font>搜索类型</font></font></option>
                                            <option value="1"><font><font>用户名</font></font></option>
                                            <option value="2"><font><font>手机号</font></font></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" v-model="search.value">
                                    </div>
                                    <button type="submit" @click.prevent="searchLists" class="btn btn-info"><font><font>搜索</font></font>
                                    </button>
                                </div>
                            </form>
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th>管理员名称</th>
                                <th>联系方式</th>
                                <th>最后登录时间</th>
                                <th>最后登录IP</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data,index) in datas">
                                <td><font><font>@{{ data.nickname }}</font></font></td>
                                <td><font><font>@{{ data.tel }}</font></font></td>
                                <td v-if="data.last_login_at == data.created_at"><font><font>刚刚创建 </font></font></td>
                                <td v-else><font><font>@{{ data.last_login_at }} </font></font></td>
                                <td v-if="data.last_login_ip"><font><font> @{{ data.last_login_ip }}</font></font></td>
                                <td v-else><font><font> 尚未登录 </font></font></td>

                                <td>
                                    <button @click="getAdminId(data.id)" class="btn btn-warning btn-xs"
                                            data-toggle="modal" data-target="#updatePassword"
                                            data-whatever="@getbootstrap">重置密码
                                    </button>
                                    <button @click="deleteAdmin(data.id,index)" class="btn btn-danger btn-xs">删除
                                    </button>
                                    <button class="btn btn-primary btn-xs"
                                            @click="fetchRoles(index, data.id)"
                                            data-toggle="modal" data-target="#bindModal"
                                            data-whatever="@getbootstrap">授权职务
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <center>@include('common.page')</center>
                    </section>
                    <!-- create admin form  start -->
                    <div class="modal fade createAdminUser" id="exampleModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="exampleModalLabel">添加管理员</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="userForm" onsubmit="return false;" @submit.prevent="submit($event)">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label">用户名:</label>
                                            <input type="text" class="form-control" name="nickname">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">手机号码:</label>
                                            <input type="text" class="form-control" name="tel">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">密码:</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">确认密码:</label>
                                            <input type="password" class="form-control" name="rel_password">
                                        </div>
                                        <div class="modal-footer" style="margin-top: 45px;">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                                            </button>
                                            <button type="submit" class="btn btn-primary">提交</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- create admin form  end -->
                    <!-- update admin password form  start -->
                    <div class="modal fade updateAdminUser" id="updatePassword" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="exampleModalLabel">重置密码</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="userForm">
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">密码:</label>
                                            <input type="password" class="form-control" name="password"
                                                   v-model="data.password">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">确认密码:</label>
                                            <input type="password" class="form-control" name="rel_password"
                                                   v-model="data.rel_password">
                                        </div>
                                        <div class="modal-footer" style="margin-top: 45px;">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                                            </button>
                                            <button type="button" @click="userUpdate()" class="btn btn-primary">提交
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- update admin password form  end -->
                </div>
            </div>
        </section>
    </section>
@stop
@section('customJs')
    <!-- 定义token -->
    <script> var token = "{{ csrf_token() }}" </script>
    <!-- 获取列表页js -->
    <script src="{{ asset('admins/handle/users/index.js') }}"></script>
    <!-- 创建管理员表单 -->
    <script src="{{ asset('admins/handle/users/insert.js') }}"></script>
    <!-- 重置管理员信息表单 -->
    <script src="{{ asset('admins/handle/users/update.js') }}"></script>
    <!-- 页面表单验证 js -->
    <script src="{{ asset('admins/handle/users/insert_form_validation.js') }}"></script>
@stop