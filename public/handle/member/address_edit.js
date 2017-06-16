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
    layer.load(2);
    var id = $(this).attr('data-id');
    var data={
        'consignee':$('#user-name').val(),
        'tel':$('#user-phone').val(),
        'detailed_address':$('#user-intro').val(),
        '_method':'put',
        '_token':token
    }
    sendAjax(data, "/home/address/"+id, function (response) {
        if (response.ServerNo == 200) {
            window.location.href = '/home/address';
        } else {
            layer.msg(response.ResultData);
        }
    })
})