<?php
/**
 * 系统组件配置
 * 注意事项
 * 1.阿里大鱼发送验证码 发送内容只可为一个变量  使用场景(注册 更换手机号码 绑定手机号码)
 * 2.sendCloud邮箱服务 发送内容只可为一个变量  使用场景(注册 更换邮箱)
 * 3.邮箱模板初次认证模板 发送内容为两个变量 认证路由与用户昵称
 */
return [

    // 阿里大鱼模板名称
    'autograph' => env('ALISMS_AUTOGRAPH'),
    // 阿里大鱼模板ID
    'template_id' => env('ALISMS_TEMPLATE_ID'),
    // sendCloud 注册模板名称
    'sendCloud_template' => env('SEND_CLOUD_TEMPLATE'),
    // sendCloud 邮箱模板初次认证
    'sendCloud_bind_email' => env('SEND_CLOUD_BIND_EMAIL')

];