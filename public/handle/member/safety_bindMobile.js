// 绑定手机
$('#secondSend').click(function () {
    // 重新计算倒计时
    wait = 60;
    // 验证新绑定手机号码
    var result = checkTel($('#user-new-phone'), $('#errorMessage'))
    if (result != 100) {
        return false;
    }
    layer.load(2);
    // 开始倒计时
    time($(this));
    // 拼装发送验证码数据
    data = {
        '_token': token,
        'sendType': 1,
        'login_name': $('#user-new-phone').val()
    };
    // 请求发送验证码接口
    sendAjax(data, "/home/safety/bindSendCode", function (response) {
        layer.closeAll();
        if (response.ServerNo != 200) {
            return $('#errorMessage').html(response.ResultData);
        } else {
            layer.msg('验证码已发送,注意查收呦~');
            $('#errorMessage').html('');
        }
    })
})
// 绑定新电话号码
$('#bingTel').click(function () {
    // 检测手机号
    var telRes = checkTel($('#user-new-phone'), $('#errorMessage'))
    if (telRes != 100) {
        return false;
    }
    // 检测验证码
    var codeRes = checkVerifyCode($('#user-new-code'), $('#errorMessage'), 6);
    if (codeRes != 100) {
        return false;
    }
    layer.load(2);
    // 拼装参数
    var data = {
        'loginName': $('#user-new-phone').val(),
        'newCode': $('#user-new-code').val(),
        '_token': token,
        'bindType': 1,// 绑定类型 1手机 2邮箱
        'bindStatus': 2 // 绑定状态 1修改 2从新添加
    }
    // 发送绑定手机请求
    sendAjax(data, "/home/safety/bindLoginUser", function (response) {
        layer.closeAll();
        if (response.ServerNo != 200) {
            return $('#errorMessage').html(response.ResultData);
        } else {
            window.location.reload();
        }
    })
})
