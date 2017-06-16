@extends('home.layouts.master')

@section('title')
    商品列表页
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
                        <li class="index"><a href="{{ url('/home/index') }}">首页</a></li>
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
            </div>

            <div class="am-g am-g-fixed">
                <div class="am-u-sm-12 am-u-md-12">
                    <div class="theme-popover">
                        <div class="searchAbout">
                            <span class="font-pale">{{ $data['tree'][0]['name'] }}
                                <em>&gt;</em>{{ $data['tree'][1]['name'] }}
                                <em>&gt;</em>{{ $data['tree'][2]['name'] }}</span>
                        </div>
                        <ul class="select">
                            <p class="title font-normal">
                                <span class="fl">{{ $data['tree'][2]['name'] }} 商品筛选 </span>
                                <span class="total fl">共<strong
                                            class="num">{{ count($data['cargos']) }}</strong>件相关商品</span>
                            </p>
                            <div class="clear"></div>
                            @if(!empty($data['ev']))
                                <li class="select-result" style="display: list-item;">
                                    <dl>
                                        <dt>已选</dt>
                                        <dd class="select-no" style="display: none;"></dd>
                                        <p class="eliminateCriteria" style="display: block;"
                                           onclick="location.href='{{ $GoodsListPresenter->destroy($data['category_id'], $data['sort']) }}'">
                                            清除</p>
                                        @foreach($data['ev'] as $k => $v)
                                            <dd class="selected" id="selectA"><a
                                                        href="{{ $GoodsListPresenter->delSelectedUrl($k, $v, $data['ev'], $data['sort']) }}">{{ $data['labels'][$k] }}
                                                    ：{{ $data['attrs'][$v] }}</a></dd>
                                        @endforeach
                                    </dl>
                                </li>
                            @endif
                            <div class="clear"></div>
                            @foreach($data['categoryLabels'] as $label)
                                @if(!isset($data['ev'][$label->id]))
                                    <li class="select-list">
                                        <dl id="select1">
                                            <dt class="am-badge am-round">{{ $label->category_label_name }}</dt>
                                            <div class="dd-conent">
                                                @foreach($label->categoryAttrs as $attr)
                                                    <dd>
                                                        <a href="{{ $GoodsListPresenter->createUrl($label->id, $attr->id, $data['ev'], $data['sort']) }}">{{ $attr->attribute_name }}</a>
                                                    </dd>
                                                @endforeach
                                            </div>
                                        </dl>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <ul class="sort">
                            <li {{ !isset($_GET['sort']) ? 'class=first' : '' }}><a
                                        href="{{ $GoodsListPresenter->createSortUrl() }}" title="综合">综合排序</a></li>
                            <li {{ isset($_GET['sort']) && strpos($_GET['sort'], 'sale') !== false ? 'class=first' : '' }}>
                                <a href="{{ $GoodsListPresenter->createSortUrl(strpos($data['sort'], 'sale') === false ? 'sale_asc' : $data['sort']) }}" title="销量">销量</a></li>
                            <li {{ isset($_GET['sort']) && strpos($_GET['sort'], 'sort') !== false ? 'class=first' : '' }}>
                                <a href="{{ $GoodsListPresenter->createSortUrl(strpos($data['sort'], 'sort') === false ? 'sort_asc' : $data['sort']) }}" title="价格">价格</a></li>
                            <li {{ isset($_GET['sort']) && strpos($_GET['sort'], 'comment') !== false ? 'class=first' : '' }}>
                                <a href="{{ $GoodsListPresenter->createSortUrl(strpos($data['sort'], 'comment') === false ? 'comment_asc' : $data['sort']) }}" title="评论数"
                                   href="#">评论数</a></li>
                        </ul>
                        <div class="clear"></div>
                        <ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-4 boxes">
                            @foreach($data['cargos'] as $cargo)
                                @if($cargo->cargo_status == 2)
                                    <li>
                                        <div class="i-pic limit">
                                            <a href="/home/goodsDetail/{{ $cargo->id }}"><img
                                                        src="{{ env('QINIU_DOMAIN') }}{{ $cargo->cargo_cover }}?imageView2/1/w/430/h/430"/></a>
                                            <p class="title fl">{{ $cargo->cargo_name }}</p>
                                            <p class="price fl">
                                                <b>¥</b>
                                                <strong>{{ $cargo->cargo_discount }}</strong>
                                            </p>
                                            <p class="number fl">
                                                <a href="javascript:;" class="collection"
                                                   data-cargo-id="{{ $cargo->id }}">

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
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <div class="clear"></div>
                    <!--分页 -->
                    @if(!$data['cargos']->isEmpty())
                        <div class="am-pagination">
                            @if(isset($data['ev']) && !empty($data['ev']))
                                {{ $data['cargos']->appends(['ev'=>$GoodsListPresenter->convertUrl($data['ev'])]) }}
                            @else
                                {{ $data['cargos']->render() }}
                            @endif
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
    <script type="text/javascript">var token = "{{ csrf_token() }}"</script>
    <script src="{{ asset('/handle/goods_list.js') }}" type="text/javascript"></script>
@stop