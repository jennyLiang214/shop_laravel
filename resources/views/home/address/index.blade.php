@extends('home.layouts.master')

@section('title')
    个人资料
@stop

@section('externalCss')
    <link href="/css/personal.css" rel="stylesheet" type="text/css">
    <link href="/css/addstyle.css" rel="stylesheet" type="text/css">
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

                <div class="user-address">
                    <!--标题 -->
                    <div class="am-cf am-padding">
                        <div class="am-fl am-cf"><strong class="am-text-danger am-text-lg">地址管理</strong> /
                            <small>Address&nbsp;list</small>
                        </div>
                        <div>
                            @if(!empty(\Session::get('checkoutUrl')))
                                <a href="{{ \Session::get('checkoutUrl') }}" class="am-btn am-btn-danger" style="float:right">继续结账</a>
                            @endif
                        </div>
                    </div>
                    <hr/>
                    <ul class="am-avg-sm-1 am-avg-md-3 am-thumbnails">
                        @if(!empty($data))
                            @foreach($data as $item)
                                <li class="user-addresslist @if($item->status == 2)defaultAddr @endif">
                                    <span class="new-option-r default" data-id="{{ $item->id }}"><i class="am-icon-check-circle"></i>默认地址</span>
                                    <p class="new-tit new-p-re">
                                        <span class="new-txt">{{ $item->consignee }}</span>
                                        <span class="new-txt-rd2">{{ substr_replace($item->tel,'****',3,4) }}</span>
                                    </p>
                                    <div class="new-mu_l2a new-p-re">
                                        <p class="new-mu_l2cw">
                                            <span class="title">地址：</span>
                                            <span class="province">{{ $item->province }}</span>
                                            <span class="city">{{ $item->city }}</span>
                                            <span class="dist">{{ $item->county }}</span>
                                            <span class="street">{{ $item->detailed_address }}</span></p>
                                    </div>
                                    <div class="new-addr-btn">
                                        <a href="{{ url('/home/address') }}/{{ $item->id }}/edit"><i class="am-icon-edit"></i>编辑</a>
                                        <span class="new-addr-bar">|</span>
                                        <a href="javascript:void(0);"  data-id="{{ $item->id }}" class="del"><i class="am-icon-trash"></i>删除</a>
                                    </div>
                                </li>
                            @endforeach
                        @else
                        @endif
                    </ul>
                    <div class="clear"></div>
                    <a class="new-abtn-type" data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 0}">添加新地址</a>
                </div>

                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".new-option-r").click(function () {
                            $(this).parent('.user-addresslist').addClass("defaultAddr").siblings().removeClass("defaultAddr");
                        });

                        var $ww = $(window).width();
                        if ($ww > 640) {
                            $("#doc-modal-1").removeClass("am-modal am-modal-no-btn")
                        }

                    })
                </script>

                <div class="clear"></div>

            </div>
            <!--底部-->
            @include('home.public.footer')
        </div>

        @include('home.public.aside')
    </div>
@stop
@section('customJs')
    <script src="{{ asset('/handle/member/validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/handle/sendAjax.js') }}" type="text/javascript"></script>
    <script type="text/javascript">var token= "{{ csrf_token() }}"</script>
    <script src="{{ asset('/handle/member/address_index.js') }}" type="text/javascript"></script>
@stop