@extends('home.layouts.master')

@section('title')
    首页
@stop

@section('coreCss')
    <link href="/AmazeUI-2.4.2/assets/css/amazeui.css" rel="stylesheet" type="text/css"/>
    <link href="/AmazeUI-2.4.2/assets/css/admin.css" rel="stylesheet" type="text/css"/>
@stop

@section('externalCss')
    <link href="/basic/css/demo.css" rel="stylesheet" type="text/css"/>
    <link href="/css/hmstyle.css" rel="stylesheet" type="text/css"/>
@stop

@section('customCss')
    <link href="/css/custom/index.css" rel="stylesheet" type="text/css"/>
@stop

@section('header')
    @include('home.public.hmtop')
@stop

@section('nav')
    @inject('HomeIndexPresenter', 'App\Presenters\HomeIndexPresenter')
    <div class="banner">
        <!--轮播 -->
        <div class="am-slider am-slider-default scoll" data-am-flexslider id="demo-slider-0">
            <ul class="am-slides">
                <li class="banner1"><a href="introduction.html"><img src="/images/ad1.jpg"/></a></li>
                <li class="banner2"><a><img src="/images/ad2.jpg"/></a></li>
                <li class="banner3"><a><img src="/images/ad3.jpg"/></a></li>
                <li class="banner4"><a><img src="/images/ad4.jpg"/></a></li>

            </ul>
        </div>
        <div class="clear"></div>
    </div>
    <div class="shopNav">
        <div class="slideall">

            <div class="long-title"><span class="all-goods">全部分类</span></div>
            <div class="nav-cont">
                <ul>
                    <li class="index"><a href="/home/index">首页</a></li>
                    <li class="qc"><a href="javascript:;" onclick="layer.msg('暂未开通,敬请期待')">闪购</a></li>
                    <li class="qc"><a href="javascript:;" onclick="layer.msg('暂未开通,敬请期待')">限时抢</a></li>
                    <li class="qc"><a href="javascript:;" onclick="layer.msg('暂未开通,敬请期待')">团购</a></li>
                    <li class="qc last"><a href="javascript:;" onclick="layer.msg('暂未开通,敬请期待')">大包装</a></li>
                </ul>
                @if(false)
                <div class="nav-extra">
                    <i class="am-icon-user-secret am-icon-md nav-user"></i><b></b>我的福利
                    <i class="am-icon-angle-right" style="padding-left: 10px;"></i>
                </div>
                @endif
            </div>

            <!--侧边导航 -->
            <div id="nav" class="navfull">
                <div class="area clearfix">
                    <div class="category-content" id="guide_2">
                        <div class="category">
                            <ul class="category-list" id="js_climit_li">
                                @foreach($data['categorys'] as $category)
                                    <li class="appliance js_toggle relative first">
                                        <div class="category-info">
                                            <h3 class="category-name b-category-name">
                                                <a class="ml-22" title="{{ $category->name }}">{{ $category->name }}</a>
                                            </h3>
                                            <em>&gt;</em>
                                        </div>
                                        <div class="menu-item menu-in top">
                                            <div class="area-in">
                                                <div class="area-bg">
                                                    <div class="menu-srot">
                                                        <div class="sort-side">
                                                            @foreach($category->children as $children)
                                                                <dl class="dl-sort">
                                                                    <dt>
                                                                        <span title="{{ $children->name }}">{{ $children->name }}</span>
                                                                    </dt>
                                                                    @foreach($children->grandchild as $grandchild)
                                                                        <dd><a title="{{ $grandchild->name }}"
                                                                               href="/home/goodsList/{{ $grandchild->id }}"><span>{{ $grandchild->name }}</span></a>
                                                                        </dd>
                                                                    @endforeach
                                                                </dl>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <b class="arrow"></b>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!--轮播 -->
            <script type="text/javascript">
                (function () {
                    $('.am-slider').flexslider();
                });
                $(document).ready(function () {
                    $("li").hover(function () {
                        $(".category-content .category-list li.first .menu-in").css("display", "none");
                        $(".category-content .category-list li.first").removeClass("hover");
                        $(this).addClass("hover");
                        $(this).children("div.menu-in").css("display", "block")
                    }, function () {
                        $(this).removeClass("hover")
                        $(this).children("div.menu-in").css("display", "none")
                    });
                });
            </script>

            <!--小导航 -->
            <div class="am-g am-g-fixed smallnav">
                <div class="am-u-sm-3">
                    <a href="sort.html"><img src="/images/navsmall.jpg"/>
                        <div class="title">商品分类</div>
                    </a>
                </div>
                <div class="am-u-sm-3">
                    <a href="#"><img src="/images/huismall.jpg"/>
                        <div class="title">大聚惠</div>
                    </a>
                </div>
                <div class="am-u-sm-3">
                    <a href="#"><img src="/images/mansmall.jpg"/>
                        <div class="title">个人中心</div>
                    </a>
                </div>
                <div class="am-u-sm-3">
                    <a href="#"><img src="/images/moneysmall.jpg"/>
                        <div class="title">投资理财</div>
                    </a>
                </div>
            </div>
            @if(false)
            <!--走马灯 -->
            <div class="marqueen">
                <span class="marqueen-title">商城头条</span>
                <div class="demo">

                    <ul>
                        <li class="title-first"><a target="_blank" href="#">
                                <img src="/images/TJ2.jpg"></img>
                                <span>[特惠]</span>商城爆品1分秒
                            </a></li>
                        <li class="title-first"><a target="_blank" href="#">
                                <span>[公告]</span>商城与广州市签署战略合作协议
                                <img src="/images/TJ.jpg"></img>
                                <p>XXXXXXXXXXXXXXXXXX</p>
                            </a></li>

                        <div class="mod-vip">
                            <div class="m-baseinfo">
                                <a href="/person/index.html">
                                    <img src="/images/getAvatar.do.jpg">
                                </a>
                                <em>
                                    Hi,<span class="s-name">小叮当</span>
                                    <a href="#"><p>点击更多优惠活动</p></a>
                                </em>
                            </div>
                            <div class="member-logout">
                                <a class="am-btn-warning btn" href="login.html">登录</a>
                                <a class="am-btn-warning btn" href="register.html">注册</a>
                            </div>
                            <div class="member-login">
                                <a href="#"><strong>0</strong>待收货</a>
                                <a href="#"><strong>0</strong>待发货</a>
                                <a href="#"><strong>0</strong>待付款</a>
                                <a href="#"><strong>0</strong>待评价</a>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <li><a target="_blank" href="#"><span>[特惠]</span>洋河年末大促，低至两件五折</a></li>
                        <li><a target="_blank" href="#"><span>[公告]</span>华北、华中部分地区配送延迟</a></li>
                        <li><a target="_blank" href="#"><span>[特惠]</span>家电狂欢千亿礼券 买1送1！</a></li>

                    </ul>
                    <div class="advTip"><img src="/images/advTip.jpg"/></div>
                </div>
            </div>
            <div class="clear"></div>
           @endif
        </div>
        <script type="text/javascript">
            if ($(window).width() < 640) {
                function autoScroll(obj) {
                    $(obj).find("ul").animate({
                        marginTop: "-39px"
                    }, 500, function () {
                        $(this).css({
                            marginTop: "0px"
                        }).find("li:first").appendTo(this);
                    })
                }

                $(function () {
                    setInterval('autoScroll(".demo")', 3000);
                })
            }
        </script>
    </div>
