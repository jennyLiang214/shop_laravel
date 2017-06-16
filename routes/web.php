<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * 商城首页
 */
Route::get('/', function () {
    return redirect('home/index');
});

/**
 * 公共路由
 * 路由前缀 common
 * 目录 Common
 */
Route::group(['prefix' => 'common', 'namespace' => 'Common'], function () {
    // ...
});

/**
 * 前台
 * 路由前缀 home
 * 目录 Home
 */
Route::group(['prefix' => 'home', 'namespace' => 'Home'], function () {
    // 商城首页
    Route::get('index', 'IndexController@index')->name('home.index');
    // 返回注册视图
    Route::get('register', 'RegisterController@register')->name('home.register');
    // 发送手机验证码
    Route::post('register/sendMobileCode', 'RegisterController@sendMobileCode');
    // 发送邮箱验证码
    Route::post('register/sendEmailCode', 'RegisterController@sendEmailCode');
    // 用户注册
    Route::post('register/createUser', 'RegisterController@createUser');
    // 用户返回视图
    Route::get('login', 'UserController@login')->name('home.login');
    // 用户登录处理
    Route::post('doLogin', 'UserController@doLogin');
    // 商品列表页
    Route::get('goodsList/{category_id}', 'GoodsController@goodsList');
    // 商品搜索页
    Route::get('search', 'GoodsController@search');
    // 商品详情页
    Route::get('goodsDetail/{cargo_id}', 'GoodsController@goodsDetail');
    // 异步获取商品评论信息
    Route::post('goodsDetails/comments', 'GoodsController@comments');
    // 获取货品ID
    Route::post('getCargoId', 'GoodsController@getCargoId');
    // 分类
    Route::get('sort', 'GoodsController@sort')->name('home.sort');
    // 验证邮箱路由
    Route::get('safety/checkEmail', 'SafetyController@checkEmail');
    // 支付宝同步回调
    Route::get('order/aliPayCogradient', 'OrderController@aliPayCogradient');
    // 支付宝异步回调
    Route::post('order/aliPayNotify', 'OrderController@aliPayNotify');
    // 微信异步回调
    Route::post('order/wechatNotify', 'OrderController@wechatNotify');
    // 商品收藏
    Route::resource('GoodsCollection', 'GoodsCollectionController');
    Route::group(['middleware' => 'member'], function () {
        // 购物车
        Route::get('goods/shopCart', 'GoodsController@shopCart')->name('home.goods.shopCart');
        // 个人中心
        Route::get('personal', 'PersonalController@index')->name('home.personal');
        // 个人信息 视图
        Route::get('userInfo/information', 'UserInfoController@information')->name('home.userInfo.information');
        // 个人信息 数据更新
        Route::post('userInfo/updateMessage', 'UserInfoController@updateMessage')->name('home.userInfo.updateMessage');
        // 个人信息 图片上传
        Route::post('userInfo/uploadAvatar', 'UserInfoController@uploadAvatar');
        // 安全设置 首页
        Route::get('safety', 'SafetyController@index')->name('home.safety.index');
        // 安全信息 重置密码 视图
        Route::get('safety/changePassword', 'SafetyController@changePassword')->name('home.safety.changePassword');
        // 安全信息 重置密码 处理
        Route::post('safety/modifyChangePassword', 'SafetyController@modifyChangePassword')->name('home.safety.modifyChangePassword');
        // 安全信息 绑定手机 视图
        Route::get('safety/changeMobile', 'SafetyController@changeMobile')->name('home.safety.changeMobile');
        // 安全设置 绑定邮箱 视图
        Route::get('safety/changeEmail', 'SafetyController@changeEmail');
        // 安全设置 确认原账号 (手机与邮箱)
        Route::post('safety/confirmMobileCode', 'SafetyController@confirmMobileCode');
        // 安全设置 检测验证码 (手机与邮箱)
        Route::post('safety/checkVerifyCode', 'SafetyController@checkVerifyCode');
        // 安全设置 改绑账号发送验证码 (手机与邮箱)
        Route::post('safety/bindSendCode', 'SafetyController@bindSendCode');
        // 安全设置 绑定账号处理 (手机与邮箱)
        Route::post('safety/bindLoginUser', 'SafetyController@bindLoginUser');
        // 安全设置 初次绑定邮箱 跳转绑定
        Route::post('safety/bingEmail', 'SafetyController@bingEmail');
        // 安全设置 实名认证 视图
        Route::get('safety/idCard', 'SafetyController@idCard');
        // 安全设置 实名认证 处理
        Route::post('safety/handleIdCard', 'SafetyController@handleIdCard');
        // 收货地址管理
        Route::resource('address', 'AddressController');
        // 购物车 购物车列表
        Route::get('shoppingCart', 'ShoppingCartController@index');
        // 购物车 加入购物车
        Route::post('addToShoppingCart', 'ShoppingCartController@store');
        // 购物车 删除商品
        Route::post('delShoppingCart', 'ShoppingCartController@destroy');
        // 查询购物车 库存
        Route::post('checkShoppingCart', 'ShoppingCartController@checkShoppingCart');
        // 订单
        Route::resource('order', 'OrderController');
        // 订单 查询订单状态
        Route::post('order/rotation', 'OrderController@rotation');
        // 订单 用户订单列表
        Route::get('orders/{order_status}', 'OrderController@index');
        // 订单 点击收货
        Route::get('orders/receiptGoods', 'OrderController@receiptGoods');
        // 订单 去付款
        Route::post('orders/againPay', 'OrderController@againPay');

        // 商品评论
        Route::resource('comments', 'CommentsController');
        // 退出登录
        Route::get('logout', 'UserController@logout');

    });
});

