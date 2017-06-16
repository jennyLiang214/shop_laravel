@extends('home.layouts.master')

@section('title')
    支付结果
@stop

@section('externalCss')
    <link href="/basic/css/demo.css" rel="stylesheet" type="text/css"/>
    <link href="/css/sustyle.css" rel="stylesheet" type="text/css"/>

@stop

@section('header')
    @include('home.public.header')
@stop

@section('content')

    @if(!empty($data))
    <div class="take-delivery">
        <div class="status">

            <h2>您已成功付款</h2>
            <div class="successInfo">
                <ul>
                    <li>付款金额<em>¥ {{ $data['total_fee'] }}</em></li>
                    @if(!empty($data['address']))
                    <div class="user-info">
                        <p>收货人：{{ $data['address']['consignee'] }}</p>
                        <p>联系电话：{{ $data['address']['tel'] }}</p>
                        <p>收货地址：{{ $data['address']['province'] }} {{ $data['address']['city'] }} {{ $data['address']['county'] }} {{ $data['address']['detailed_address'] }}</p>
                    </div>
                    请认真核对您的收货信息，如有错误请联系客服
                    @endif
                </ul>
                <div class="option">
                    <span class="info">您可以</span>
                    <a href="{{ url('/home/orders/0') }}" class="J_MakePoint">查看<span>已买到的宝贝</span></a>
                </div>
            </div>
        </div>
    </div>
    @else
        <div style="width:100%;color:red;text-align: center;padding:200px 0px">交易失败,请联系客服</div>
    @endif
    @include('home.public.footer')
@stop
