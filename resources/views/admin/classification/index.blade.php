@extends('admin.layouts.master')
@section('content')
    <section id="main-content" class="has-js">
        <section class="wrapper">
            <!-- 绑定属性模态框 Start -->
            <div class="modal fade" id="bindModal" tabindex="-1" role="dialog" aria-labelledby="bindModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="/admin/aaaa" method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="exampleModalLabel">分类下的标签</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="addName" class="control-label">分类名称:</label>
                                    <span class="form-control">@{{ (datas[labelBind.index] != null ? datas[labelBind.index].name :'') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="addName" class="control-label">标签:</label>
                                    <div class="checkboxes">
                                        <div class="row" id="labelsC">
                                            <div class="col-md-6" v-for="(label, index) in labelBind.labels">
                                                <label class="label_check" :class="{'c_on':label.checked}">
                                                    <input
                                                            :value="label.id"
                                                            type="checkbox"/> @{{ label.category_label_name }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" v-model.trim="labelBind.labelName" class="form-control"
                                                   placeholder="标签名称">
                                        </div>
                                        <div class="tagsinput-add" @click="addNewLabel()"></div>
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

            <!-- 添加子分类模态框 Start -->
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">添加子分类</h4>
                        </div>
                        <form id="formCategory" class="validator-form" onsubmit="return false" method="post"
                              @submit.prevent="createChild($event)">
                            <div class="modal-body">
                                {{ csrf_field() }}
                                <input type="hidden" class="pid" name="pid" :value="category.pid">
                                <input type="hidden" class="level" name="level" :value="category.level">
                                <div class="form-group">
                                    <label for="addName" class="control-label">分类名称:</label>
                                    <input type="text" class="form-control recipient-name" id="addName" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="addDescrib" class="control-label">描述:</label>
                                    <input type="text" class="form-control describe" id="addDescribe" name="describe">
                                </div>
                                <div class="form-group">
                                    <label for="curl" class="control-label col-lg-2">图片</label>
                                    <label for="img"><img width="100px" class="img-responsive img_img"
                                                          id="img_img"
                                                          src="https://dn-phphub.qbox.me/uploads/images/201704/11/4430/U0ctyGJUV7.png"></label>
                                    <input id="img" style="display: none" type="file" class="form-control img"
                                           name="image">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" @click="emptyForm()" class="btn btn-default" data-dismiss="modal">
                                    关闭
                                </button>
                                <button type="submit" class="btn btn-primary submit">提交</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- 添加子分类模态框 End -->

            <!-- 修改分类模态框 start -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">修改分类</h4>
                        </div>
                        <form id="formCategory" class="validator-form" method="post" onsubmit="return false"
                              @submit.prevent="submit(category.id, $event)">
                            <div class="modal-body">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">分类名称:</label>
                                    <input type="text" class="form-control recipient-name" id="recipient-name"
                                           name="name"
                                           :value="category.name">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="control-label">描述:</label>
                                    <input type="text" class="form-control describe" id="describe" name="describe"
                                           :value="category.describe">
                                </div>
                                <div class="form-group">
                                    <label for="curl" class="control-label col-lg-2">图片</label>
                                    <label for="img"><img width="100px" class="img-responsive img_img" id="img_img"
                                                          :src="(category.img != null) ? category.doma + category.img : 'https://dn-phphub.qbox.me/uploads/images/201704/11/4430/U0ctyGJUV7.png'"></label>
                                    <input id="img" style="display: none" type="file" class="form-control img"
                                           name="image">
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
                            分类列表：@{{ getLevel(currentLevel) }}
                            <button class="btn btn-primary btn-sm pull-right col-lg-1" @click="backTo()"
                                    v-if="currentLevel != 1">上一级分类
                            </button>
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th> id</th>
                                <th> 分类名称</th>
                                <th> 父级分类</th>
                                <th> 操作</th>
                                <th> 子类操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data, index) in datas">
                                <td>@{{ data.id }}</td>
                                <td>@{{ data.name }}</td>
                                <td>@{{ (data.parent_category != null)?data.parent_category.name:'顶级分类' }}</td>
                                <td>
                                    <button @click="fetchCategoryById(data.id, index)" class="btn btn-info btn-xs"
                                            data-toggle="modal" data-target="#exampleModal"
                                            data-whatever="@getbootstrap">修改
                                    </button>
                                    <button class="btn btn-danger btn-xs" @click="toggleEnabledBy(data.id, index, 0)"
                                            v-if="data.deleted_at == null">
                                        禁用
                                    </button>
                                    <button class="btn btn-success btn-xs" @click="toggleEnabledBy(data.id, index, 1)"
                                            v-else>
                                        启用
                                    </button>
                                </td>
                                <td v-if="currentLevel != 3 && data.deleted_at == null">
                                    <button class="btn btn-primary btn-xs" @click="catChild(data)"><i
                                                class="icon-eye-open"></i></button>
                                    <button class="btn btn-success btn-xs" @click="childSet(data.id, data.level)"
                                            data-toggle="modal" data-target="#addModal"
                                            data-whatever="@getbootstrap"><i class="icon-plus"></i></button>
                                </td>
                                <td v-else-if="currentLevel == 3 && data.deleted_at == null">
                                    <button class="btn btn-primary btn-xs"
                                            @click="fetchCategoryForLabel(index, data.id)"
                                            data-toggle="modal" data-target="#bindModal"
                                            data-whatever="@getbootstrap">查看标签
                                    </button>
                                </td>
                                <td v-else="data.deleted_at != null">

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
    <!-- 引入公共 js -->
    <script src="{{ asset('admins/handle/common/common.js') }}"></script>
    <!-- 当前页面 js -->
    <script src="{{ asset('admins/handle/classification/index.js') }}"></script>
    <!-- 当前页面表单验证 js -->
    <script src="{{ asset('admins/handle/classification/index_form_validation.js') }}"></script>
@stop