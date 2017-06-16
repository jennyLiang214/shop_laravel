$('.collection').click(function () {
    layer.load(2);
    var obj = $(this);
    var data={
        'cargo_id':$(this).attr('data-cargo-id'),
        '_token':token,
        'user_id':''
    };
    sendAjax(data,'/home/GoodsCollection',function(response){
        layer.closeAll();
        if(response.ServerNo == 200){
            obj.next().html(response.ResultData);
            obj.html('已收藏');
        }else if(response.ServerNo == 300){
            obj.next().html(response.ResultData);
            obj.html('收藏');
        }else if(response.ServerNo == 401){
            layer.msg('登录之后才可以收藏,去登陆吧~');
        }else{
            layer.msg('收藏失败');
        }
    })
});
