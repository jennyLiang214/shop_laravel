$('#commentForm').bootstrapValidator({
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