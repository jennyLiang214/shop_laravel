@extends('admin.layouts.master')

@section('title')
    添加推荐位
@stop

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            添加推荐位
                        </header>
                        <div class="panel-body">
                            @include('notice.error')
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" enctype="multipart/form-data"
                                      id="commentForm" method="post" action="{{ route('recommend.store') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="cname" class="control-label col-lg-2">推荐位置</label>
                                        <div class="col-lg-10 radios">
                                            <label>
                                                <input type="radio" name="recommend_location" id="optionsRadios1"
                                                       value="1" checked>
                                                首页
                                            </label>
                                            <label>
                                                <input type="radio" name="recommend_location" id="optionsRadios1"
                                                       disabled>
                                                敬请期待
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="curl" class="control-label col-lg-2">推荐位样式</label>
                                        <div class="col-lg-10 radios">
                                            <label>
                                                <input type="radio" name="recommend_type" id="optionsRadios1"
                                                       value="1" checked>
                                                样式一
                                            </label>
                                            <label>
                                                <input type="radio" name="recommend_type" value="2" id="optionsRadios1">
                                                样式二
                                            </label>
                                            <label>
                                                <input type="radio" name="recommend_type" value="3" id="optionsRadios1">
                                                样式三
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">推荐位名称</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" id="cemail" name="recommend_name"
                                                   value="{{ old('recommend_name') }}" type="text"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="cemail" class="control-label col-lg-2">推荐位导语</label>
                                        <div class="col-lg-10">
                                            <input class=" form-control" id="cemail" name="recommend_introduction"
                                                   value="{{ old('recommend_introduction') }}" type="text"/>
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
    <script src="{{ asset('admins/handle/recommend/insert_form_validation.js') }}"></script>
@stop