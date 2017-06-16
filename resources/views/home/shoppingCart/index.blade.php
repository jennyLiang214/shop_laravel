@extends('home.layouts.master')

@section('title')
    购物车
@stop

@section('coreCss')
    <link href="/AmazeUI-2.4.2/assets/css/amazeui.css" rel="stylesheet" type="text/css"/>
@stop

@section('externalCss')
    <link href="/basic/css/demo.css" rel="stylesheet" type="text/css"/>
    <link href="/css/cartstyle.css" rel="stylesheet" type="text/css"/>
    <link href="/css/optstyle.css" rel="stylesheet" type="text/css"/>
@stop

@section('coreJs')
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script src="/layer/layer.js"></script>
@stop

@section('header')
    @include('home.public.amContainer')
@stop

@section('nav')
    @include('home.public.nav')
@stop

@section('content')
    <!--购物车 -->
    <div class="concent">
        <div id="cartTable">
            <div class="cart-table-th">
                <div class="wp">
                    <div class="th th-chk">
                        <div id="J_SelectAll1" class="select-all J_SelectAll">

                        </div>
                    </div>
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
                    <div class="th th-op">
                        <div class="td-inner">操作</div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <tr class="item-list">
                <div class="bundle  bundle-last ">
                    <div class="bundle-hd">
                        <div class="bd-promos" style="padding: 10px 0px">
                        </div>
                    </div>
                    <div class="clear"></div>
                    @if(!empty($data))
                        <div class="bundle-main">
                            @foreach($data as $value)
                                <ul class="item-content clearfix">
                                    <li class="td td-chk">
                                        <div class="cart-checkbox">
                                            <input class="check" id="J_CheckBox_170769542747" name="items"
                                                   type="checkbox" checked="checked" value="{{ $value['id'] }}"/>
                                            <label for="J_CheckBox_170769542747"></label>
                                        </div>
                                    </li>
                                    <li class="td td-item">
                                        <div class="item-pic">
                                            <a href="/home/goodsDetail/{{ $value['id'] }}" target="_blank"
                                               data-title="{{ $value['cargo_name'] }}" class="J_MakePoint"
                                               data-point="tbcart.8.12">
                                                <img src="{{ env('QINIU_DOMAIN') }}{{ $value['cargo_cover'] }}?imageView2/1/w/80/h/80"
                                                     class="itempic J_ItemImg">
                                            </a>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-basic-info">
                                                <a href="/home/goodsDetail/{{ $value['id'] }}" target="_blank"
                                                   title="{{ $value['cargo_name'] }}" class="item-title J_MakePoint"
                                                   data-point="tbcart.8.11">{{ str_limit($value['cargo_name'], 70, '...') }}</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="td td-info">
                                        <div class="item-props item-props-can"
                                             style="text-align: left; padding-left: 20px;">
                                            @if(!empty($value['label']))
                                                @foreach($value['label'] as $k => $v)
                                                    <span class="sku-line">{{ str_replace('选择', '', $v['label_name']) }}
                                                        ：{{ $v['attr_name'] }}</span><br>
                                                @endforeach
                                            @endif
                                            <i class="theme-login am-icon-sort-desc"></i>
                                        </div>
                                    </li>
                                    <li class="td td-price">
                                        <div class="item-price price-promo-promo">
                                            <div class="price-content">
                                                <div class="price-line">
                                                    <em class="J_Price price-now"
                                                        tabindex="0">{{ $value['price'] }}</em>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="td td-amount">
                                        <div class="amount-wrapper ">
                                            <div class="item-amount ">
                                                <div class="sl">
                                                    <input class="min am-btn" name="" type="button" value="-"/>
                                                    <input class="text_box" name="" type="text"
                                                           value="{{ $value['shopping_number'] }}" id="inventory"
                                                           data-default-number="{{ $value['shopping_number'] }}"
                                                           style="width:30px;"/>
                                                    <input class="add am-btn" name="" type="button" value="+"/>
                                                    <div style="color:#ccc;" class="message">
                                                        @if($value['inventory'] == 0)
                                                            无货
                                                        @else
                                                            有货
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="td td-sum">
                                        <div class="td-inner">
                                            <em tabindex="0" class="J_ItemSum number"
                                                data-default-price="{{ $value['shopping_number']*$value['price'] }}">{{ $value['shopping_number']*$value['price'] }}
                                                .00</em>
                                        </div>
                                    </li>
                                    <li class="td td-op">
                                        <div class="td-inner">
                                            <a href="javascript:;" data-point-url="#"
                                               data-cargo-id="{{ $value['cargo_id'] }}"
                                               class="delete">
                                                删除</a>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 50px 0;">
                            <a href="{{ url('/') }}" style="color: red;">购物车空空的哦~，去看看心仪的商品吧~</a>
                        </div>
                    @endif
                </div>
            </tr>
        </div>
        <div class="clear"></div>
        @if(!empty($data))
            <div class="float-bar-wrapper">
                <div id="J_SelectAll2" class="select-all J_SelectAll">
                    <div class="cart-checkbox">
                        <input class="check-all check" id="J_SelectAllCbx2" checked="checked" name="select-all"
                               value="true" type="checkbox">
                        <label for="J_SelectAllCbx2"></label>
                    </div>
                    <span>全选</span>
                </div>
                <div class="operations">
                    <a href="javascript:;" hidefocus="true" class="deleteAll" id="del">删除</a>
                </div>
                <div class="float-bar-right">
                    <div class="amount-sum">
                        <span class="txt">已选商品</span>
                        <em id="J_SelectedItemsCount"></em><span class="txt">件</span>
                        <div class="arrow-box">
                            <span class="selected-items-arrow"></span>
                            <span class="arrow"></span>
                        </div>
                    </div>
                    <div class="price-sum">
                        <span class="txt">合计:</span>
                        <strong class="price">¥<em id="J_Total"></em></strong>
                    </div>
                    <div class="btn-area">
                        <a href="javascript:;" id="J_Go" class="submit-btn submit-btn-disabled"
                           aria-label="请注意如果没有选择宝贝，将无法结算" style="link:#fff} ">
                            <span>结&nbsp;算</span></a>
                    </div>
                </div>

            </div>
        @endif
        @include('home.public.footer')
    </div>

    <!--操作页面-->
    <div class="theme-popover-mask"></div>

    <div class="theme-popover">
        <div class="theme-span"></div>
        <div class="theme-poptit h-title">
            <a href="javascript:;" title="关闭" class="close">×</a>
        </div>
        <div class="theme-popbod dform">
            <form class="theme-signin" name="loginform" action="" method="post">

                <div class="theme-signin-left">

                    <li class="theme-options">
                        <div class="cart-title">颜色：</div>
                        <ul>
                            <li class="sku-line selected">12#川南玛瑙<i></i></li>
                            <li class="sku-line">10#蜜橘色+17#樱花粉<i></i></li>
                        </ul>
                    </li>
                    <li class="theme-options">
                        <div class="cart-title">包装：</div>
                        <ul>
                            <li class="sku-line selected">包装：裸装<i></i></li>
                            <li class="sku-line">两支手袋装（送彩带）<i></i></li>
                        </ul>
                    </li>
                    <div class="theme-options">
                        <div class="cart-title number">数量</div>
                        <dd>
                            <input class="min am-btn am-btn-default" name="" type="button" value="-"/>
                            <input class="text_box" name="" type="text" value="1" style="width:30px;"/>
                            <input class="add am-btn am-btn-default" name="" type="button" value="+"/>
                            <span class="tb-hidden">库存<span class="stock">1000</span>件</span>
                        </dd>

                    </div>
                    <div class="clear"></div>
                    <div class="btn-op">
                        <div class="btn am-btn am-btn-warning">确认</div>
                        <div class="btn close am-btn am-btn-warning">取消</div>
                    </div>

                </div>
                <div class="theme-signin-right">
                    <div class="img-info">
                        <img src="/images/kouhong.jpg_80x80.jpg"/>
                    </div>
                    <div class="text-info">
                        <span class="J_Price price-now">¥39.00</span>
                        <span id="Stock" class="tb-hidden">库存<span class="stock">1000</span>件</span>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @include('home.public.navCir')
@stop
@section('customJs')
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/shoppingCart_index.js') }}" type="text/javascript"></script>
@stop