// 验证旧密码
$('#user-old-password').blur(function(){
    checkOldPassword($(this), $('#oldPasswordErrorMessage'), 6);
})
// 验证新密码
$('#user-new-password').blur(function(){
    checkNewPassword($(this), $('#newPasswordErrorMessage'), 6);
})
// 验证确认密码
$('#user-confirm-password').blur(function(){
    checkRelPassword($('#user-new-password'),$(this), $('#relPasswordErrorMessage'), 6);
})
// 点击提交时验证
$('#submit').click(function(){
    var oldPassword = checkOldPassword( $('#user-old-password'), $('#oldPasswordErrorMessage'), 6);
    var newPassword = checkNewPassword($('#user-new-password'), $('#newPasswordErrorMessage'), 6);
    var relPassword = checkRelPassword($('#user-new-password'),$('#user-confirm-password'), $('#relPasswordErrorMessage'), 6);
    if(oldPassword != 100 || newPassword != 100 || relPassword != 100){
        return false;
    }

});