@stop

@section('content')
    <div class="shopMainbg">
        <div class="shopMain" id="shopmain">
            <!--今日推荐 -->
            <div class="am-g am-g-fixed recommendation">
                <div class="clock am-u-sm-3"
                ">
                <img src="/images/2016.png "></img>
                <p>今日<br>推荐</p>
            </div>
            <div class="am-u-sm-4 am-u-lg-3 ">
                <div class="info ">
                    <h3>真的有鱼</h3>
                    <h4>开年福利篇</h4>
                </div>
                <div class="recommendationMain ">
                    <img src="/images/tj.png "></img>
                </div>
            </div>
            <div class="am-u-sm-4 am-u-lg-3 ">
                <div class="info ">
                    <h3>囤货过冬</h3>
                    <h4>让爱早回家</h4>
                </div>
                <div class="recommendationMain ">
                    <img src="/images/tj1.png "></img>
                </div>
            </div>
            <div class="am-u-sm-4 am-u-lg-3 ">
                <div class="info ">
                    <h3>浪漫情人节</h3>
                    <h4>甜甜蜜蜜</h4>
                </div>
                <div class="recommendationMain ">
                    <img src="/images/tj2.png "></img>
                </div>
            </div>
        </div>
        <div class="clear "></div>
        @if($data['activity'])
            <div class="am-container activity ">
                <div class="shopTitle ">
                    <h4>{{ $data['activity']->name }}</h4>
                    <h3>{{ $data['activity']->desc }}</h3>
                    <span class="more intDiff"></span>
                </div>
                <div class="am-g am-g-fixed">
                    <ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-thumbnails">
                        @foreach($data['activity']->relGoodsActivitys as $relGoodsActivity)
                            @if($relGoodsActivity->cargo->cargo_status == 2)
                                <li>
                                    <a href="/home/goodsDetail/{{ $relGoodsActivity->cargo->id }}">
                                        <img src="{{ env('QINIU_DOMAIN') }}{{ $relGoodsActivity->cargo->cargo_cover }}?imageView2/1/w/350/h/350"/>
                                        <div class="pro-title ">{{ str_limit($relGoodsActivity->cargo->cargo_name, 50, '...') }}</div>
                                        <span class="e-price ">￥{{ $relGoodsActivity->cargo->cargo_price }}</span> <span class="seckill-price"><i>¥</i><del>{{ $relGoodsActivity->cargo->cargo_discount }}</del></span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="clear "></div>
        @endif

        @foreach($data['recommends'] as $recommend)
            <div class="am-container activity">
                <div class="shopTitle ">
                    <h4>{{ $recommend->recommend_name }}</h4>
                    <h3>{{ $recommend->recommend_introduction }}</h3>
                </div>
                <div class="am-g am-g-fixed">
                    <ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-thumbnails">
                        @foreach($recommend->cargos as $cargo)
                            @if($cargo->cargo_status == 2)
                                <li>
                                    <a href="/home/goodsDetail/{{ $cargo->id }}">
                                        <img src="{{ env('QINIU_DOMAIN') }}{{ $cargo->cargo_cover }}?imageView2/1/w/350/h/350"/>
                                        <div class="pro-title ">{{ str_limit($cargo->cargo_name, 50, '...') }}</div>
                                        <span class="e-price ">￥{{ $cargo->cargo_discount }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="clear"></div>
        @endforeach
        @include('home.public.footer')
    </div>
    </div>
    </div>
    </div>

    @include('home.public.navCir')
    @include('home.public.tip')
@stop

@section('customJs')
    <script>
        window.jQuery || document.write('<script src="basic/js/jquery.min.js "><\/script>');
    </script>
    <script type="text/javascript " src="/basic/js/quick_links.js"></script>
    <script>
        @if($data['activity'])
            // 获取距离活动开始的秒数或者距离活动结束的秒数
            var intDiff = parseInt('{{ $HomeIndexPresenter->diffSeconds($data['activity']->start_timestamp, $data['activity']->length) }}');
            var activityLength = parseInt('{{ $data['activity']->length }}') * 60;
        @endif
    </script>
    <script type="text/javascript " src="/handle/index.js"></script>
@stop
