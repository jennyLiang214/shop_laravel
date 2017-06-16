@extends('admin.layouts.master')
@section('content')
    <section id="main-content" class="has-js">
        <section class="wrapper">
            <!-- 绑定属性模态框 Start -->
            <div class="modal fade" id="bindModal" tabindex="-1" role="dialog" aria-labelledby="bindModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="exampleModalLabel">角色权限绑定</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="addName" class="control-label">角色名称:</label>
                                    <span class="form-control">@{{ role.display_name }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="addName" class="control-label">权限:</label>
                                    <div class="checkboxes">
                                        <div class="row" id="labelsC">
                                            <div class="col-md-6" v-for="permission in permissions">
                                                <label class="label_check" :class="{'c_on':permission.checked}">
                                                    <input
                                                            :value="permission.id"
                                                            type="checkbox"/> @{{ permission.display_name }}
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
            <!-- 绑定属性模态框 End -->

            <!-- 修改分类模态框 start -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">修改角色信息</h4>
                        </div>
                        <form id="formCategory" class="validator-form" method="post" onsubmit="return false"
                              @submit.prevent="submit(role.id, $event)">
                            <div class="modal-body">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">角色名称:</label>
                                    <input type="text" class="form-control recipient-name" id="recipient-name"
                                           name="display_name" :value="role.display_name">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="control-label">描述:</label>
                                    <input type="text" class="form-control" id="message-text"
                                           name="description" :value="role.description">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" @click="" class="btn btn-default" data-dismiss="modal">
                                    关闭
                                </button>
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- 修改分类模态框 end -->
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            角色列表
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th> id</th>
                                <th> 角色标识</th>
                                <th> 角色名称</th>
                                <th> 操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data, index) in datas">
                                <td>@{{ data.id }}</td>
                                <td>@{{ data.name }}</td>
                                <td>@{{ data.display_name }}</td>
                                <td>
                                    <button class="btn btn-primary btn-xs"
                                            @click="fetchRoleByIndex(index)"
                                            data-toggle="modal" data-target="#exampleModal"
                                            data-whatever="@getbootstrap">修改信息
                                    </button>
                                    <button class="btn btn-primary btn-xs"
                                            @click="fetchPermissions(index, data.id)"
                                            data-toggle="modal" data-target="#bindModal"
                                            data-whatever="@getbootstrap">查看权限
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <center>@include('common.page')</center>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
@stop

@section('customJs')
    <!-- 当前页面 js -->
    <script src="/admins/handle/acl/index.js"></script>
    <!-- 当前页面表单验证 js -->
    <script src="/admins/handle/acl/index_form_validation.js"></script>
@stop