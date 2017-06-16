$('.updateUserPassword').bootstrapValidator({
    fields: {
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