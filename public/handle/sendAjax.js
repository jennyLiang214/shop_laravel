/**
 * 发送ajax函数
 *
 * @param data
 * @param sendUrl
 * @param back
 * @author zhangyuchao
 */
function sendAjax(data, sendUrl, back) {
    $.ajax({
        type: "post",
        url: sendUrl,
        data: data,
        datatype: "json",
        success: function (response) {
            back(response)
        }
    })
}