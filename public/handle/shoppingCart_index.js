// 获取总数量元素
var totalNumberObj = $('#J_SelectedItemsCount');
// 获取总价格元素
var totalPriceObj = $('#J_Total');
// 初始化总价格
var totalPrice = 0;
// 初始化总数量
var totalNumber = 0;
// 进入页面显示总价格总数量
getData($("input[name='items']"), totalPrice, totalNumber);

// 购物车全部选中或者全部清除
$('#J_SelectAllCbx2').click(function () {
    // 重新设定初始值
    totalPrice = 0;
    totalNumber = 0;
    // 获取购物车checkbox
    var obj = $("input[name='items']");
    // 判断选中或者不选中
    if ($(this).attr('checked')) {
        // 便利购物车数据
        getData(obj, totalPrice, totalNumber);
    } else {
        // 把货品从购物车删除
        $.each(obj, function (key, value) {
            $(value).attr('checked', false);
        });
        // 把数据填充到页面
        $('#J_SelectedItemsCount').html(totalNumber);
        $('#J_Total').html(totalPrice);
    }
});

// 选定指定货品计算总价及数量
$("input[name='items']").click(function () {
    layer.load(2);
    // 获取当前选中货品数量
    var number = $(this).parents('.item-content').find('.text_box').val();
    // 获取当前选中货品的总价格
    var price = $(this).parents('.item-content').find('.J_ItemSum').html();
    // 判断复选框是否选中
    if ($(this).attr('checked')) {
        // 计算货品总数
        totalNumberObj.html(parseInt(totalNumberObj.html()) + parseInt(number));
        // 计算货品价格
        totalPriceObj.html(parseInt(totalPriceObj.html()) + parseInt(price));
    } else {
        // 计算货品总数
        totalNumberObj.html(totalNumberObj.html() - number);
        // 计算货品价格
        totalPriceObj.html(totalPriceObj.html() - parseInt(price));
    }
    layer.closeAll();
});

// 删除单条商品
$('.delete').click(function () {
    layer.load(2);
    var obj = $(this);
    // 获取货品ID
    var data = new Array(obj.attr('data-cargo-id'));
    sendAjax({'cargoId': data}, '/home/delShoppingCart', function (response) {
        if (response.ServerNo == 200) {
            if (response.ResultData == 0) {
                $('.bundle-main').html('<div style="text-align: center; padding: 50px 0;"><a href="{{ url('/') }}" style="color: red;">购物车空空的哦~，去看看心仪的商品吧~</a> </div>');
                $('.float-bar-wrapper').remove();
            } else {
                // 获取当前选中货品数量
                var number = obj.parents('.item-content').find('.text_box').val();
                // 获取当前选中货品的总价格
                var price = obj.parents('.item-content').find('.J_ItemSum').html();
                totalNumberObj.html(totalNumberObj.html() - number);
                totalPriceObj.html(totalPriceObj.html() - price);
                obj.parents('.item-content').hide();
            }
        }
    });
    layer.closeAll();
});

// 删除选中的商品
$('#del').click(function () {
    layer.load(2);
    var obj = $("input[name='items']");
    var param = new Array;
    $.each(obj, function (key, value) {
        if (value.checked) {
            param[key] = $(value).val();
        }
    });
    sendAjax({'cargoId': param}, '/home/delShoppingCart', function (response) {
        if (response.ServerNo == 200) {
            $('.bundle-main').html('<div style="text-align: center; padding: 50px 0;"><a href="{{ url('/') }}" style="color: red;">购物车空空的哦~，去看看心仪的商品吧~</a> </div>');
            $('.float-bar-wrapper').remove();
        }
    });
    layer.closeAll();
});

// 数量加加
$('.add').click(function () {
    layer.load(2);
    var obj = $(this);
    // 获取商品单价
    var price = parseInt(obj.parents('.item-content').find('.J_Price').html());

    // 获取点击后的数量进行库存查询
    var number = parseInt(obj.parents('.item-content').find('.text_box').val()) + 1;

    // 总数量
    var totalNumber = parseInt($('#J_SelectedItemsCount').html());
    var totalPrice = parseInt($('#J_Total').html());

    // 初始化查询参数
    var data = {
        cargoId: obj.parents('.item-content').find('.check').val(),
        number: number
    };

    // 新增
    sendAjax(data, '/home/checkShoppingCart', function (response) {

        if (response.ServerNo == 200) {
            var data = response.ResultData;
            // 改变信息提示
            obj.parents('.item-content').find('.message').html('有货');
            // 单价
            obj.parents('.item-content').find('.J_Price').html(data.price);
            // 金额
            obj.parents('.item-content').find('.J_ItemSum').html(data.price * number + '.00');
            // 总数量
            $('#J_SelectedItemsCount').html(totalNumber + 1);
            // 总金额
            getData($("input[name='items']"), 0, 0);
            // 超过促销数量不再享受优惠
            if (number > data.promotion_number) {
                layer.alert('购买超过' + data.promotion_number + '件时,不再享受优惠！');
            }
        } else if(response.ServerNo == 410){
            layer.alert(response.ResultData);
            // 显示原本数量
            obj.parents('.item-content').find('.text_box').val(200);
        } else if(response.ServerNo == 412){
            layer.alert('商品数量不能超过'+response.ResultData);
            // 显示原本数量
            obj.parents('.item-content').find('.text_box').val(response.ResultData);
        } else if(response.ServerNo == 414){
            layer.alert(response.ResultData);
            // 显示原本数量
            obj.parents('.item-content').find('.text_box').val(0);
        } else {
            layer.alert(response.ResultData);
            // 显示原本数量
            obj.parents('.item-content').find('.text_box').val(number - 1);
        }
        layer.closeAll('loading');
    });
});

