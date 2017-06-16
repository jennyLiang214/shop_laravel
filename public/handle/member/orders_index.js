$(document).ready(function () {
    $('.order_status').click(function(){
        location.href="/home/orders/"+$(this).attr('data-status');
    });
    var data = {
        '_token':token,
        'order_status':'',
        'orderDetailsId':'',
        '_method':''
    };
    $('.orderStatus').click(function(){

        var obj = this;
        var status = $(this).attr('data-status');
        if(status ==2) {
            layer.msg('商家暂未发货');
            return false;
        }

        var orderDetailsId =$(this).parents('.item-list').attr('data-orderDetails-id');
        if(status==3){
            data.order_status=4;
            data._method='PUT';
            sendAjax(data,'/home/order/'+orderDetailsId,function(response){
                if(response.ServerNo == 200){
                    layer.msg('确认成功');
                    $(obj).parents('.item-list').hide();
                }else{
                    layer.msg('操作失败');
                }
            });
        }else if(status ==4){
            location.href="/home/comments/create?orderDetailsId="+orderDetailsId;
        }else if(status == 5){
            data.order_status=status;
            data._method='delete';
            sendAjax(data,'/home/order/'+orderDetailsId,function(response){
                if(response.ServerNo == 200){
                    layer.msg('删除成功');
                    $(obj).parents('.item-list').hide();
                }else{
                    layer.msg('删除失败');
                }
            });
        }else {

        }

    });



    // 再次支付
    $('.againPay').click(function(){
        var pay_type= $(this).attr('data-pay-type');
        var order_id = $(this).parents('.order-status5').find('.orderId').attr('data-order-id');
        var data = {
            'pay_type':pay_type,
            'order_id':order_id,
            '_token':token
        };

        sendAjax(data,'/home/orders/againPay',function (response) {
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
    // 轮询
    function getInfo(orderGuid) {
        var data ={'guid': orderGuid, '_token': token};
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

});