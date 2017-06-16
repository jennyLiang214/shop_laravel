// 实名认证
$('#button').click(function(){
    if(!isNull($('#user-name').val())){
        $('#errorMessage').html('真实姓名不能为空');
        return false;
    }
    // 简单验证身份证号码
    var idCardRes = checkIdCard($('#user-IDcard'),$('#errorMessage'))
    if(idCardRes != 100){
        return false;
    }
});
