$('.linkList').bootstrapValidator({
    fields: {
        name: {
            validators: {
                notEmpty: {
                    message: '链接名称不能为空!'
                }
            }
        },
        // url: {
        //     validators: {
        //         notEmpty: {
        //             message: '链接地址不能为空!'
        //         },
        //         regexp: {
        //             regexp: /^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/,
        //             message: '链接地址格式不正确!'
        //         },
        //     }
        // },
    }
});