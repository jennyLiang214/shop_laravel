$('#activity').bootstrapValidator({
    fields: {
        number: {
            validators: {
                notEmpty: {
                    message: '数量不能为空!'
                }
            }
        },
        promotion_price: {
            validators: {
                notEmpty: {
                    message: '促销价不能为空!'
                }
            }
        }
    }
});