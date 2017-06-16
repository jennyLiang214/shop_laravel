$(document).ready(function () {
    /**
     * 选择评价
     */
    $(".comment-list .item-opinion li").click(function () {
        $(this).prevAll().children('i').removeClass("active");
        $(this).nextAll().children('i').removeClass("active");
        $(this).children('i').addClass("active");

    });

    /**
     * 填写评论
     */
    $('.am-btn').click(function(){

        var  star = $('.item-opinion').find('.active').attr('data-star');
        if(!star){
            return layer.msg('评价不能为空');
        }
        var content = $('#comment').val();
        if(!star){
            return layer.msg('评价不能为空');
        }
        if(!content){
            return layer.msg('评论不能为空');
        }
        if(content.replace(/[\u0391-\uFFE5]/g,"aaa").length < 15){
            return layer.msg('评论内容不小于5个汉字');
        }
        var data = {
            'star':star,
            'comment_info':content,
            'comment_type':0,
            'cargo_id':$("#cargo_id").val(),
            'goods_id':$('#goods_id').val(),
            'order_id':$('#order_id').val(),
            '_token':token
        };
        sendAjax(data,'/home/comments',function(response){
            if(response.ServerNo == 200){
                layer.msg('评论成功!');
                location.href="/home/comments";
            }else{
                layer.msg(response.ResultData);
            }
        })
    });
});


