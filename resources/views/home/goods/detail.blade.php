@extends('home.layouts.master')

@section('title')
    商品详情页
@stop

@section('externalCss')
    <link href="/basic/css/demo.css" rel="stylesheet" type="text/css"/>
    <link type="text/css" href="/css/optstyle.css" rel="stylesheet"/>
    <link type="text/css" href="/css/style.css" rel="stylesheet"/>
@stop

@section('customCss')
    <link type="text/css" href="/css/custom/goodsDetail.css" rel="stylesheet"/>
@stop

@section('coreJs')
    <script type="text/javascript" src="/basic/js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="/basic/js/quick_links.js"></script>
    <script src="/layer/layer.js"></script>
@stop

@section('externalJs')
    <script type="text/javascript" src="/AmazeUI-2.4.2/assets/js/amazeui.js"></script>
    <script type="text/javascript" src="/js/jquery.imagezoom.min.js"></script>
    <script type="text/javascript" src="/js/jquery.flexslider.js"></script>
    <script>
        var csrf_token = '{{ csrf_token() }}';
        @if($data['activity'] && $data['activity']->cargoActivity)
            var intDiff = parseInt('{{ $data['activity']->length * 60 - (time() - $data['activity']->start_timestamp) }}');
        @endif
    </script>
    <script type="text/javascript" src="/js/list.js"></script>
@stop

@section('header')
    @include('home.public.amContainer')
@stop

@section('nav')
    @include('home.public.nav')
@stop

