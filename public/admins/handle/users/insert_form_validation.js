$('.userForm').bootstrapValidator({
    fields: {
        nickname: {
            validators: {
                notEmpty: {
                    message: '用户名不能为空!'
                }
            }
        },
        tel: {
            validators: {
                notEmpty: {
                    message: '手机号码不能为空!'
                },
                regexp: {
                    regexp: /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/,
                    message: '手机号码格式不正确!'
                },
            }
        },
        password: {
            validators: {
                notEmpty: {
                    message: '密码不能为空!'
                },
                stringLength: {
                    min: 6,
                    message: '密码长度不能小于6位!'
                }
            }
        },
        rel_password: {
            validators: {
                notEmpty: {
                    message: '确认不能为空!'
                },
                stringLength: {
                    min: 6,
                    message: '确认密码长度不能小于6位!'
                }
            }
        },
    }
});