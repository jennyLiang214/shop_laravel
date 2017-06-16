// JavaScript Document

$(function () {
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': csrf_token}
    });

    //商品规格选择
    $('.theme-options ul li').on('click', function () {
        if (!$(this).hasClass('selected')) {
            if (!$(this).hasClass('in-no-stock')) {
                $(this).addClass("selected").siblings("li").removeClass("selected");

                var cargo_ids = {};
                $('.theme-options ul .selected').each(function (index, elem) {
                    var label_id = $(elem).data('label');
                    var attr_id = $(elem).data('attr');
                    cargo_ids[label_id] = attr_id;
                });

                $.ajax({
                    type: 'post',
                    url: '/home/getCargoId',
                    data: cargo_ids,
                    dataType: 'json',
                    success: function (response, status, xhr) {
                        if (response.ServerNo != 200) {
                            console.log(response.ResultData);
                        } else {
                            location.href = '/home/goodsDetail/' + response.ResultData;
                        }
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            }
        }
    });

    // 秒杀倒计时
    if (typeof intDiff != 'undefined') timer(intDiff);
});


/**
 * 倒计时函数
 *
 * @param param
 */
function timer(intDiff) {
    setInterval(function () {
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
            layer.msg('活动已结束');
        }

        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;

        var html = '距离结束 <strong>';
        if (day > 0) html += day + ':';
        html += (hour < 10 ? '0' + hour : hour) + ':' + minute + ':' + second;

        $('.intDiff').html(html);

        intDiff--;
    }, 1000);
}

//弹出规格选择
$(document).ready(function () {
    var $ww = $(window).width();
    if ($ww < 1025) {
        $('.theme-login').click(function () {
            $(document.body).css("position", "fixed");
            $('.theme-popover-mask').show();
            $('.theme-popover').slideDown(200);

        });

        $('.theme-poptit .close,.btn-op .close').click(function () {
            $(document.body).css("position", "static");
            //					滚动条复位
            $('.theme-signin-left').scrollTop(0);

            $('.theme-popover-mask').hide();
            $('.theme-popover').slideUp(200);
        })
    }
});

//导航固定
$(document).ready(function () {
    var $ww = $(window).width();
    var dv = $('ul.am-tabs-nav.am-nav.am-nav-tabs'),
        st;

    if ($ww < 623) {
        var tp = $ww + 363;
        $(window).scroll(function () {
            st = Math.max(document.body.scrollTop || document.documentElement.scrollTop);
            if (st >= tp) {
                if (dv.css('position') != 'fixed') dv.css({
                    'position': 'fixed',
                    top: 53,
                    'z-index': 1000009
                });

            } else if (dv.css('position') != 'static') dv.css({
                'position': 'static'
            });
        });
        //滚动条复位（需要减去固定导航的高度）

        $('.introduceMain ul li').click(function () {
            sts = tp;
            $(document).scrollTop(sts);
        });
    } else {
        dv.attr('otop', dv.offset().top); //存储原来的距离顶部的距离
        var tp = parseInt(dv.attr('otop')) + 36;
        $(window).scroll(function () {
            st = Math.max(document.body.scrollTop || document.documentElement.scrollTop);
            if (st >= tp) {

                if (dv.css('position') != 'fixed') dv.css({
                    'position': 'fixed',
                    top: 0,
                    'z-index': 998
                });

                //滚动条复位	
                $('.introduceMain ul li').click(function () {
                    sts = tp - 35;
                    $(document).scrollTop(sts);
                });

            } else if (dv.css('position') != 'static') dv.css({
                'position': 'static'
            });
        });
    }
});


$(document).ready(function () {
    //优惠券
    $(".hot span").click(function () {
        $(".shopPromotion.gold .coupon").toggle();
    });

    //获得文本框对象
    var t = $("#text_box");
    //初始化数量为1,并失效减
    $('#min').attr('disabled', true);
    //数量增加操作
    $("#add").click(function () {
        t.val(parseInt(t.val()) + 1)
        if (parseInt(t.val()) != 1) {
            $('#min').attr('disabled', false);
        }
    });

    //数量减少操作
    $("#min").click(function () {
        t.val(parseInt(t.val()) - 1);
        if (parseInt(t.val()) == 1) {
            $('#min').attr('disabled', true);
        }
    });
});