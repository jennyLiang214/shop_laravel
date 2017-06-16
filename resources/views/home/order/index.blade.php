@extends('home.layouts.master')

@section('title')
    订单支付
@stop

@section('externalCss')
    <link href="/basic/css/demo.css" rel="stylesheet" type="text/css"/>
    <link href="/css/cartstyle.css" rel="stylesheet" type="text/css"/>
    <link href="/css/jsstyle.css" rel="stylesheet" type="text/css"/>
@stop

@section('header')
    @include('home.public.header')
@stop

@section('content')
    <div class="concent">
        <!--地址 -->
        @if(!empty($data))
        <div class="paycont">
            <div class="address">
                <h3>确认收货地址 </h3>
                <div class="control selected">
                    <div class="tc-btn createAddr theme-login am-btn am-btn-danger"><a href="{{ url('/home/address/create') }}" style="color:#fff">添加新地址</a></div>
                </div>
                <div class="clear"></div>
                <ul>
                    @if(!empty($data['address']))
                        @foreach($data['address'] as $key => $val)
                            <div class="per-border"></div>
                        <li class="user-addresslist  @if($val->status == 2) defaultAddr @endif" data-address-id="{{ $val->id }}">

                            <div class="address-left">
                                <div class="user @if($val->status == 2) defaultAddr @endif">

                                            <span class="buy-address-detail">
                       <span class="buy-user">{{ $val->consignee }}</span>
                                            <span class="buy-phone">{{ $val->tel }}</span>
                                            </span>
                                </div>
                                <br>
                                <div class="@if($val->status == 2) default-address DefaultAddr @endif">
                                    <span class="buy-line-title buy-line-title-type">收货地址：</span>
                                    <span class="buy--address-detail">
                                       <span class="province">{{ $val->province }}</span>
                                            <span class="city">{{ $val->city }}</span>
                                            <span class="dist">{{ $val->county }}</span>
                                            <span class="street">{{  $val->detailed_address }}</span>
                                            </span>


                                </div>
                                @if($val->status == 2)
                                <ins class="deftip" style="background: #ee3495">默认地址</ins>
                                @endif
                            </div>
                            <div class="address-right">
                                <a href="../person/address.html">
                                    <span class="am-icon-angle-right am-icon-lg"></span></a>
                            </div>
                            <div class="clear"></div>

                            <div class="new-addr-btn">
                                <a href="{{ url('/home/address') }}/{{ $val->id }}/edit">编辑</a>
                            </div>

                        </li>
                        @endforeach
                    @endif
                </ul>

                <div class="clear"></div>
            </div>
            <!--支付方式-->
            <div class="logistics">
                <h3>选择支付方式</h3>
                <ul class="pay-list">
                    <li class="pay qq" data-pay-type="1"><img src="/images/weizhifu.jpg">微信<span></span></li>
                    <li class="pay taobao selected" data-pay-type="2"><img src="/images/zhifubao.jpg">支付宝<span></span></li>
                </ul>
            </div>
            <div class="clear"></div>

            <!--订单 -->
            <div class="concent">
                <div id="payTable">
                    <h3>确认订单信息</h3>
                    <div class="cart-table-th">
                        <div class="wp">

                            <div class="th th-item">
                                <div class="td-inner">商品信息</div>
                            </div>
                            <div class="th th-price">
                                <div class="td-inner">单价</div>
                            </div>
                            <div class="th th-amount">
                                <div class="td-inner">数量</div>
                            </div>
                            <div class="th th-sum">
                                <div class="td-inner">金额</div>
                            </div>
                            <div class="th th-oplist">
                                <div class="td-inner">优惠金额</div>
                            </div>

                        </div>
                    </div>
                    <div class="clear"></div>

                    @if(!empty($data['goods']))
                        @foreach($data['goods'] as $key => $value)
                    <div class="bundle  bundle-last">

                        <div class="bundle-main">
                            <ul class="item-content clearfix" data-cargo-id="{{ $value['id'] }}" data-goods-id ="{{ $value['goods_id'] }}"   data-number="{{ $value['inventory'] }}">
                                <div class="pay-phone">
                                    <li class="td td-item">
                                        <div class="item-pic">
                                            <a href="{{ url('/home/goodsDetail') }}/{{ $value['id'] }}" class="J_MakePoint">
                                                <img src="{{ env('QINIU_DOMAIN') }}{{ $value['cargo_cover'] }}?imageView2/1/w/80/h/80" class="itempic J_ItemImg"></a>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-basic-info">
                                                <a href="{{ url('/home/goodsDetail') }}/{{ $value['id'] }}" class="item-title J_MakePoint" data-point="tbcart.8.11">{{ $value['cargo_name'] }}</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="td td-info">
                                        <div class="item-props">
                                            @if(!empty($value['label']))
                                                @foreach($value['label'] as $v)
                                                <span class="sku-line">{{ str_replace('选择', '', $v['label_name']) }}:{{ $v['attr_name'] }}</span><br>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                    <li class="td td-price">
                                        <div class="item-price price-promo-promo">
                                            <div class="price-content">
                                                <div class="price-content">
                                                    <div class="price-line">
                                                        <em class="price-original">{{ $value['cargo_price'] }}</em>
                                                    </div>
                                                    <div class="price-line">
                                                        <em class="J_Price price-now" tabindex="0">@if(!empty($value['price'])) {{ $value['price'] }} @else {{ $value['cargo_discount'] }} @endif</em>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </div>
                                <li class="td td-amount">
                                    <div class="amount-wrapper ">
                                        <div class="item-amount ">
                                            <span class="phone-title">购买数量</span>
                                            @if($value['inventory'] == 0)
                                                <div style="color:red">已无货</div>
                                            @elseif($value['inventory'] < $value['shopping_number'] )
                                                <div class="sl"> {{ $value['inventory'] }} </div>
                                                <span style="color:red;font-size:12px">库存不足{{ $value['shopping_number'] }}件</span>
                                            @else
                                                <div class="sl">{{ $value['shopping_number'] }}</div>
                                            @endif

                                        </div>
                                    </div>
                                </li>
                                <li class="td td-sum">
                                    <div class="td-inner">
                                        <em tabindex="0" class="J_ItemSum number">{{ $value['shopping_number'] * $value['price'] }}</em>
                                    </div>
                                </li>
                                <li class="td td-oplist">
                                    <div class="td-inner">
                                        {{ $value['shopping_number'] * $value['cargo_price'] - $value['shopping_number'] * $value['price'] }}
                                    </div>
                                </li>

                            </ul>
                            <div class="clear"></div>

                        </div>

                        <div class="clear"></div>
                    </div>
                        @endforeach
                    @endif
                    <!--信息 -->
                    <div class="order-go clearfix">
                        <div class="pay-confirm clearfix">
                            <div class="box">
                                @inject('goods', 'App\Presenters\ShoppingCartPresenter')
                                <div tabindex="0" id="holyshit267" class="realPay"><em class="t">实付款：</em>
                                    <span class="price g_price ">
                                    <span>¥</span> <em class="style-large-bold-red " id="J_ActualFee">{{ $goods->totalPrice($data['goods']) }}</em>
											</span>
                                </div>
                                @if(!empty($data['address']))
                                @foreach($data['address'] as $item)
                                    @if($item->status == 2)
                                        <div id="holyshit268" class="pay-address" data-address-id="{{ $item->id }}">

                                            <p class="buy-footer-address">
                                                <span class="buy-line-title buy-line-title-type">寄送至：</span>
                                                <span class="buy--address-detail">
                                           <span class="province">{{ $item->province  }}</span>
                                                        <span class="city">{{ $item->city  }}</span>
                                                        <span class="dist">{{ $item->county  }}</span>
                                                        <span class="street">{{ $item->detailed_address  }}</span>
                                                        </span>

                                            </p>
                                            <p class="buy-footer-address">
                                                <span class="buy-line-title">收货人：</span>
                                                <span class="buy-address-detail">
                                                 <span class="buy-user">{{ $item->consignee }} </span>
                                                        <span class="buy-phone">{{ $item->tel }}</span>
                                                 </span>
                                            </p>
                                        </div>
                                     @endif
                                 @endforeach
                                @endif
                            </div>

                            <div id="holyshit269" class="submitOrder">
                                <div class="go-btn-wrap">
                                    <a id="J_Go" href="javascript:;" class="btn-go" tabindex="0" title="点击此按钮，提交订单">提交订单</a>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <div class="clear"></div>
            </div>
        </div>
        @else
            <div style="width:100%;text-align: center;color:red;padding:300px 0px">订单已经生成，去<a href="{{ url('/home/orders/0') }}" style="color:red">看看</a>吧~</div>
        @endif
        <!--底部-->
        @include('home.public.footer')
    </div>
@stop
@section('customJs')
    <script type="text/javascript" src="/js/address.js"></script>
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script type="text/javascript">var token= "{{ csrf_token() }}"</script>
    <script src="{{ asset('/handle/order_index.js') }}" type="text/javascript"></script>
@stop