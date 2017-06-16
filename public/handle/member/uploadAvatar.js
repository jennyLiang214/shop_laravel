// 头像上传
$('#img').on('change', function () {
    layer.load(2);
    // 获取控件中得文件
    var obj = this;
    var formData = new FormData();
    formData.append('photo', this.files[0]);
    formData.append('_token',token);
    $.ajax({
        url: '/home/userInfo/uploadAvatar',
        type: 'post',
        data: formData,
        // 因为data值是FormData对象，不需要对数据做处理
        processData: false,
        contentType: false,
        success: function(data){
            layer.closeAll();
            if(data.ServerNo==200){
                // 成功
                $('#images').attr('src', imgUrl+data.ResultData);
            }
        }
    });

});