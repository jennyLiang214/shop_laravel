$('#activity').bootstrapValidator({
    fields: {
        name: {
            validators: {
                notEmpty: {
                    message: '活动名称不能为空!'
                }
            }
        },
        desc: {
            validators: {
                notEmpty: {
                    message: '活动描述不能为空!'
                }
            }
        },
        type: {
            validators: {
                notEmpty: {
                    message: '请选择活动类型!'
                }
            }
        },
        length: {
            validators: {
                notEmpty: {
                    message: '时长不能为空!'
                },
                regexp: {
                    regexp: /^\d+$/,
                    message: '时长只能是数字'
                }
            }
        }
    }
});