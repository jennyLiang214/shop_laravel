$('.validator-form').bootstrapValidator({
    fields: {
        name: {
            validators: {
                notEmpty: {
                    message: '分类名称不能为空!'
                }
            }
        },
        describe: {
            validators: {
                notEmpty: {
                    message: '描述不能为空!'
                }
            }
        }
    }
});
// 自动开启验证
$('.validator-form').bootstrapValidator('validate');