<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="/admins/img/favicon.html">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@section('title') LaraMall 商城后台 @show</title>

    <!-- Bootstrap core CSS -->
    <link href="/admins/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admins/css/bootstrap-reset.css" rel="stylesheet">

    <!--external css-->
    <link href="/admins/assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/bootstrapValidator.min.css">
    <link rel="stylesheet" href="/css/sweetalert.css">
    @yield('externalCss')

    <!-- Custom styles for this template -->
    <link href="/admins/css/style.css" rel="stylesheet">
    <link href="/admins/css/style-responsive.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/sweetalert.css">
    @yield('customCss')

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admins/js/html5shiv.js"></script>
    <script src="/admins/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<section id="container" class="">
    <!--header start-->
    @section('header')
        @include('admin.public.header')
    @show
    <!--header end-->
    <!--sidebar start-->
    @section('sidebar')
        @include('admin.public.sidebar')
    @show
    <!--sidebar end-->
    <!--main content start-->
    @yield('content')
    <!--main content end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->
@section('coreJs')
    <script src="/admins/js/jquery.js"></script>
    <script src="/admins/js/bootstrap.min.js"></script>
    <script src="/admins/js/jquery.scrollTo.min.js"></script>
    <script src="/admins/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="/js/bootstrapValidator.min.js"></script>
    <script src="/layer/layer.js"></script>
    <!-- Vue -->
    <script src="/js/vue.js"></script>
    <script src="/js/axios.min.js"></script>
    <script src="/js/sweetalert.min.js"></script>
@show

@yield('externalJs')

<!--common script for all pages-->
<script src="/admins/js/common-scripts.js"></script>

<!--script for this page-->
@yield('customJs')

<!--flashy-->
@include('flashy::message')
</body>
</html>