/**
 * 后台
 * 路由前缀 admin
 * 目录 Admin
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    // 后台登录
    Route::get('login', 'UserController@login')->name('admin.login');
    // 后台用户登陆块
    Route::resource('user', 'UserController');

    // 认证后的操作路由
    Route::group(['middleware' => 'user:admin'], function () {

        Route::get('/', 'IndexController@index');
        // 后台首页
        Route::get('index', 'IndexController@index')->name('admin.index');
        Route::post('index/count', 'IndexController@count');

        // 后台用户管理块
        Route::resource('subscribers', 'SubscribersController');
        // 管理员重置密码
        Route::post('usersUpdate', 'AdminUserController@update');
        // 后台管理员列表
        Route::any('usersList', 'AdminUserController@userList');
        // 用户角色同步
        Route::patch('syncRoles/{id}', 'AdminUserController@syncRoles');
        // 后台用户管理
        Route::resource('users', 'AdminUserController');
        // 用户退出登陆
        Route::any('logout', 'UserController@logout')->name('admin.logout');
        // 管理员重置密码
        Route::post('usersUpdate', 'AdminUserController@update');
        // 后台用户管理
        Route::resource('subscribers', 'SubscribersController');
        // 后台用户列表
        Route::any('subscriberList', 'SubscribersController@subscriberList');
        // 重置密码
        Route::post('subscriberUpdate', 'SubscribersController@update');
        // 分类块
        Route::resource('classification', 'ClassificationController');
        // 修改分类内容
        Route::post('classificationUpdate/{id}', 'ClassificationController@update');
        // 分类列表
        Route::any('classificationList', 'ClassificationController@categoryList');
        // 添加子分类
        Route::post('classificationCreate', 'ClassificationController@categoryCreate');
        // 分类标签
        Route::resource('categoryLabel', 'CategoryLabelController');
        // 商品管理
        Route::resource('goods', 'GoodsController');
        // 获取商品列表数据
        Route::post('goodsList', 'GoodsController@goodsList');
        // 获取分类列表
        Route::post('getCategory', 'GoodsController@getCategory');
        // 获取分类下的商品标签
        Route::post('getGoodsLabel', 'GoodsController@getGoodsLabel');
        // 添加商品标签
        Route::post('addGoodsLabel', 'GoodsController@addGoodsLabel');
        // 上传商品图片
        Route::post('goodsImgUpload', 'GoodsController@goodsImgUpload');
        // 获取商品详细信息
        Route::post('getGoodsDetail', 'GoodsController@getGoodsDetail');
        // 修改商品
        Route::post('updateGoods/{id}', 'GoodsController@update');
        // 修改商品状态
        Route::post('updateGoodsStatus', 'GoodsController@updateGoodsStatus');
        // 货品管理
        Route::resource('cargo', 'CargoController');
        // 货品添加
        Route::get('addCargo/{goods_id}', 'CargoController@addCargo');
        // 获取货品的分类信息
        Route::post('cargo/detail', 'CargoController@detail');
        // 修改货品状态
        Route::resource('updateCargoStatus', 'CargoController@updateCargoStatus');
        // 添加分类标签值
        Route::post('addCategoryAttr', 'CargoController@addCategoryAttr');
        // 添加商品标签值
        Route::post('addGoodsAttr', 'CargoController@addGoodsAttr');
        // 上传货品图片
        Route::post('cargoImgUpload', 'CargoController@cargoImgUpload');
        // 货品列表界面
        Route::get('cargoList/{goods_id}', 'CargoController@cargoList');
        // 获取货品列表数据
        Route::post('getCargoList', 'CargoController@getCargoList');
        // 获取推荐位相关数据
        Route::post('getRecommend', 'CargoController@getRecommend');
        // 选择推荐位
        Route::post('selectRecommend', 'CargoController@selectRecommend');
        // 货品修改
        Route::post('updateCargo/{id}', 'CargoController@update');
        // 对货品做活动
        Route::resource('cargoActivity', 'CargoActivityController');
        // 获取所有活动，暂时只做秒杀活动
        Route::post('getActivity', 'CargoActivityController@getActivity');
        // 获取在做活动的货品列表
        Route::post('cargoActivityList', 'CargoActivityController@cargoActivityList');
        // 活动管理
        Route::resource('activity', 'ActivityController');
        // 获取活动列表
        Route::post('activityList', 'ActivityController@activityList');
        // 获取某一个活动信息
        Route::post('findActivity', 'ActivityController@findActivity');
        // 修改一个活动
        Route::post('updateActivity/{id}', 'ActivityController@update');
        // 删除一个活动
        Route::post('deleteActivity/{id}', 'ActivityController@destroy');
        // 权限块
        Route::resource('acl', 'AclController');
        // 角色列表
        Route::any('aclList', 'AclController@aclList');
        // 修改权限信息
        Route::post('aclUpdate/{id}', 'AclController@update');
        // 添加权限
        Route::get('permission', 'AclController@permissionForm')->name('permission.create');
        Route::post('permission', 'AclController@permission')->name('permission.store');
        // 更新权限
        Route::patch('syncPermission/{id}', 'AclController@syncPermissions');
        // 分类标签块
        Route::resource('categoryLabel', 'CategoryLabelController');
        // 推荐位列表
        Route::any('recommend/list', 'RecommendController@recommendList');
        // 修改推荐位信息
        Route::post('recommend/update/{id}', 'RecommendController@update');
        // 推荐位管理
        Route::resource('recommend', 'RecommendController');

        // 友情链接管理
        Route::resource('friendLink','FriendLinkController');
        //友情链接管理列表
        Route::any('linkList','FriendLinkController@linkList');
        //修改友情链接
        Route::post('linkUpdate','FriendLinkController@update');
        // 友情链接图片上传
        Route::post('linkPhoto','FriendLinkController@fileDo');

        // 订单管理
        Route::resource('order', 'OrderController');
        // 获取订单列表
        Route::post('orderList', 'OrderController@orderList');
        // 获取订单收货地址
        Route::post('orderAddress', 'OrderController@orderAddress');
        // 点击发货
        Route::post('order/sendGoods', 'OrderController@sendGoods');
        // 评论管理
        Route::resource('comments', 'CommentsController');
        // 获取评论列表
        Route::any('commentList', 'CommentsController@commentList');
        // 网站配置
        Route::resource('basicconfig', 'BasicConfigController');
        // 网站配置 更新
        Route::post('basicconfig/update/{id}', 'BasicConfigController@update');
        // 网站配置 LOGO上传
        Route::post('logoImgUpload', 'BasicConfigController@logoImgUpload');


    });
});
