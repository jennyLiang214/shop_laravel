@extends('admin.layouts.master')
@section('content')
    <section id="main-content" class="has-js">
        <section class="wrapper">
            <!-- 修改分类模态框 start -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">修改推荐位信息</h4>
                        </div>
                        <form id="formCategory" class="validator-form" method="post" onsubmit="return false"
                              @submit.prevent="submit(recommend.id, $event)">
                            <div class="modal-body">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">推荐位名称:</label>
                                    <input type="text" class="form-control recipient-name" id="recipient-name"
                                           name="recommend_name" :value="recommend.recommend_name">
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">推荐位导语:</label>
                                    <input type="text" class="form-control recipient-name" id="recipient-name"
                                           name="recommend_introduction" :value="recommend.recommend_introduction">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="control-label">推荐位样式:</label>
                                    <div class="radios">
                                        <label>
                                            <input type="radio" name="recommend_type" id="optionsRadios1"
                                                   value="1" :checked="recommend.recommend_type == 1">
                                            样式一
                                        </label>
                                        <label>
                                            <input type="radio" name="recommend_type" id="optionsRadios1"
                                                   :checked="recommend.recommend_type == 2" value="2">
                                            样式二
                                        </label>
                                        <label>
                                            <input type="radio" name="recommend_type" id="optionsRadios1"
                                                   :checked="recommend.recommend_type == 3" value="3">
                                            样式三
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" @click="emptyForm()" class="btn btn-default" data-dismiss="modal">
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
                            推荐位列表
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th> id</th>
                                <th> 推荐位名称</th>
                                <th> 推荐位位置</th>
                                <th> 推荐位样式</th>
                                <th> 操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data, index) in datas">
                                <td>@{{ data.id }}</td>
                                <td>@{{ data.recommend_name }}</td>
                                <td>@{{ (data.recommend_location == 1) ? '首页' : '敬请期待' }}</td>
                                <td>@{{ recommendType(data.recommend_type) }}</td>
                                <td>
                                    <button class="btn btn-primary btn-xs"
                                            @click="fetchByIndex(index)"
                                            data-toggle="modal" data-target="#exampleModal"
                                            data-whatever="@getbootstrap">修改信息
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
    <script src="/admins/handle/recommend/index.js"></script>
    <!-- 当前页面表单验证 js -->
    <script src="/admins/handle/recommend/index_form_validation.js"></script>
@stop