@extends('admin.layouts.master')

@section('title')
    FlatLab - Flat & Responsive Bootstrap Admin Template
@stop

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            添加角色
                        </header>
                        <div class="panel-body">
                            @include('notice.error')
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" enctype="multipart/form-data"
                                      id="commentForm" method="post" action="{{ route('acl.store') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group ">
                                        <label for="cname" class="control-label col-lg-2">角色标识</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" id="cname" name="name"
                                                   value="{{ old('name') }}" type="text" placeholder="不可修改!"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">角色名称</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" id="cemail" name="display_name"
                                                   value="{{ old('display_name') }}" type="text"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="curl" class="control-label col-lg-2">角色描述</label>
                                        <div class="col-lg-10">
                                            <textarea name="description" class="form-control" id="curl" cols="30"
                                                      rows="10">{{ old('description') }}</textarea>
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
    <script src="{{ asset('admins/handle/acl/insert_form_validation.js') }}"></script>
@stop