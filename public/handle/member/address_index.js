/**
 * 删除收货地址
 */

$('.del').click(function(){
    var obj = this;
    var id = $(this).attr('data-id');
    var data={
        '_method':'delete',
        '_token':token
    }
    sendAjax(data, "/home/address/"+id, function (response) {
        if (response.ServerNo == 200) {
            $(obj).parents('.user-addresslist').hide();
        }
    })

});
/**
 *  设为默认地址
 */
$('.default').click(function(){
    var obj = this;
    var id = $(this).attr('data-id');
    var data={
        'status':2,
        '_method':'put',
        '_token':token
    }
    sendAjax(data, "/home/address/"+id, function (response) {
        if (response.ServerNo == 200) {
            $(obj).parents('.user-addresslist').addClass('defaultAddr');
        }
    })
})