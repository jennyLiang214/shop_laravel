/**
 * 倒计时函数
 *
 * @param o
 * @author zhangyuchao
 */
function time(o) {
    if (wait == 0) {
        o.removeAttr("disabled");
        o.html("获取验证码");
        wait = 60;
    } else {
        o.attr("disabled", true);
        o.html("重新发送(" + wait + ")");
        wait--;
        setTimeout(function () {
                time(o)
            },
            1000)
    }
}
/**
 * 替换函数
 *
 * @param str
 * @returns {string}
 * @author zhangyuchao
 */
function replaceStr(str) {
    str = str.split('')
    str.splice(3, 4, '****');
    return str.join('');
}
