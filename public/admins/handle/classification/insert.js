/**
 * 获取图标编码
 * @author Luoyan
 */
function getObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) {
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) {
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) {
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}

/**
 * 立即显示图标
 * @author Luoyan
 */
$('#img').on('change', function () {
    // 获取控件中得文件
    var files = $(this).prop('files')[0];
    // 获取当前 id
    var id = $(this).prop('id');
    // 获取图标编码
    var url = getObjectURL(files);
    // 立即显示图片
    $('#' + id + '_img').attr({'src': url});
});