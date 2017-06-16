@extends('home.layouts.master')

@section('title')
    个人资料
@stop

@section('externalCss')
    <link href="{{ asset('/css/personal.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/cmstyle.css') }}" rel="stylesheet" type="text/css">

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

                <div class="user-comment">
                    <!--标题 -->
                    <div class="am-cf am-padding">
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">评价管理</strong> /
                            <small>Manage&nbsp;Comment</small>
                        </div>
                    </div>
                    <hr>

                    <div class="am-tabs am-tabs-d2 am-margin" data-am-tabs="">

                        <ul class="am-avg-sm-2 am-tabs-nav am-nav am-nav-tabs">
                            <li class=""><a href="#tab1">所有评价</a></li>
                        </ul>

                        <div class="am-tabs-bd" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                            <div class="am-tab-panel am-fade am-in am-active" id="tab1">

                                <div class="comment-main">
                                    <div class="comment-list">
                                        @if(!empty($data))
                                            @foreach($data['data'] as $item)
                                                <ul class="item-list">


                                            <div class="comment-top">
                                                <div class="th th-price">
                                                    评价
                                                </div>
                                                <div class="th th-item">
                                                    商品
                                                </div>
                                            </div>
                                            <li class="td td-item">
                                                <div class="item-pic">
                                                    <a href="{{ url('/home/goodsDetail') }}/{{ $item['cargo_message']['id']}}" class="J_MakePoint">
                                                        <img src="{{ env('QINIU_DOMAIN') }}{{ $item['cargo_message']['cargo_cover'] }}?imageView2/1/w/80/h/80" class="itempic">
                                                    </a>
                                                </div>
                                            </li>

                                            <li class="td td-comment">
                                                <div class="item-title">
                                                    <div class="item-opinion">@if($item['star'] ==1)好评@elseif($item['star'] ==2)中评@else差评@endif</div>
                                                    <div class="item-name">
                                                        <a href="{{ url('/home/goodsDetail') }}/{{ $item['cargo_message']['id']}}">
                                                            <p class="item-basic-info">{{ $item['cargo_message']['cargo_name'] }}</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="item-comment">
                                                    {{ $item['comment_info'] }}
                                                </div>

                                                <div class="item-info">
                                                    <div>
                                                        @if(!empty($item['label']))
                                                            <p class="info-little">
                                                                @foreach($item['label'] as $val)
                                                                <span>{{ str_replace('选择', '', $val['label_name']) }}：{{ $val['attr_name'] }}</span>
                                                                @endforeach
                                                            </p>

                                                         @endif
                                                         <p class="info-time">{{ $item['created_at'] }}</p>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>
                                                <div style="clear: both"></div>
                                            @endforeach
                                        @else
                                            <div style="width:100%;text-align: center;padding:100px 0px;"><a href="{{ url('/home/orders/0') }}" style="color: red">还没有对商品进行评论过~,去添加一条吧!</a></div>
                                        @endif
                                    </div>
                                </div>

                            </div>
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