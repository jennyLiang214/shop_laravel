/**
 * 判断手机号码格式
 */
$('#phone').blur(function () {
    checkTel($(this), $('#telErrorMessage'));
});
/**
 * 检测验证码位数
 */
$('#telCode').blur(function () {
    checkVerifyCode($(this), $('#telCodeErrorMessage'), 6);
});

/**
 * 验证密码
 */
$('#telPwd').blur(function () {
    checkPassword($(this), $('#telPwdErrorMessage'), 6);
});
/**
 * 验证确认密码
 */
$('#relTelPwd').blur(function () {
    checkRelPassword($('#telPwd'), $(this), $('#relTelRelPwdErrorMessage'), 6);
});

/**
 * 发送手机验证码
 * @author zhangyuchao
 */
function sendMobileCode() {
    var result = checkTel($('#phone'), $('#telErrorMessage'))
    if (result != 100) {
        return false;
    }
    time($('.dyMobileButton'));
    sendAjax({
        'tel': $('#phone').val(),
        '_token': token
    }, telVerifyCodeUrl, function (response) {
        if (response.ServerNo == 200) {
            $('#message').html('验证码已发送');
        } else {
            $('#message').html(response.ResultData);
        }
    })
}
/**
 * 使用手机号码注册
 */
function submitParamForTel() {
    if ($('.telAgree').prop('checked') != true) {
        $('#message').html('请先勾选协议')
        return false;
    }
    $('#message').html('')
    // 判断
    var telResult = checkTel($('#phone'), $('#telErrorMessage'))
    var codeResult = checkVerifyCode($('#telCode'), $('#telCodeErrorMessage'), 6);
    var pwdResult = checkPassword($('#telPwd'), $('#telPwdErrorMessage'), 6);
    var pwdRelResult = checkRelPassword($('#telPwd'), $('#relTelPwd'), $('#relTelRelPwdErrorMessage'), 6);
    if (telResult != 100 && codeResult != 100 && pwdResult != 100 && pwdRelResult != 100) {
        return false;
    }
    layer.load(2);
    var data = {
        'tel': $('#phone').val(),
        'code': $('#telCode').val(),
        'registerType': 1,
        'password': $('#telPwd').val(),
        'rel_password': $('#relTelPwd').val(),
        '_token': token,
    };

    sendAjax(data, registerUrl, function (response) {
        layer.closeAll();
        if (response.ServerNo == 200) {
            window.location.href = '/home/personal';
        } else {
            $('#message').html(response.ResultData);
        }
    })

}



/**
 * 验证邮箱
 */
$('#email').blur(function () {
    checkEmail($(this), $('#emailErrorMessage'))
})
/**
 * 验证邮箱验证码
 */
$('#emailCode').blur(function () {
    checkVerifyCode($(this), $('#emailCodeErrorMessage'), 6);
});
/**
 * 验证密码
 */
$('#emailPwd').blur(function () {
    checkPassword($(this), $('#emailPwdErrorMessage'), 6);
})
/**
 * 验证重复密码
 */
$('#relEmailPwd').blur(function () {
    checkRelPassword($('#emailPwd'), $(this), $('#relEmailPwdErrorMessage'), 6);
})
/**
 * 发送邮箱验证码
 */
$('.sendEmail').click(function(){
    var result = checkEmail($('#email'), $('#emailErrorMessage'))
    if (result != 100) {
        return false;
    }
    time($('.dyEmailButton'));
    sendAjax({
        'email': $('#email').val(),
        '_token': $('#token').val()
    }, emailVerifyCodeUrl, function (response) {
        layer.closeAll();
        if (response.ServerNo == 200) {
            $('#message').html('验证码已发送');
        } else {
            $('#message').html(response.ResultData);
        }
    })
})

/**
 * 点击注册
 */
function submitParamForEmail() {

    if ($('.emailAgree').prop('checked') != true) {
        $('#message').html('请先勾选协议')
        return false;
    }
    $('#message').html('')
    // 判断
    var telResult = checkEmail($('#email'), $('#emailErrorMessage'))
    var codeResult = checkVerifyCode($('#emailCode'), $('#emailCodeErrorMessage'), 6);
    var pwdResult = checkPassword($('#emailPwd'), $('#emailPwdErrorMessage'), 6);
    var pwdRelResult = checkRelPassword($('#emailPwd'), $('#relEmailPwd'), $('#relEmailPwdErrorMessage'), 6);
    if (telResult != 100 && codeResult != 100 && pwdResult != 100 && pwdRelResult != 100) {
        return false;
    }

    layer.load(2);
    var data = {
        'email': $('#email').val(),
        'code': $('#emailCode').val(),
        'registerType': 2,
        'password': $('#emailPwd').val(),
        'rel_password': $('#relEmailPwd').val(),
        '_token': $('#token').val(),
    };
    sendAjax(data, registerUrl, function (response) {
        layer.closeAll();
        if (response.ServerNo == 200) {
            window.location.href = '/home/personal';
        } else {
            $('#message').html(response.ResultData);
        }
    })

}
