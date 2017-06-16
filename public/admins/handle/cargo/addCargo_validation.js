$('#cargo').bootstrapValidator({
    fields: {
        cargo_name: {
            validators: {
                notEmpty: {
                    message: '货品名称不能为空!'
                }
            }
        },
        cargo_price: {
            validators: {
                notEmpty: {
                    message: '货品原价不能为空!'
                }
            }
        },
        inventory: {
            validators: {
                notEmpty: {
                    message: '库存量不能为空!'
                }
            }
        }
    }
});