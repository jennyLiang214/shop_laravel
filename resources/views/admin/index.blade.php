@extends('admin.layouts.master')

@section('title')
    后台首页
@stop

@section('externalCss')
    <link href="/admins/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/admins/css/owl.carousel.css" type="text/css">
@stop

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <!--state overview start-->
            <div class="row state-overview">
                <div class="col-lg-3 col-sm-6">
                    <section class="panel">
                        <div class="symbol terques">
                            <i class="icon-user"></i>
                        </div>
                        <div class="value">
                            <h1>@{{ header.user }}</h1>
                            <p>用户数量</p>
                        </div>
                    </section>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <section class="panel">
                        <div class="symbol yellow">
                            <i class="icon-shopping-cart"></i>
                        </div>
                        <div class="value">
                            <h1>@{{ header.order }}</h1>
                            <p>订单数量</p>
                        </div>
                    </section>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <section class="panel">
                        <div class="symbol red">
                            <i class="icon-tags"></i>
                        </div>
                        <div class="value">
                            <h1>@{{ header.success_order }}</h1>
                            <p>支付数量</p>
                        </div>
                    </section>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <section class="panel">
                        <div class="symbol blue">
                            <i class="icon-bar-chart"></i>
                        </div>
                        <div class="value">
                            <h1>@{{ header.price }}</h1>
                            <p>收款金额</p>
                        </div>
                    </section>
                </div>
            </div>
            <!--state overview end-->

            <div class="row">
                <div class="col-lg-12">
                    <!--custom chart start-->
                    <div class="border-head">
                        <h3>数据统计</h3>
                    </div>
                    <div>
                        <div id="user"  class="col-lg-6" style="height:300px;"></div>
                        <div id="order"  class="col-lg-6" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@stop

@section('customJs')
    <script> var token = "{{ csrf_token() }}" </script>
    <script src="{{ asset('/admins/js/echarts.common.min.js') }}"></script>
    <script src="{{ asset('/admins/handle/index/index.js') }}"></script>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var userChart = echarts.init(document.getElementById('user'));
        var orderChart = echarts.init(document.getElementById('order'));
    </script>
@stop