// 点击 第一次获取手机验证码
$('#codeTime').click(function () {
    layer.load(2);
    // 组装参数
    data = {
        '_token': token,
        'sendType':1 // 发送类型 1 手机 2邮箱
    }
    // 开始倒计时
    time($(this));
    // 请求发送验证码
    sendAjax(data, "/home/safety/confirmMobileCode", function (response) {
        layer.closeAll();
        if (response.ServerNo != 200) {
            return $('#errorMessage').html(response.ResultData);
        } else {
            $('#errorMessage').html('验证码已经发送!');

        }
    })
});
// 点击下一步
$('.nextStep').click(function () {
    // 验证码判断
    var res = checkVerifyCode($('#user-code'), $('#errorMessage'), 6);
    if (res != 100) {
        return false;
    }
    layer.load(2);
    // 组装数据
    data = {
        '_token': token,
        'code': $('#user-code').val(),
        'sendType': 1,
    };
    // 发送判断验证码是否正确请求
    sendAjax(data, "/home/safety/checkVerifyCode", function (response) {
        layer.closeAll();
        if (response.ServerNo != 200) {
            return $('#errorMessage').html(response.ResultData);
        } else {
            // 成功
            $('#errorMessage').html('');
            // 更换进度条
            $('#confirm-1').removeClass('step-1');
            $('#confirm-1').addClass('step-2');
            $('#confirm-2').addClass('step-1');
            $('#confirm-2').removeClass('step-2');
            // 切换界面
            $('#Step-One').hide();
            $('#Step-two').show();
        }
    })
})
// 第二次发送验证码
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
    time($(this))
    // 拼装发送第二次验证码数据
    data = {
        '_token': token,
        'sendType': 1,
        'login_name': $('#user-new-phone').val()
    }
    // 请求第二次发送验证码接口
    sendAjax(data, "/home/safety/bindSendCode", function (response) {
        if (response.ServerNo != 200) {
            layer.closeAll();
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
        '_token': $('#token').val(),
        'bindType': 1, // 绑定类型 1手机 2邮箱
        'bindStatus': 1 // 绑定状态 1修改 2从新添加
    }
    // 发送绑定手机请求
    sendAjax(data, "/home/safety/bindLoginUser", function (response) {
        layer.closeAll();
        if (response.ServerNo != 200) {
            return $('#errorMessage').html(response.ResultData);
        } else {
            $('#errorMessage').html('');
            // 完成之后，显示初始页
            $('#Step-One').show();
            $('#Step-two').hide();
            // 发送验证码 成功更换进度条 2-> 3
            $('#confirm-2').removeClass('step-1');
            $('#confirm-2').addClass('step-2');
            $('#confirm-3').addClass('step-1');
            $('#confirm-3').removeClass('step-2');
            // 替换电话
            $('#user-phone').html(replaceStr($('#user-new-phone').val()))
            // 清空验证码
            $('#user-code').val('');
        }
    })
})

