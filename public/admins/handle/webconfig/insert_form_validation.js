$('.config-add').bootstrapValidator({
    fields: {
        site_name: {
            validators: {
                notEmpty: {
                    message: '网站名称不能为空!'
                }
            }
        },
        site_describe: {
            validators: {
                notEmpty: {
                    message: '网站描述不能为空!'
                }
            }
        },
        telephone: {
            validators: {
                notEmpty: {
                    message: '400电话不能为空!'
                }
            }
        },
        logo: {
            validators: {
                notEmpty: {
                    message: '网站logo不能为空!'
                }
            }
        },
        level_set: {
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '分类层级不能为空'
                }
            }
        },
        record_number: {
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '网站备案号不能为空'
                }
            }
        },
        address: {
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '公司地址不能为空'
                }
            }
        },
        copyright: {
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '版权信息不能为空'
                }
            }
        }
    }
});