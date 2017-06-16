@extends('home.layouts.master')

@section('title')
    我的收藏
@stop

@section('externalCss')
    <link href="/css/personal.css" rel="stylesheet" type="text/css">
    <link href="/css/colstyle.css" rel="stylesheet" type="text/css">
@stop

@section('header')
    @include('home.public.header')
@stop

@section('nav')
    @include('home.public.navTab')
@stop

@section('content')
    <div class="center">
        <div class="col-main">
            <div class="main-wrap">

                <div class="user-collection">
                    <!--标题 -->
                    <div class="am-cf am-padding">
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">我的收藏</strong> /
                            <small>My&nbsp;Collection</small>
                        </div>
                    </div>
                    <hr>

                    <div class="you-like">
                        <div class="s-bar">
                            我的收藏
                        </div>
                        <div class="s-content">
                            @if(!empty($data))
                            @foreach($data as $item)
                            <div class="s-item-wrap">
                                <div class="s-item">

                                    <div class="s-pic">
                                        <a href="{{ url('home/goodsDetail') }}/{{ $item['cargo_id'] }}" class="s-pic-link">
                                            <img src="{{ env('QINIU_DOMAIN') }}{{ $item['cargo_cover'] }}" alt="{{ $item['cargo_name'] }}" title="{{ $item['cargo_name'] }}" class="s-pic-img s-guess-item-img">
                                            @if($item['cargo_status'] == 3)<span class="tip-title">已下架</span>@endif
                                        </a>
                                    </div>
                                    <div class="s-info">
                                        <div class="s-title"><a href="{{ url('home/goodsDetail') }}/{{ $item['cargo_id'] }}" title="{{ $item['cargo_name'] }}">{{ $item['cargo_name'] }}</a>
                                        </div>
                                        <div class="s-price-box">
                                            <span class="s-price"><em class="s-price-sign">¥</em><em class="s-value">{{ $item['cargo_price'] }}</em></span>
                                            <span class="s-history-price"><em class="s-price-sign">¥</em><em class="s-value">{{ $item['cargo_price'] }}</em></span>
                                        </div>
                                        <div class="s-extra-box">
                                            <span class="s-comment"><a href="javascript:;">收藏数:{{ $item['cargo_collection'] }}</a></span>
                                            <span class="s-sales"><a href="javascript:;" class="del" data-id="{{ $item['cargo_id'] }}">移除</a></span>
                                        </div>
                                    </div>
                                    <div class="s-tp">
                                        <span class="ui-btn-loading-before">
                                            @if(!empty($item['category_attr_id']))
                                            <a href="{{ url('/home/goodsList') }}/{{ $item['category_id'] }}?ev={{ $item['category_attr_id'] }}"  style="color:#fff">找相似</a>
                                            @else
                                                <a href="{{ url('/home/goodsList') }}/{{ $item['category_id'] }}"  style="color:#fff">找相似</a>
                                            @endif
                                        </span>
                                        <i class="am-icon-shopping-cart"></i>
                                        <span class="ui-btn-loading-before buy" data-cargoId="{{ $item['cargo_id'] }}">加入购物车</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <!--分页 -->
                        @if(!$page->isEmpty())
                            <div class="am-pagination" style="text-align:center">
                                {{ $page->render() }}
                            </div>
                        @endif

                    </div>

                </div>

            </div>
            <!--底部-->
            @include('home.public.footer')
        </div>

        @include('home.public.aside')
    </div>
@stop
@section('customJs')
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script type="text/javascript">var token= "{{ csrf_token() }}"</script>
    <script src="{{ asset('/handle/member/goodsCollection_index.js') }}" type="text/javascript"></script>


@stop