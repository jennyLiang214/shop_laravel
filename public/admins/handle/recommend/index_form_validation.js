$('.validator-form').bootstrapValidator({
    fields: {
        recommend_name: {
            validators: {
                notEmpty: {
                    message: '推荐位名称名称不能为空!'
                }
            }
        },
        recommend_introduction: {
            validators: {
                notEmpty: {
                    message: '推荐位名称名称不能为空!'
                }
            }
        }
    }
});