// 数量减减
$('.min').click(function () {
    var obj = $(this);
    // 获取商品单价
    var price = parseInt(obj.parents('.item-content').find('.J_Price').html());
    // 获取数量
    var number = parseInt(obj.parents('.item-content').find('.text_box').val());
    if(number == 0){
        return;
    }

    layer.load(2);

    // 初始化查询参数
    var data = {
        cargoId: obj.parents('.item-content').find('.check').val(),
        number: number - 1
    };

    if (number > 1) {
        // 总数量
        var totalNumber = parseInt($('#J_SelectedItemsCount').html());
        // 总价格
        var totalPrice = parseInt($('#J_Total').html())
        // 删减
        sendAjax(data, '/home/checkShoppingCart', function (response) {
            if (response.ServerNo == 200) {
                var data = response.ResultData;
                // 单价
                obj.parents('.item-content').find('.J_Price').html(data.price);
                // 计算价格
                obj.parents('.item-content').find('.J_ItemSum').html(data.price * (number - 1) + '.00');
                // 计算总数量
                $('#J_SelectedItemsCount').html(totalNumber - 1);
                // 总金额
                getData($("input[name='items']"), 0, 0);
                // 超过促销数量不再享受优惠
                if ((number - 1) > data.promotion_number) {
                    layer.alert('购买超过' + data.promotion_number + '件时,不再享受优惠！');
                }
            } else {
                layer.alert(response.ResultData);
                // 显示原本数量
                obj.parents('.item-content').find('.text_box').val(number + 1);
            }
            layer.closeAll('loading');
        });
    } else {
        obj.parents('.sl').find('.text_box').val(2);
        layer.closeAll('loading');
    }
});

// 随意填写数量
$('.text_box').on('blur', function () {
    layer.load(2);
    var obj = $(this);

    // 获取点击后的数量进行库存查询
    var number = parseInt(obj.parents('.item-content').find('.text_box').val());

    // 初始化查询参数
    var data = {
        'cargoId': obj.parents('.item-content').find('.check').val(),
        'number': number,
    };

    // 查询货品数量是否充足
    sendAjax(data, '/home/checkShoppingCart', function (response) {
        console.log(response);
        if (response.ServerNo == 200) {
            var data = response.ResultData;
            // 改变信息提示
            obj.parents('.item-content').find('.message').html('有货');
            // 单价
            obj.parents('.item-content').find('.J_Price').html(data.price);
            // 计算价格
            obj.parents('.item-content').find('.J_ItemSum').html(data.price * number + '.00');
            // 总金额
            getData($("input[name='items']"), 0, 0);
            // 超过促销数量不再享受优惠
            if (number > data.promotion_number) {
                layer.alert('购买超过' + data.promotion_number + '件时,不再享受优惠！');
            }
        } else if(response.ServerNo == 410){
            layer.alert(response.ResultData);
            // 显示原本数量
            obj.parents('.item-content').find('.text_box').val(200);
        } else if(response.ServerNo == 412){
            layer.alert('商品数量不能超过'+response.ResultData);
            // 显示原本数量
            obj.parents('.item-content').find('.text_box').val(response.ResultData);
        } else if(response.ServerNo == 414){
            layer.alert(response.ResultData);
            // 显示原本数量
            obj.parents('.item-content').find('.text_box').val(0);
        } else {
            layer.alert(response.ResultData);
            // 显示原本数量
            obj.parents('.item-content').find('.text_box').val(number);
        }
        layer.closeAll('loading');
    });
});

// 页面初始化以及全部选中函数
function getData(obj, totalPrice, totalNumber) {
    // 便利购物车数据
    $.each(obj, function (key, value) {
        // 把货品全部添加到购物车
        $(value).attr('checked', 'checked');
        // 计算总价格
        totalPrice = parseInt(totalPrice) + parseInt(($(value).parents('.item-content').find('.J_ItemSum').html()));
        // 计算货品总数
        totalNumber = parseInt(totalNumber) + parseInt(($(value).parents('.item-content').find('.text_box').val()));
    });
    // 把数据填充到页面
    totalNumberObj.html(totalNumber);
    totalPriceObj.html(totalPrice);
}

// 更新购物车数据
$('#J_Go').click(function () {
    var cargo_id = '';
    var shopping_number = '';
    $.each($("input[name='items']"), function (key, value) {
        // 把货品全部添加到购物车
        if ($(value).attr('checked')) {
            shopping_number += $(value).parents('.item-content').find('.text_box').val() + ',';
            cargo_id += $(value).parents('.item-content').find('.check').val() + ',';
        }
    });

    if (!cargo_id || !shopping_number) {
        return layer.msg('没有选择宝贝，无法结算');
    }

    // 拆分数组
    location.href = '/home/order/create?cargo_id=' + cargo_id.substring(0, cargo_id.length - 1) + '&shopping_number=' + shopping_number.substring(0, shopping_number.length - 1);
});
