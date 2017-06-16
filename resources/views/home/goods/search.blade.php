@extends('home.layouts.master')

@section('title')
    商品搜索页
@stop

@section('coreCss')
    <link href="/AmazeUI-2.4.2/assets/css/amazeui.css" rel="stylesheet" type="text/css"/>
    <link href="/AmazeUI-2.4.2/assets/css/admin.css" rel="stylesheet" type="text/css"/>
@stop

@section('externalCss')
    <link href="/basic/css/demo.css" rel="stylesheet" type="text/css"/>
    <link href="/css/seastyle.css" rel="stylesheet" type="text/css"/>
@stop

@section('coreJs')
    <script type="text/javascript" src="/basic/js/jquery-1.7.min.js"></script>
    <script src="/layer/layer.js"></script>
@stop

@section('header')
    @include('home.public.amContainer')
@stop

@section('nav')
    @include('home.public.nav')
@stop

@section('content')
    @inject('GoodsListPresenter', 'App\Presenters\HomeGoodsListPresenter')
    <b class="line"></b>
    <div class="search">
        <div class="search-list">
            <div class="nav-table">
                <div class="long-title"><span class="all-goods">全部分类</span></div>
                <div class="nav-cont">
                    <ul>
                        <li class="index"><a href="#">首页</a></li>
                        <li class="qc"><a href="#">闪购</a></li>
                        <li class="qc"><a href="#">限时抢</a></li>
                        <li class="qc"><a href="#">团购</a></li>
                        <li class="qc last"><a href="#">大包装</a></li>
                    </ul>
                    <div class="nav-extra">
                        <i class="am-icon-user-secret am-icon-md nav-user"></i><b></b>我的福利
                        <i class="am-icon-angle-right" style="padding-left: 10px;"></i>
                    </div>
                </div>
            </div>


            <div class="am-g am-g-fixed">
                <div class="am-u-sm-12 am-u-md-12">
                    <div class="theme-popover">
                        <div class="searchAbout">
                            <span class="font-pale">全部结果 > <strong style="color: #666; font-weight: bold">"{{ $keyword }}"</strong></span>
                        </div>
                        {{--<ul class="select">--}}
                        {{--<li class="select-list"></li>--}}
                        {{--</ul>--}}
                        {{--<div class="clear"></div>--}}
                    </div>
                    <div>
                        @if(!$cargos->isEmpty())
                            <div class="sort">
                                <li class="first"><a title="综合">综合排序</a></li>
                                <li><a title="销量">销量排序</a></li>
                                <li><a title="价格">价格优先</a></li>
                                <li class="big"><a title="评价" href="#">评价为主</a></li>
                            </div>
                            <div class="clear"></div>
                        @endif

                        <ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-4 boxes">
                            @forelse($cargos as $cargo)
                                <li>
                                    <div class="i-pic limit">
                                        <a href="/home/goodsDetail/{{ $cargo->id }}"><img src="{{ env('QINIU_DOMAIN') }}{{ $cargo['cargo_cover'] }}?imageView2/1/w/430/h/430"/></a>
                                        <p class="title fl"><a href="/home/goodsDetail/{{ $cargo->id }}">{{ $cargo->cargo_name }}</a></p>
                                        <p class="price fl">
                                            <b>¥</b>
                                            <strong>{{ $cargo->cargo_discount }}</strong>
                                        </p>
                                        <p class="number fl">
                                            <a href="javascript:;" class="collection" data-cargo-id="{{ $cargo->id }}">
                                                @if(!empty($cargo->goodscollection->toArray()) && !empty(\Session::get('user')->user_id))
                                                    @foreach($cargo->goodscollection as $item)
                                                        @if($cargo->id == $item->cargo_id)
                                                            已收藏
                                                        @else
                                                            收藏
                                                        @endif
                                                    @endforeach
                                                @else
                                                    收藏
                                                @endif
                                            </a>
                                            <span class="count">{{count($cargo->goodscollection->toArray())}}</span>
                                        </p>
                                    </div>
                                </li>
                            @empty
                                <li>抱歉，没有找到与“{{ $keyword }}”相关的商品</li>
                            @endforelse
                        </ul>
                    </div>
                    <!--分页 -->
                    @if(!$cargos->isEmpty())
                        <div class="am-pagination pull-right">
                            {{ $cargos->appends(['keyword' => $keyword]) }}
                        </div>
                    @endif
                </div>
            </div>
            @include('home.public.footer')
        </div>

    </div>

    @include('home.public.navCir')

    @include('home.public.tip')

    <div class="theme-popover-mask"></div>
@stop

@section('customJs')
    <script>
        window.jQuery || document.write('<script src="basic/js/jquery-1.9.min.js"><\/script>');
    </script>
    <script type="text/javascript" src="/basic/js/quick_links.js"></script>
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script type="text/javascript">var token= "{{ csrf_token() }}"</script>
    <script src="{{ asset('/handle/goods_list.js') }}" type="text/javascript"></script>
@stop