@extends('admin.layouts.master')
@section('title','添加管理员')
@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading"><font><font>
                                添加管理员
                            </font></font></header>
                    <div class="panel-body">
                        <form class="form-horizontal tasi-form" method="post">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><font><font></font></font></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><font><font>密码</font></font></label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" placeholder="">
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </section>
    </section>
@endsection