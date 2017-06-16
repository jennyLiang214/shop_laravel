@extends('admin.layouts.master')

@section('title')
    添加分类
@stop

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            添加顶级分类
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" enctype="multipart/form-data"
                                      id="commentForm" method="post" action="{{ route('classification.store') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="pid" value="0">
                                    <input type="hidden" name="level" value="1">
                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">分类名称</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" id="cname" name="name"
                                                   value="{{ old('name') }}" type="text"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">分类描述</label>
                                        <div class="col-lg-10">
                                            <textarea class="form-control " rows="5" value="{{ old('describe') }}"
                                                      id="cemail"
                                                      name="describe"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="curl" class="control-label col-lg-2">图片</label>
                                        <div class="col-lg-10">
                                            <label for="img"><img width="100px" class="img-responsive" id="img_img"
                                                                  src="https://dn-phphub.qbox.me/uploads/images/201704/11/4430/U0ctyGJUV7.png"></label>
                                            <input id="img" style="display: none" type="file" class="form-control"
                                                   name="image">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <button class="btn btn-danger" type="submit">添加</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
@stop

@section('customJs')
    <!-- 当前页面表单验证 js -->
    <script src="{{ asset('admins/handle/classification/insert_form_validation.js') }}"></script>
    <!-- 当前页面 js -->
    <script src="{{ asset('admins/handle/classification/insert.js') }}"></script>
@stop