@extends('admin.layouts.master')
@section('title','订单管理')
@section('content')
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading" style="padding: 20px;">
                            <font><font>
                                   订单列表
                                </font></font>
                        </header>
                    </section>
                </div>
                <div class="col-lg-12">
                    <section class="panel subscriberList">
                        <header class="panel-heading" style="padding: 15px;">
                            <form class="form-horizontal tasi-form">
                                <div class="form-group">

                                    <div class="col-lg-2">
                                        <select class="form-control m-bot15" v-model="search.type">
                                            <option value="0"><font><font>全部</font></font></option>
                                            <option value="1"><font><font>待付款</font></font></option>
                                            <option value="2"><font><font>待发货</font></font></option>
                                            <option value="3"><font><font>待收货</font></font></option>
                                            <option value="4"><font><font>待评价</font></font></option>
                                            <option value="5"><font><font>已完成</font></font></option>
                                            <option value="6"><font><font>已取消</font></font></option>
                                        </select>
                                    </div>
                                    <button type="submit" @click.prevent="searchLists()" class="btn btn-info"><font><font>搜索</font></font></button>
                                </div>
                            </form>
                        </header>
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th>订单编号</th>
                                <th>购买用户</th>
                                <th>购买商品</th>
                                <th>购买数量</th>
                                <th>消费金额</th>
                                <th>订单状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data,index) in datas">
                                <td width="15%"><font><font>@{{ data.order_guid }}</font></font></td>
                                <td width="8%"><font><font>@{{ data.user_message.nickname }}</font></font></td>
                                <td width="30%"><font><font>@{{ data.cargo_message.cargo_name }}</font></font></td>
                                <td width="10%"><font><font>@{{ data.commodity_number  }}</font></font></td>
                                <td width="10%"><font><font>@{{ data.commodity_number * data.cargo_price }}元</font></font></td>
                                <td>
                                    <button v-if="data.order_status ==1" class="btn btn-danger btn-xs">待付款</button>
                                    <button v-else-if="data.order_status ==2" class="btn btn-info btn-xs status"  @click="sendGoods(data.id,index,'status')"  onmouseover="$(this).html('点击发货')" onmouseout="$(this).html('待发货')">待发货</button>
                                    <button v-else-if="data.order_status ==3" class="btn btn-warning btn-xs">待收货</button>
                                    <button v-else-if="data.order_status ==4" class="btn btn-primary btn-xs">待评价</button>
                                    <button v-else-if="data.order_status ==5" class="btn btn-success btn-xs">已完成</button>
                                    <button v-else="data.order_status ==5" class="btn btn-default btn-xs">已取消</button>
                                </td>
                                <td>
                                    <button  class="btn btn-success btn-xs"  data-toggle="modal" data-target="#address" @click="showAddress(data.order_guid)">配送地址</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- update admin password form  start -->
                        <div class="modal fade updateAdminUser" id="address" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="exampleModalLabel">收货地址</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form class="userForm">
                                            <div class="form-group">
                                                <label for="message-text" class="control-label">收货人</label>
                                                <input type="text" class="form-control user"  readonly="readonly">
                                            </div>
                                            <div class="form-group">
                                                <label for="message-text" class="control-label">联系方式</label>
                                                <input type="text" class="form-control tel"  readonly="readonly">
                                            </div>
                                            <div class="form-group">
                                                <label for="message-text" class="control-label">详细地址</label>
                                                <textarea  class="form-control address" readonly="readonly"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- update admin password form  end -->
                        <center>@include('common.page')</center>
                    </section>
                </div>
            </div>
        </section>
    </section>
@stop
@section('customJs')
    {{--<!-- 定义token -->--}}
    <script> var token = "{{ csrf_token() }}" </script>
    <script src="{{ asset('admins/handle/order/index.js') }}"></script>
@stop