/**
 * 添加商品 Vue
 * @author jiaohuafeng
 */
new Vue({
    // 绑定元素
    el: ".config-add",
    // 响应式参数
    data(){
        return {
            goodsImgs: [1],             // 商品图片个数
            configId: 0                    // 配置ID
        }
    },
    // 第一次执行
    mounted(){

    },
    // 计算属性
    computed: {

    },
    // 方法定义
    methods: {
        // 添加网站配置操作
        addConfig(e){

             //console.log($(e.target).data('id'));
             //return false;
            // 前端验证
            $('.config-add').bootstrapValidator('validate');

            // 构造一个包含Form表单数据的FormData对象，需要在创建FormData对象时指定表单的元素
            var fd = new FormData($(e.target).parents('form')[0]);
            // 添加请求
            axios.post('/admin/basicconfig/update/'+$(e.target).data('id'), fd).then(response => {
                console.log(response);
                // 添加网站配置失败的情况
                if (response.data.ServerNo != 200) {
                    sweetAlert("操作失败!", response.data.ResultData, "error");
                    return;
                }
                // 添加网站配置成功的情况
                swal({
                    title: '操作成功',
                    text: response.data.ResultData,
                    type: 'success'
                }, function(isConfirm){
                    if(isConfirm){
                        // 500毫秒以后跳转到商品列表页
                        setTimeout(function () {
                            location.href="/admin/basicconfig";
                        }, 500);
                    }
                });
            }).catch(error => {  // 请求失败的情况
                sweetAlert("操作失败!", response.request.statusText, "error");
            });
        },

        // 添加商品图片
        addLogoImg(e){
            e.preventDefault();
            console.log(this.goodsImgs);
            this.goodsImgs.push(this.goodsImgs.length + 1);
            console.log(this.goodsImgs);
        },
        // 上传商品图片
        uploadLogoImg(e){
            // 触发文件上传
            $(e.target).next().trigger('click');
            // 上传操作
            $(e.target).next().on('change', function () {
                var obj = this;
                // 创建一个空的FormData对象
                var fd = new FormData();
                // 获取表单上传控件
                var file = this.files[0];

                // 允许上传的图片格式
                var allowType = ['image/jpeg', 'image/png', 'image/gif'];

                // 检测上传图片格式是否合法
                if($.inArray(file.type, allowType) == -1){
                    sweetAlert("操作失败!", "图片格式有误，请上传jpg、png、gif格式的图片!", "error");
                    return;
                }
                // 将上传表单控件添加到FormData对象中
                fd.append('image', file);
                // 图片上传请求
                axios.post('/admin/logoImgUpload', fd).then(response => {
                    if (response.data.ServerNo != 200) {
                        sweetAlert("操作失败!", response.data.ResultData, "error");
                        return;
                    }
                    // 接收返回的数据，即上传到七牛云后返回的图片路径名
                    var data = response.data.ResultData;
                    // 设置图片src属性，用于回显
                    $(e.target).attr('src', QINIU_DOMAIN + data + '?imageView2/1/w/350/h/350');
                    // 将返回图片路径名设置到隐藏文本框中，用于提交到数据库
                    $(e.target).nextAll('.logo').val(data);
                    // 删除事件，防止事件累加导致一次上传多个文件的问题
                    $(obj).off('change');
                    // 请求失败的情况
                }).catch(error => {
                    sweetAlert("操作失败!", response.request.statusText, "error");
                });
            });
        }
    }
});