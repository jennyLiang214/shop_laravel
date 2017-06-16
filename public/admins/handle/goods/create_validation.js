$('#goods').bootstrapValidator({
    fields: {
        level1: {
            validators: {
                notEmpty: {
                    message: '一级分类不能为空!'
                }
            }
        },
        level2: {
            validators: {
                notEmpty: {
                    message: '二级分类不能为空!'
                }
            }
        },
        level3: {
            validators: {
                notEmpty: {
                    message: '三级分类不能为空!'
                }
            }
        },
        goods_title: {
            validators: {
                notEmpty: {
                    message: '商品名称不能为空!'
                }
            }
        }
    }
});