@section('content')
    @inject('DetailPresenter', 'App\Presenters\HomeGoodsDetailPresenter')
    <b class="line"></b>
    <div class="listMain">

        <!--分类-->
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
        <ol class="am-breadcrumb am-breadcrumb-slash">
            @foreach($data['tree'] as $k => $v)
                @if($k == 2)
                    <li><a href="/home/goodsList/{{ $v['id'] }}">{{ $v['name'] }}</a></li>
                @else
                    <li>{{ $v['name'] }}</li>
                @endif
            @endforeach
            <li class="am-active">{{ $data['cargo']->cargo_name }}</li>
        </ol>
        <script type="text/javascript">
            $(function () {
            });
            $(window).load(function () {
                $('.flexslider').flexslider({
                    animation: "slide",
                    start: function (slider) {
                        $('body').removeClass('loading');
                    }
                });
            });
        </script>
        <div class="scoll">
            <section class="slider">
                <div class="flexslider">
                    <ul class="slides">
                        <li>
                            <img src="/images/01.jpg" title="pic"/>
                        </li>
                        <li>
                            <img src="/images/02.jpg"/>
                        </li>
                        <li>
                            <img src="/images/03.jpg"/>
                        </li>
                    </ul>
                </div>
            </section>
        </div>

        <!--放大镜-->
        <div class="item-inform">
            <div class="clearfixLeft" id="clearcontent">
                <div class="box">
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $(".jqzoom").imagezoom();
                            $("#thumblist li a").click(function () {
                                $(this).parents("li").addClass("tb-selected").siblings().removeClass("tb-selected");
                                $(".jqzoom").attr('src', $(this).find("img").attr("mid"));
                                $(".jqzoom").attr('rel', $(this).find("img").attr("big"));
                            });
                        });
                    </script>

                    <div class="tb-booth tb-pic tb-s310">
                        <a href="{{ env('QINIU_DOMAIN') }}{{ $data['cargo']->cargo_cover }}?imageView2/1/w/800/h/800">
                            <img src="{{ env('QINIU_DOMAIN') }}{{ $data['cargo']->cargo_cover }}?imageView2/1/w/350/h/350"
                                 alt="细节展示放大镜特效"
                                 rel="{{ env('QINIU_DOMAIN') }}{{ $data['cargo']->cargo_cover }}?imageView2/1/w/800/h/800"
                                 class="jqzoom"/>
                        </a>
                    </div>

                    <ul class="tb-thumb" id="thumblist">
                        @foreach(json_decode($data['cargo']->cargo_original) as $v)
                            <li class="tb-selected">
                                <div class="tb-pic tb-s40">
                                    <a href="#">
                                        <img src="{{ env('QINIU_DOMAIN') }}{{ $v }}?imageView2/1/w/60/h/60"
                                             mid="{{ env('QINIU_DOMAIN') }}{{ $v }}?imageView2/1/w/350/h/350"
                                             big="{{ env('QINIU_DOMAIN') }}{{ $v }}?imageView2/1/w/800/h/800">
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="clear"></div>
            </div>

            <div class="clearfixRight">
                <div class="tb-detail-hd" data-cargo-id="{{ $data['cargo']->id }}">
                    <h1>
                        {{ $data['cargo']->cargo_name }}
                    </h1>
                </div>
                @if($data['cargo']->cargo_status == 2)
                    <div class="tb-detail-list">
                        <!--价格-->
                        <div class="tb-detail-price">
                            @if($data['activity'] && $data['activity']->cargoActivity)
                                <li class="price iteminfo_price">
                                    <dt>秒杀价</dt>
                                    <dd><em>¥</em><b
                                                class="sys_item_price">{{ $data['cargo']->cargo_discount }}</b>
                                    </dd>
                                </li>
                                <li class="price iteminfo_mktprice">
                                    <dt>原价</dt>
                                    <dd><em>¥</em><b class="sys_item_mktprice">{{ $data['cargo']->cargo_price }}</b></dd>
                                </li>
                            @else
                                <li class="price iteminfo_price">
                                    <dt>价格</dt>
                                    <dd><em>¥</em><b class="sys_item_price">{{ $data['cargo']->cargo_discount }}</b></dd>
                                </li>
                            @endif
                            <div class="clear"></div>
                        </div>

                        <!--各种规格-->
                        <dl class="iteminfo_parameter sys_item_specpara">
                            <dt class="theme-login">
                            <div class="cart-title">可选规格<span class="am-icon-angle-right"></span></div>
                            </dt>
                            <dd>
                                <!--操作页面-->
                                <div class="theme-popover-mask"></div>

                                <div class="theme-popover">
                                    <div class="theme-span"></div>
                                    <div class="theme-poptit">
                                        <a href="javascript:;" title="关闭" class="close">×</a>
                                    </div>
                                    <div class="theme-popbod dform">
                                        <form class="theme-signin" name="loginform" action="" method="post">
                                            <div class="theme-signin-left">
                                                @foreach($data['goods']->labels as $label)
                                                    <div class="theme-options">
                                                        <div class="cart-title">{{ $label->goods_label_name }}</div>
                                                        <ul>
                                                            @foreach($DetailPresenter->filterAttr($label->attrs, $data['goods']->attrs) as $attr)
                                                                @if($DetailPresenter->selectedAttr($label->id, $attr->id, $data['cargo'], $data['cids']) == 'selected')
                                                                    <li class="sku-line selected"
                                                                        data-label="{{ $label->id }}"
                                                                        data-attr="{{ $attr->id }}">{{ $attr->goods_label_name }}
                                                                        <i></i></li>
                                                                @elseif($DetailPresenter->selectedAttr($label->id, $attr->id, $data['cargo'], $data['cids']) == 'normal')
                                                                    <li class="sku-line" data-label="{{ $label->id }}"
                                                                        data-attr="{{ $attr->id }}">{{ $attr->goods_label_name }}
                                                                        <i></i></li>
                                                                @else
                                                                    <li class="sku-line in-no-stock"
                                                                        data-label="{{ $label->id }}"
                                                                        data-attr="{{ $attr->id }}">{{ $attr->goods_label_name }}
                                                                        <i></i></li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                                <div class="theme-options">
                                                    <div class="cart-title number">数量</div>
                                                    <div>
                                                        <input id="min" class="am-btn am-btn-default" name="" type="button"
                                                               value="-"/>
                                                        <input id="text_box" name="" type="text" value="1"
                                                               style="width:30px;"/>
                                                        <input id="add" class="am-btn am-btn-default" name="" type="button"
                                                               value="+"/>
                                                    <span id="Stock" class="tb-hidden">库存<span class="stock"
                                                                                               id="cargo_num">{{ $data['cargo']->inventory }}</span>件</span>
                                                    </div>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="btn-op">
                                                    <div class="btn am-btn am-btn-warning">确认</div>
                                                    <div class="btn close am-btn am-btn-warning">取消</div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </dd>
                        </dl>
                        <div class="clear"></div>
                        <!--活动	-->
                        @if($data['activity'] && $data['activity']->cargoActivity)
                            <div class="shopPromotion gold activity">
                                <span class="intDiff">  </span>
                                <h3>京东秒杀</h3>
                            </div>
                        @else
                            <div class="shopPromotion gold">
                                <div class="hot">
                                    <dt class="tb-metatit">店铺优惠</dt>
                                    <div class="gold-list">
                                        <p>购物满2件打8折，满3件7折<span>点击领券<i class="am-icon-sort-down"></i></span></p>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="coupon">
                                    <dt class="tb-metatit">优惠券</dt>
                                    <div class="gold-list">
                                        <ul>
                                            <li>125减5</li>
                                            <li>198减10</li>
                                            <li>298减20</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="pay">
                        <div class="pay-opt">
                            <a href="home.html"><span class="am-icon-home am-icon-fw">首页</span></a>
                            <a><span class="am-icon-heart am-icon-fw">收藏</span></a>

                        </div>
                        @if($data['activity'] && $data['activity']->cargoActivity)
                            <li>
                                @if($data['activity']->cargoActivity->number > 0)
                                    <a class="am-btn am-btn-danger" id="toSnapUp" title="立即抢购" href="javascript:;"
                                       data-cargoId="{{ $data['cargo']->id }}"><i></i>立即抢购</a>
                                @else
                                    <a class="am-btn am-btn-default" id="toSnapUp" title="已抢光" href="javascript:;"
                                       data-cargoId="{{ $data['cargo']->id }}"><i></i>已抢光</a>
                                @endif
                            </li>
                        @else
                            <li>
                                <div class="clearfix tb-btn tb-btn-buy theme-login">
                                    <a id="LikBuy" title="点此按钮到下一步确认购买信息" href="javascript:;"
                                       data-cargoId="{{ $data['cargo']->id }}">立即购买</a>
                                </div>
                            </li>
                            <li>
                                <div class="clearfix tb-btn tb-btn-basket theme-login">
                                    <a id="LikBasket" title="加入购物车" href="javascript:;"
                                       data-cargoId="{{ $data['cargo']->id }}"><i></i>加入购物车</a>
                                </div>
                            </li>
                        @endif
                    </div>
                @else
                    该商品已下架
                @endif
            </div>
            <div class="clear"></div>
        </div>

        <!--优惠套装-->
        <div class="match">
        </div>
        <div class="clear"></div>


        <!-- introduce-->
        <div class="introduce">
            <div class="browse">
                <div class="mc">
                    <ul>
                        <div class="mt">
                            <h2>看了又看</h2>
                        </div>

                        <li class="first">
                            <div class="p-img">
                                <a href="#"> <img class="" src="/images/browse1.jpg"> </a>
                            </div>
                            <div class="p-name"><a href="#">
                                    【三只松鼠_开口松子】零食坚果特产炒货东北红松子原味
                                </a>
                            </div>
                            <div class="p-price"><strong>￥35.90</strong></div>
                        </li>
                        <li>
                            <div class="p-img">
                                <a href="#"> <img class="" src="/images/browse1.jpg"> </a>
                            </div>
                            <div class="p-name"><a href="#">
                                    【三只松鼠_开口松子】零食坚果特产炒货东北红松子原味
                                </a>
                            </div>
                            <div class="p-price"><strong>￥35.90</strong></div>
                        </li>
                        <li>
                            <div class="p-img">
                                <a href="#"> <img class="" src="/images/browse1.jpg"> </a>
                            </div>
                            <div class="p-name"><a href="#">
                                    【三只松鼠_开口松子】零食坚果特产炒货东北红松子原味
                                </a>
                            </div>
                            <div class="p-price"><strong>￥35.90</strong></div>
                        </li>
                        <li>
                            <div class="p-img">
                                <a href="#"> <img class="" src="/images/browse1.jpg"> </a>
                            </div>
                            <div class="p-name"><a href="#">
                                    【三只松鼠_开口松子】零食坚果特产炒货东北红松子原味
                                </a>
                            </div>
                            <div class="p-price"><strong>￥35.90</strong></div>
                        </li>
                        <li>
                            <div class="p-img">
                                <a href="#"> <img class="" src="/images/browse1.jpg"> </a>
                            </div>
                            <div class="p-name"><a href="#">
                                    【三只松鼠_开口松子218g】零食坚果特产炒货东北红松子原味
                                </a>
                            </div>
                            <div class="p-price"><strong>￥35.90</strong></div>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="introduceMain">
                <div class="am-tabs" data-am-tabs>
                    <ul class="am-avg-sm-3 am-tabs-nav am-nav am-nav-tabs">
                        <li class="am-active">
                            <a href="#">

                                <span class="index-needs-dt-txt">宝贝详情</span></a>

                        </li>

                        <li>
                            <a href="#">

                                <span class="index-needs-dt-txt">全部评价</span></a>

                        </li>
                    </ul>

                    <div class="am-tabs-bd">

                        <div class="am-tab-panel am-fade am-in am-active">
                            <div class="J_Brand">

                                <div class="attr-list-hd tm-clear">
                                    <h4>产品参数：</h4></div>
                                <div class="clear"></div>
                                <ul id="J_AttrUL">
                                    <li title="">产品类型:&nbsp;烘炒类</li>
                                    <li title="">原料产地:&nbsp;巴基斯坦</li>
                                    <li title="">产地:&nbsp;湖北省武汉市</li>
                                    <li title="">配料表:&nbsp;进口松子、食用盐</li>
                                    <li title="">产品规格:&nbsp;210g</li>
                                    <li title="">保质期:&nbsp;180天</li>
                                    <li title="">产品标准号:&nbsp;GB/T 22165</li>
                                    <li title="">生产许可证编号：&nbsp;QS4201 1801 0226</li>
                                    <li title="">储存方法：&nbsp;请放置于常温、阴凉、通风、干燥处保存</li>
                                    <li title="">食用方法：&nbsp;开袋去壳即食</li>
                                </ul>
                                <div class="clear"></div>
                            </div>

                            <div class="details">
                                <div class="attr-list-hd after-market-hd">
                                    <h4>商品细节</h4>
                                </div>
                                <div class="twlistNews">
                                    {!! $data['cargo']->cargo_info !!}
                                </div>
                            </div>
                            <div class="clear"></div>

                        </div>

                        <div class="am-tab-panel am-fade">
                            <div class="clear"></div>
                            <div class="tb-r-filter-bar">
                                <ul class=" tb-taglist am-avg-sm-4">
                                    <li class="tb-taglist-li tb-taglist-li-current">
                                        <div class="comment-info" style="cursor:pointer" data-type="0">
                                            <span>全部评价</span>
                                            <span class="tb-tbcr-num">{{ $data['star']['good']+$data['star']['almost'] +$data['star']['bad'] }}</span>
                                        </div>
                                    </li>

                                    <li class="tb-taglist-li tb-taglist-li-1">
                                        <div class="comment-info" style="cursor:pointer" data-type="1">
                                            <span>好评</span>
                                            <span class="tb-tbcr-num">{{ $data['star']['good'] }}</span>
                                        </div>
                                    </li>

                                    <li class="tb-taglist-li tb-taglist-li-0">
                                        <div class="comment-info" style="cursor:pointer" data-type="2">
                                            <span>中评</span>
                                            <span class="tb-tbcr-num">{{ $data['star']['almost'] }}</span>
                                        </div>
                                    </li>

                                    <li class="tb-taglist-li tb-taglist-li--1">
                                        <div class="comment-info" style="cursor:pointer" data-type="3">
                                            <span>差评</span>
                                            <span class="tb-tbcr-num">{{ $data['star']['bad'] }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="clear"></div>

                            <ul class="am-comments-list am-comments-list-flip">


                            </ul>

                            <div class="clear"></div>

                            <!--分页 -->

                            <div class="clear"></div>

                            <div style="width:100%;padding:50px 0px" id="moreButton">
                                <button type="button" id="commentMore">加载更多</button>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="clear"></div>
                @include('home.public.footer')
            </div>

        </div>
    </div>

    @include('home.public.tip')

@stop
@section('customJs')
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var token= '{{ csrf_token() }}';

        @if(!empty($data['activity']) && !empty($data['activity']->cargoActivity))
            var activity = true;
            var activity_id = '{{ $data['activity']->id }}';
        @else
            var activity = false;
        @endif

        var user = '{{ Session::has('user') }}';
        var cargo_id = '{{ $data["cargo"]->id }}';
        var QINIU_DOMAIN = "{{ env('QINIU_DOMAIN') }}";
        var images = "{{ asset('/images/hwbn40x40.jpg') }}";
    </script>
    <script src="{{ asset('/handle/goods_detail.js') }}" type="text/javascript"></script>
@stop
