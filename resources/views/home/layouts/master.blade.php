<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- core CSS -->
    <link href="/css/app.css" rel="stylesheet" type="text/css"/>
    @section('coreCss')
        <link href="/AmazeUI-2.4.2/assets/css/admin.css" rel="stylesheet" type="text/css"/>
        <link href="/AmazeUI-2.4.2/assets/css/amazeui.css" rel="stylesheet" type="text/css"/>
    @show

    <!-- external css -->
    @yield('externalCss')

    <!-- custom css -->
    @yield('customCss')

    <!-- core js -->
    @section('coreJs')
        <script src="/AmazeUI-2.4.2/assets/js/jquery.min.js"></script>
        <script src="/AmazeUI-2.4.2/assets/js/amazeui.js"></script>
        <script src="/layer/layer.js"></script>
    @show

    <!-- external js -->
    @yield('externalJs')
</head>

<body>
@yield('header')

@yield('nav')

@yield('content')

<!-- custom js -->
@yield('customJs')
</body>
</html>