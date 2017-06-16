@extends('admin.layouts.master')
@section('title','友情链接列表')
@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading" style="padding: 20px;">
                            <font><font>
                                    友情链接列表
                                </font></font>
                            <button type="button" style="float: right" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">添加友情链接</button>
                        </header>
                    </section>
                </div>
                <div class="col-lg-12">
                    <section class="panel linkList" id="link_list">
                        <header class="panel-heading" style="padding: 15px;">
                            <form class="form-horizontal tasi-form">
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" v-model="search.value">
                                    </div>
                                    <button type="submit" @click.prevent="searchLists()" class="btn btn-info"><font><font>搜索</font></font></button>
                                </div>
                            </form>
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th>链接名称</th>
                                <th>图片/文字</th>
                                <th>图片名称</th>
                                <th>链接地址</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data,index) in datas">
                                <td><font><font>@{{ data.name }}</font></font></td>
                                <td v-if=" data.type == 1"><font><font>图片 </font></font></td>
                                <td v-else><font><font>文字</font></font></td>
                                <td v-if=" data.type == 1"><font><font><img :src="'{{ env("QINIU_DOMAIN") }}'+data.image" alt="" width="80" height="50" ></font></font></td>
                                <td v-if=" data.type == 2"><font><font>@{{ data.name }}</font></font></td>
                                <td><font><font>@{{ data.url }}</font></font></td>
                                <td>
                                    <button @click="getAdminId(data.id,data)" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#updatePassword" data-whatever="@getbootstrap"  >修改</button>
                                    <button  @click="deleteAdmin(data.id,index)" class="btn btn-danger btn-xs">删除</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </section>
                    <center>@include('common.page')</center>
                    <!-- create admin form  start -->
                    <div class="modal fade createAdminUser" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="exampleModalLabel">添加友情链接</h4>
                                </div>
                                <div class="modal-body " id='FriendLink'>
                                    <form class="userForm linkList"  @submit.prevent="sub($event)" onsubmit ="return false" >
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label">链接名称:</label>
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" name="type" value="1" v-model="type"> 图片<br>
                                            <input type="radio" name="type" value="2" v-model="type"> 文字
                                        </div>
                                        <div class="form-group" v-if="type==1">
                                                <div class="col-lg-13">
                                                    <img width="100px" class="img-responsive img_img" id="img_img" @click="upload" src="https://dn-phphub.qbox.me/uploads/images/201704/11/4430/U0ctyGJUV7.png">
                                                    <input class="img" style="display: none" type="file" class="form-control" >
                                                    <input type="hidden" name="image" class="image_img">
                                                </div>
                                            </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">链接地址:</label>
                                            <input type="text" class="form-control"  name="url" id="url">
                                        </div>
                                        <div class="modal-footer" style="margin-top: 45px;">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                            <button type="submit"  class="btn btn-primary">提交</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- create admin form  end -->
                    <!-- update admin password form  start -->
                    <div class="modal fade updateAdminLink" id="updatePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="exampleModalLabel">修改友情链接</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="linkList"  @submit.prevent="submit($event)" >
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label">链接名称:</label>
                                            <input type="text" class="form-control" id="name" name="name" :value="link.name">
                                        </div>
                                        <div class="form-group">
                                            <img v-if=" link.type == 1" class="img-responsive img_img" id="img_img" @click="upload" :src="'{{ env("QINIU_DOMAIN") }}'+link.image" alt="" width="100"  >
                                            <input class="img" style="display: none" type="file"  class="form-control"/>
                                            <input type="hidden" name="image" class="image_img">
                                        </div>
                                        <div>
                                            <label for="message-text" class="control-label">链接地址:</label>
                                            <input type="text" class="form-control" id="url" name="url" :value="link.url">
                                        </div>
                                        <div class="modal-footer" style="margin-top: 45px;">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                            <button type="button" @click="linkUpdate"  class="btn btn-primary">提交</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- update   end -->
                </div>
            </div>
        </section>
    </section>
@stop
@section('customJs')
    <!-- 定义七牛 -->
    <script> var QINIU_DOMAIN = '{{ env("QINIU_DOMAIN") }}';</script>
    <!--定义token-->
    <script> var token = "{{ csrf_token() }}" </script>
    <!-- 获取列表页js -->
    <script src="{{ asset('admins/handle/link/index.js') }}"></script>
    <!-- 表单验证js -->
    {{--<script src="{{ asset('admins/handle/link/link_validation.js') }}"></script>--}}

@stop