$(function () {
    if (typeof intDiff != 'undefined') {
        timer(intDiff);
    }
});

/**
 * 倒计时函数
 *
 * @param param
 */
function timer(param) {
    var intDiff = Math.abs(param);
    window.mTimer = setInterval(function () {
        var day = 0,
            hour = 0,
            minute = 0,
            second = 0; //时间默认值

        if (intDiff > 0) {
            day = Math.floor(intDiff / (60 * 60 * 24));
            hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
            minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
        }else{
            if(param < 0){
                clearInterval(window.mTimer);
                timer(activityLength);
            }else{
                clearInterval(window.mTimer);
                layer.confirm('本场秒杀活动结束啦，看看最新的秒杀吧~', {
                    btn: ['查看']
                }, function () {
                    location.href = '/home/index';
                });
            }
        }

        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;

        if (param < 0) {
            var html = '当前场次 <strong>';
            if (day > 0) html += (day < 10 ? '0' + day : day) + ':';
            html += (hour < 10 ? '0' + hour : hour) + ':' + minute + ':' + second + '</strong> 后开抢';
        } else {
            var html = '当前场次 <strong>';
            if (day > 0) html += day + ':';
            html += (hour < 10 ? '0' + hour : hour) + ':' + minute + ':' + second + '</strong> 结束抢购';
        }

        $('.intDiff').html(html);
        intDiff--;
    }, 1000);
}