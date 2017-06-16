$('#commentForm').bootstrapValidator({
    fields: {
        name: {
            validators: {
                notEmpty: {
                    message: '标示不能为空!'
                }
            }
        },
        display_name: {
            validators: {
                notEmpty: {
                    message: '名称不能为空!'
                }
            }
        }
    }
});