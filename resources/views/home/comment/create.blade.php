@extends('home.layouts.master')

@section('title')
    个人资料
@stop

@section('externalCss')
    <link href="{{ asset('/css/personal.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/appstyle.css') }}" rel="stylesheet" type="text/css">

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
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">发表评论</strong> /
                            <small>Make&nbsp;Comments</small>
                        </div>
                    </div>
                    <hr>
                    @if(!empty($data))
                    <div class="comment-main">
                        <div class="comment-list">
                            <div class="item-pic">
                                <a href="#" class="J_MakePoint">
                                    <img src="{{ env('QINIU_DOMAIN') }}{{ $data['cargo']['cargo_cover'] }}?imageView2/1/w/148/h/148" class="itempic">
                                </a>
                            </div>

                            <div class="item-title">

                                <div class="item-name">
                                    <a href="#">
                                        <p class="item-basic-info">{{ $data['cargo']['cargo_name'] }}</p>
                                    </a>
                                </div>
                                <div class="item-info">
                                    @if(!empty($data['cargo']['label']))
                                    <div class="info-little">
                                        @foreach($data['cargo']['label'] as $item)
                                            <span>{{ str_replace('选择', '', $item['label_name']) }}
                                                ：{{ $item['attr_name'] }}</span><br>
                                        @endforeach
                                    </div>
                                    @endif
                                    <div class="item-price">
                                        价格：<strong>{{ $data['order']['cargo_price'] }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="item-comment">
                                <textarea id="comment" placeholder="请写下对宝贝的感受吧，对他人帮助很大哦！" style="border:1px solid #000"></textarea>
                            </div>
                            <div class="item-opinion">
                                <li><i class="op1" data-star="1"></i>好评</li>
                                <li><i class="op2" data-star="2"></i>中评</li>
                                <li><i class="op3" data-star="3"></i>差评</li>
                            </div>
                        </div>
                        <div class="info-btn">
                            <div class="am-btn am-btn-danger">发表评论</div>
                        </div>
                        <input type="hidden" name="cargo_id" id="cargo_id" value="{{ $data['order']['cargo_id'] }}">
                        <input type="hidden" name="goods_id" id="goods_id" value="{{ $data['order']['goods_id'] }}">
                        <input type="hidden" name="goods_id" id="order_id" value="{{ $data['order']['id'] }}">
                    </div>
                    @endif

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
    <script src="{{ asset('/handle/member/comment_create.js') }}" type="text/javascript"></script>
@stop