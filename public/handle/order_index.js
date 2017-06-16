
    // 更改收货地址
    $('.user-addresslist').click(function () {
        layer.load(2);
        // 修改省份
        $('#holyshit268').find('.province').html($(this).find('.province').html());
        // 修改城市
        $('#holyshit268').find('.city').html($(this).find('.city').html());
        // 修改县
        $('#holyshit268').find('.dist').html($(this).find('.dist').html());
        // 修改详细地址
        $('#holyshit268').find('.street').html($(this).find('.street').html());
        // 修改收货人
        $('#holyshit268').find('.buy-user').html($(this).find('.buy-user').html());
        // 修改收货人手机号
        $('#holyshit268').find('.buy-phone').html($(this).find('.buy-phone').html());
        // 收货地址表ID
        $('#holyshit268').attr('data-address-id',$(this).attr('data-address-id'));
        layer.closeAll()
    });
// 提交订单
    $('#J_Go').click(function(){
        layer.load(2);
        // 初始化购买商品信息
        var goodsMessage =[];
        // 初始化收货地址信息
        var addressMessage;
        // 拼装商品信息
        $.each($('.item-content'),function(key,val){
            // 过滤掉没有库存的商品
            if( $(val).attr('data-number') != 0){
                // 初始化货品信息
                var cargo={};
                // 获取货品数量
                cargo.shopping_number=$(val).find('.sl').html();
                // 商品标题
                cargo.cargo_title=$(val).find('.item-title').html();
                // 获取货品ID
                cargo.cargo_id = $(val).attr('data-cargo-id');
                // 添加到商品信息
                goodsMessage.push(cargo);
            }
        });
        // 收货地址表ID
        addressMessage = $('#holyshit268').attr('data-address-id');
        // 定义支付方式
        var pay_type = $('.pay.selected').attr('data-pay-type');

        if(goodsMessage.length < 1){
            layer.msg('没有库存了,下单失败了!');
            return false;
        }
        // 拼接提交参数
        var data = {
            'goods_message':JSON.stringify(goodsMessage),
            'address_id':addressMessage,
            'pay_type':pay_type,
            '_token':token,
        };
        sendAjax(data,'/home/order',function (response) {
            if(response.ServerNo == 200){
                if(pay_type == 2){
                    location.href=response.ResultData;
                }else{
                    layer.open({
                        type: 1,
                        skin: 'layui-layer-rim', //加上边框
                        area: ['270px', '310px'], //宽高
                        content: eval(response.ResultData.QrCode)
                    });
                    $('.layui-layer-title').html('金额：'+response.ResultData.total_fee+'元');
                    getInfo(response.ResultData.out_trade_no);
                }

            } else {
                layer.msg(response.ResultData);
            }
        });

    });

    function getInfo(orderGuid) {
        var data ={'guid': orderGuid, '_token': token}
        sendAjax(data, '/home/order/rotation', function (res) {
            // 支付完成
            if(res.ServerNo == 200){
                $('#layui-layer-shade1').hide();

                location.href = "/home/order/aliPayCogradient?trade_status=TRADE_SUCCESS&total_fee="+res.ResultData.total_amount+"&body="+res.ResultData.guid
            } else if(res.ServerNo == 400){
                setTimeout(getInfo(orderGuid),1000);
            } else {
                $('.layui-layer-close1').trigger('click');
                layer.msg('下单失败了!');
            }
        });

    }


