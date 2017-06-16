/**
 * 初次绑定邮箱
 */
$('#sendEmail').click(function(){
    // 检测邮箱
    if(checkEmail($('#user-new-email'),$('#errorMessage')) != 100 ){
        return false;
    }
    layer.load(2);
    // 组装参数
    var data = {
        'email':$('#user-new-email').val(),
        '_token': token,
    };
    // 请求发送邮件路由
    sendAjax(data, '/home/safety/bingEmail', function (response) {
        if (response.ServerNo != 200) {
            layer.closeAll();
            $('#errorMessage').html(response.ResultData);
        } else {
            layer.msg(response.ResultData);
            $('#errorMessage').html(response.ResultData);
        }
    })
});
