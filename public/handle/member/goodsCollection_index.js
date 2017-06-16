$(document).ready(function () {
    // 移除收藏列表
    $('.del').click(function () {
        layer.load(2);
        var obj = $(this);
        var data={
            'cargo_id':$(this).attr('data-id'),
            '_token':token,
        };
        sendAjax(data,'/home/GoodsCollection',function(response){
            layer.closeAll();
            if(response.ServerNo == 300){
                layer.msg('移除成功~');
                obj.parents('.s-item-wrap').hide();
            }else{
                layer.msg('移除失败~');
            }
        })
    });

    /**
     * 添加到购物车
     */
    $('.buy').click(function(){
        layer.load(2);
        var data = {
            "cargo_id":$(this).attr('data-cargoId')
        };
        sendAjax(data,'/home/addToShoppingCart',function(response){
            layer.closeAll();
            if(response.ServerNo == 200){
                var number=parseInt($('#J_MiniCartNum').html())+parseInt(response.ResultData);
                $('#J_MiniCartNum').html(number);
                layer.msg('成功加入购物车~');
            }else if(response.ServerNo == 414){
                layer.msg(response.ResultData);
            }else{
                layer.msg('加入购物车失败了~');
            }
        })
    });
});
