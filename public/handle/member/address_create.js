var Gid  = document.getElementById ;
var showArea = function(){
    Gid('show').innerHTML = "<h3>省" + Gid('s_province').value + " - 市" +
        Gid('s_city').value + " - 县/区" +
        Gid('s_county').value + "</h3>"
}

$('#submit').click(function(){

    // 收件人判断
    if(!isNull($('#user-name').val())){
        $('#userErrorMessage').html('收货人不能为空');
        return false;
    }
    $('#userErrorMessage').html('');

    // 判断手机号码
    if(checkTel($('#user-phone'),$('#telErrorMessage')) != 100){
        return false;
    }

    // 所在收货城市判断
    if($('#s_province').val() =='省份'){
        $('#cityErrorMessage').html('请选择省份');
        return false;
    }
    if($('#s_city').val() =='地级市'){
        $('#cityErrorMessage').html('请选择城市');
        return false;
    }
    if($('#s_county').val() =='市、县级市'){
        $('#cityErrorMessage').html('请选择区县');
        return false;
    }
    $('#cityErrorMessage').html('');
    // 详细地址判断
    if(!isNull($('#user-intro').val())){
        $('#introErrorMessage').html('详细地址不能为空')
        return false;
    }
    $('#introErrorMessage').html('');

    return true;
})
