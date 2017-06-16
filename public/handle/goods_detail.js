$(function () {
    // 评论内容模板
    var commentContent = '';

    // 评论内容
    var content = '';

    // 总页数
    var totalPage = '';

    // 初始化当前页
    var page = 1;

    // 初始化 传输数据
    var data = {
        page: page,
        _token: token,
        cargo_id: cargo_id,
        star: ''
    };
    
    // 立即抢购
    if (activity) {
        $('#toSnapUp').on('click', function () {
            // 判断是否登录，没有登录需要先登录
            if (!user) {
                location.href = '/home/login';
            } else {
                var data = {
                    cargo_id: cargo_id,
                    activity_id: activity_id,
                    number: $('#text_box').val()
                };
            }
            sendAjax(data, '/home/addToShoppingCart', function (response) {
                if (response.ServerNo == 414) {
                    layer.alert(response.ResultData);
                    return;
                }

                if (response.ServerNo != 200) {
                    layer.alert(response.ResultData);
                    return;
                }
                location.href = '/home/shoppingCart';
            });
        });
    }

    // 立即购买
    $('#LikBuy').click(function () {
        // 判断是否登录，没有登录需要先登录
        if (!user) {
            location.href = '/home/login';
        }

        var data = {
            cargo_id: cargo_id,
            number: $('#text_box').val()
        };

        sendAjax(data, '/home/addToShoppingCart', function (response) {
            if (response.ServerNo == 414) {
                layer.alert(response.ResultData);
                return;
            }

            if (response.ServerNo != 200) {
                layer.alert(response.ResultData);
                return;
            }
            location.href = '/home/shoppingCart';
        });
    });

    // 加入购物车
    $('#LikBasket').click(function () {
        // 判断是否登录，没有登录需要先登录
        if (!user) {
            location.href = '/home/login';
        }

        var data = {
            cargo_id: cargo_id,
            number: $('#text_box').val()
        };

        sendAjax(data, '/home/addToShoppingCart', function (response) {
            if (response.ServerNo == 414) {
                layer.alert(response.ResultData);
                return;
            }

            if (response.ServerNo != 200) {
                layer.alert(response.ResultData);
                return;
            }
            var number = parseInt($('#J_MiniCartNum').html()) + parseInt(response.ResultData);
            $('#J_MiniCartNum').html(number);
            $('.cart_num').html(number);
        });
    });

    // 第一次加载数据
    $(function () {
        getComment(data);
    });

    // 加载更过获取下一页数据
    $('#commentMore').click(function () {
        layer.load(2);
        if (page >= totalPage) {
            layer.closeAll();
            return layer.msg('没有更多评论了');

        }
        page = page + 1;
        data.page = page;
        getComment(data);
        layer.closeAll();

    });

    // 按评价搜索
    $('.comment-info').click(function () {
        layer.load(2);
        page = 1;
        content = '';
        data.star = $(this).attr('data-type');
        data.page = page;
        getComment(data);
        layer.closeAll();
    });

    // 加载数据函数
    function getComment(data) {
        sendAjax(data, '/home/goodsDetails/comments', function (response) {
            $('#moreButton').show();
            if (response.ServerNo == 200) {
                if (response.ResultData.data.length >= 1) {
                    $.each(response.ResultData.data, function (k, v) {
                        content += fillData(v);
                    });
                    $('.am-comments-list').html(content);
                    totalPage = response.ResultData.totalPage;
                } else {
                    $('#moreButton').hide();
                    $('.am-comments-list').html('<div style="padding:50px 0px;font-size:16px;color:red">暂无评价内容</divstyle>');
                }

            } else {
                $('.tb-taglist').hide();
                $('#moreButton').hide();
                $('.am-comments-list').html('<div style="padding:50px 0px;font-size:16px;color:red">暂无评价内容</divstyle>');

            }
        });
    }

    // 组装页面模板
    function fillData(v) {
        var avatar = '';
        if (!v.user.avatar) {
            avatar = images;
        } else {
            avatar = QINIU_DOMAIN + v.user.avatar;
        }
        commentContent += '<li class="am-comment">';
        commentContent += '<a href="">';
        commentContent += '<img class="am-comment-avatar" src="' + avatar + '"/>';
        commentContent += '</a>';
        commentContent += '<div class="am-comment-main">';
        commentContent += '<header class="am-comment-hd">';
        commentContent += '<div class="am-comment-meta">';
        commentContent += '<a href="#link-to-user" class="am-comment-author">' + v.user.nickname + '</a>';
        commentContent += '<time datetime="">' + v.created_at + '</time>';
        commentContent += '</div>';
        commentContent += '</header>';
        commentContent += '<div class="am-comment-bd">';
        commentContent += '<div class="tb-rev-item">';
        commentContent += '<div class="J_TbcRate_ReviewContent tb-tbcr-content ">';
        commentContent += v.comment_info
        commentContent += '</div>';
        commentContent += '</div>';
        commentContent += '</div>';
        commentContent += '</div>';
        commentContent += '</li>';
        var tmp = commentContent;
        commentContent = '';
        return tmp;
    }
});