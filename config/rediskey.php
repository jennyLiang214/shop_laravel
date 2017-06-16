<?php
/**
 * 存储 用户验证码
 * 作用: 注册验证，密码更新，绑定邮箱或手机
 *
 * KEY = STRING:USER:VERIFY:CODE::[tel|email] 手机号码或邮箱
 * VALUE = 发送给用户的验证码
 * @author zhangyuchao
 */
define('STRING_USER_VERIFY_CODE_', 'STRING:USER:VERIFY:CODE:');

/**
 * 存储货品信息
 *
 * KEY = HASH:CARGO:INFO:货品ID
 * VALUE = 货品信息
 * @author zhulinjie
 */
define('HASH_CARGO_INFO_', 'HASH:CARGO:INFO:');

/**
 * 存储购物车单条货品数据
 *
 * KEY = HASH:SHOPPING:CART:INFO:用户ID:货品ID
 * VALUE = 购物车里的货品信息
 * @author zhangyuchao
 */
define('HASH_SHOPPING_CART_INFO_','HASH:SHOPPING:CART:INFO:');

/**
 * 存储单个用户购物车信息
 *
 * KEY = LIST:SHOPPING:CART:INFO:用户ID
 * VALUE = 用户购物车单挑数据的KEY
 * @author zhangyuchao
 */
define('LIST_SHOPPING_CART_INFO_','LIST:SHOPPING:CART:INFO:');

/**
 * 存储货品与货品规格对应关系，用于商品详情点击规格获取对应的货品ID
 *
 * KEY = STRING:CARGO:STANDARD:货品规格
 * VALUE = 货品ID
 * @author zhulinjie
 */
define('STRING_CARGO_STANDARD_', 'STRING:CARGO:STANDARD:');

/**
 * 存储一场活动中的某个货品被抢购的数量
 *
 * KEY = STRING:ACTIVITY:CARGO:NUM:活动ID:货品ID
 * VALUE = 一场活动中的某个货品被抢购的数量
 * @author zhulinjie
 */
define('STRING_ACTIVITY_CARGO_NUM_', 'STRING:ACTIVITY:CARGO:NUM:');

/**
 * 存储一场活动中的某个货品被抢购的信息
 *
 * KEY = SADD:ACTIVITY:PURCHASE:INFOMATION:活动ID:货品ID
 * VALUE = 一场活动中的某个货品被抢购的信息
 * @author zhulinjie
 */
define('SADD_ACTIVITY_PURCHASE_INFOMATION_', 'SADD:ACTIVITY:PURCHASE:INFOMATION:');

