@extends('home.layouts.master')

@section('title')
    个人资料
@stop

@section('externalCss')
    <link href="{{ asset('/css/personal.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/orstyle.css') }}" rel="stylesheet" type="text/css">

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

                <div class="user-order">

                    <!--标题 -->
                    <div class="am-cf am-padding">
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">订单管理</strong> /
                            <small>Order</small>
                        </div>
                    </div>
                    <hr>

                    <div class="am-tabs am-tabs-d2 am-margin" data-am-tabs="">
                        <ul class="am-avg-sm-5 am-tabs-nav am-nav am-nav-tabs">
                            <li class="order_status @if(explode('/',request()->url())[5]==0) am-active @endif" data-status="0"><a href="javascript:;">所有订单</a></li>
                            <li class="order_status @if(explode('/',request()->url())[5] ==1) am-active @endif" data-status="1"><a href=javascript:;>待付款</a></li>
                            <li class="order_status @if(explode('/',request()->url())[5] ==2) am-active @endif" data-status="2"><a href="javascript:;">待发货</a></li>
                            <li class="order_status @if(explode('/',request()->url())[5] ==3) am-active @endif" data-status="3"><a href="javascript:;">待收货</a></li>
                            <li class="order_status @if(explode('/',request()->url())[5] ==4) am-active @endif" data-status="4"><a href="javascript:;">待评价</a></li>
                        </ul>

                        <div class="am-tabs-bd" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                            <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                                <div class="order-top">
                                    <div class="th th-item">
                                        商品
                                    </div>
                                    <div class="th th-price">
                                        单价
                                    </div>
                                    <div class="th th-number">
                                        数量
                                    </div>
                                    <div class="th th-operation">
                                        商品操作
                                    </div>
                                    <div class="th th-amount">
                                        合计
                                    </div>
                                    <div class="th th-status">
                                        &nbsp;
                                    </div>
                                    <div class="th th-change">
                                        交易状态
                                    </div>
                                </div>

                                <div class="order-main">
                                    <div class="order-list">
                                        @if(!empty($data))
                                        <!--交易成功-->
                                        @foreach($data as $key =>$val)
                                        <div class="order-status5">
                                            <div class="order-title">
                                                <div class="dd-num" style="max-width:400px;width:400px">订单编号：<a href="javascript:;" class="orderId" data-order-id="{{ $val['order']['id'] }}">{{ $key }}</a></div>
                                                <span>成交时间：@if(!empty($val['order'])){{$val['order']['created_at'] }}@endif</span>
                                                <!--    <em>店铺：小桔灯</em>-->
                                            </div>
                                            <div class="order-content">
                                                <div class="order-left">
                                                    @if(!empty($val['orderDetails']))
                                                    @foreach($val['orderDetails'] as $item)
                                                    <ul class="item-list" data-orderDetails-id="{{$item['id']}}">
                                                        <li class="td td-item">
                                                            <div class="item-pic">
                                                                <a href="{{ url('/home/goodsDetail') }}/{{ $item['cargo_message']['id']}}" class="J_MakePoint">
                                                                    <img src="{{ env('QINIU_DOMAIN') }}{{ $item['cargo_message']['cargo_cover'] }}?imageView2/1/w/80/h/80" class="itempic J_ItemImg">
                                                                </a>
                                                            </div>
                                                            <div class="item-info">
                                                                <div class="item-basic-info">
                                                                    <a href="{{ url('/home/goodsDetail') }}/{{ $item['cargo_message']['id']}}">
                                                                        <p>{{ $item['cargo_message']['cargo_name'] }}</p>
                                                                        @if(!empty($item['label']))
                                                                        <p class="info-little">
                                                                            @foreach($item['label'] as $k=>$v)
                                                                                {{ str_replace('选择', '', $v['label_name']) }}:{{ $v['attr_name'] }}<br>
                                                                            @endforeach
                                                                        </p>
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="td td-price">
                                                            <div class="item-price">
                                                                {{ $item['cargo_price'] }}
                                                            </div>
                                                        </li>
                                                        <li class="td td-number">
                                                            <div class="item-number">
                                                                <span>×</span>{{ $item['commodity_number'] }}
                                                            </div>
                                                        </li>

                                                        <li class="td td-operation">
                                                            <div class="item-operation">

                                                            </div>
                                                        </li>
                                                        @if($val['order']['pay_status'] ==2)
                                                        <li class="td td-operation">
                                                            <div class="item-operation">
                                                                <span>{{ $item['commodity_number']*$item['cargo_price'] }}</span>
                                                            </div>
                                                        </li>

                                                        <li class="td td-change" style="float:right;margin-top:12px">
                                                            <div class="am-btn am-btn-danger">
                                                                @if($item['order_status'] ==2)
                                                                    <p class="Mystatus orderStatus" data-status="{{$item['order_status']}}">等待发货</p>
                                                                @elseif($item['order_status'] ==3)
                                                                    <p class="Mystatus orderStatus" data-status="{{$item['order_status']}}" data-message="点击收货" onmouseover="$(this).html($(this).attr('data-message'))" onmouseout="$(this).html('等待收货')">等待收货</p>
                                                                @elseif($item['order_status'] ==4)
                                                                    <p class="Mystatus orderStatus" data-status="{{$item['order_status']}}" data-message="点击评价" onmouseover="$(this).html($(this).attr('data-message'))" onmouseout="$(this).html('等待评价')">等待评价</p>
                                                                @elseif($item['order_status'] ==5)
                                                                    <p class="Mystatus orderStatus" data-status="{{$item['order_status']}}" data-message="点击删除" onmouseover="$(this).html($(this).attr('data-message'))" onmouseout="$(this).html('交易完成')">交易完成</p>
                                                                @endif
                                                            </div>
                                                        </li>
                                                        @endif

                                                    </ul>
                                                    @endforeach
                                                    @endif

                                                </div>
                                                @if($val['order']['pay_status'] !=2)
                                                @inject('goods', 'App\Presenters\ShoppingCartPresenter')
                                                <div class="order-right">
                                                    <li class="td td-amount">
                                                        <div class="item-amount">合计:{{ $goods->totalPrice($val['orderDetails']) }}</div>
                                                    </li>
                                                    <div class="move-right">
                                                        <li class="td td-status">
                                                            <div class="item-status">
                                                                &nbsp;
                                                            </div>
                                                        </li>
                                                        <li class="td td-change">
                                                            <div class="am-btn am-btn-danger anniu">
                                                                @if($val['order']['pay_status'] ==1)
                                                                    <p class="Mystatus againPay"  onmouseover="$(this).html('&nbsp;&nbsp;去支付&nbsp;&nbsp;')"  data-pay-type="{{ $val['order']['pay_type'] }}" onmouseout="$(this).html('等待支付')">等待支付</p>
                                                                @elseif($val['order']['pay_status'] ==3)
                                                                    <p class="Mystatus">交易关闭</p>
                                                                @else
                                                                @endif
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                            @endforeach
                                            @if(!$page->isEmpty())
                                                <div class="am-pagination" style="text-align:center">
                                                    {{ $page->render() }}
                                                </div>
                                            @endif
                                        @else
                                                <div style="width:100%;text-align: center;color:red;padding:100px 0px">暂时没有新的订单呦!!!</div>
                                        @endif

                                        <!--交易失败-->
                                    </div>
                                </div>

                            </div>
                        </div>

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
    <script type="text/javascript">var token= "{{ csrf_token() }}";</script>
    <script src="{{ asset('/handle/member/orders_index.js') }}" type="text/javascript"></script>
@stop