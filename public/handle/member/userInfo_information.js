
// 定义时间需要的参数
var nowTime = new Date();
var yearOption ='<option value='+0+'>请选择</option>';
var monthOption = '<option value='+0+'>请选择</option>';
var dayOption = '<option>请选择</option>';
var birthday = [];
// 判断是否已经填写过生日
if($('#birthday').val() !==1){
    var birthday = $('#birthday').val().split('-');

}
// 生成年份
for(var i=nowTime.getFullYear();i>=1930;i--){
    // 填写过年份设置为默认年
    if(i==birthday[0]){
        yearOption+='<option selected="selected" value='+i+'>'+i+'</option>';
    }else{
        yearOption+='<option value='+i+'>'+i+'</option>';
    }

}
$('#year').html(yearOption);

// 生成月份
for(var i=1;i<=12;i++){
    // 填写过月份设置成默认月份
    if(i==birthday[1]){
        monthOption+='<option selected="selected" value='+i+'>'+i+'</option>';
    }else{
        monthOption+='<option value='+i+'>'+i+'</option>';
    }

}

$('#month').html(monthOption);
// 判断是否填写过生日的某天
if(birthday.length>2){
    // 填写过设置为默认
    $('#day').html('<option selected="selected" value='+birthday[2]+'>'+birthday[2]+'</option>');
}else {
    // 未填写 设置为请选择
    $('#day').html(dayOption);
}

// 某月的天数跟随月份年份改变
$('#month').change(function(){
    if($('#year').val()== 0){
        $('#dayErrorMessage').html('请先选择年份');
        $(this).val(0)
        return false;
    }
    $('#dayErrorMessage').html('')
    // 获取某月天数
    var day = new Date($('#year').val(),$(this).val(),0).getDate();
    // 循环出select数据
    for(var i=1;i<=day;i++){

        dayOption+='<option value='+i+'>'+i+'</option>';
    }
    $('#day').html(dayOption);

});


