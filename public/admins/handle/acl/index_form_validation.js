$('.validator-form').bootstrapValidator({
    fields: {
        display_name: {
            validators: {
                notEmpty: {
                    message: '角色名称不能为空!'
                }
            }
        },
        description: {
            validators: {
                notEmpty: {
                    message: '角色描述不能为空!'
                }
            }
        }
    }
});
// 自动开启验证
$('.validator-form').bootstrapValidator('validate');