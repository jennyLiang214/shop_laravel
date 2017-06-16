/**
 * 登录函数
 * @author zhangyuchao
 */
$('.submit').click(function () {
    // 获取登录名
    var loginName = $('#loginName').val();
    // 获取密码
    var password = $('#password').val();
    // 用户名不可为空
    if (!isNull(loginName)) {
        $('#loginNameErrorMessage').html('登录名不能为空');
        return false;
    }
    // 密码不可为空
    if (!isNull(password)) {
        $('#passwordErrorMessage').html('登录密码不能为空');
        return false;
    }
    $('#loginNameErrorMessage').html('');
    $('#passwordErrorMessage').html(' ');
    var data = {
        '_token': token,
        'loginName': $('#loginName').val(),
        'password': $('#password').val()
    }
    // 提交
    sendAjax(data, loginUrl, function (response) {
        if (response.ServerNo == 200) {
            window.location.href = "/home/personal";
        } else {
            $('#message').html(response.ResultData);
        }
    })
});

