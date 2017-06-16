/**
 * 重置管理员信息
 * @author zhangyuchao
 */
new Vue({
    // 绑定元素
    el: '.updateAdminUser',
    data() {
        return {
            data:{
                'id':'',
                'password':'',
                'rel_password':'',
                '_token':token
            }
        }
    },
    // 方法定义
    methods: {
        userUpdate() {
            // 获取管理员ID
            this.data.id = userListVue.adminId;
            // 发送参数
             axios.post('/admin/usersUpdate', this.data).then(response => {
               // 判断返回结果
                if(response.data.ServerNo == 200){
                    // 信息提示
                    layer.closeAll();
                    sweetAlert('重置成功','', "success");
                }else{
                    // 添加失败信息提示
                    layer.closeAll();
                    sweetAlert('重置失败',response.data.ResultData, "error");
                }
            }).catch(error => {
                // 请求失败
                layer.closeAll();
                sweetAlert("网络请求失败!", '', "error");
             });
        }
    }
});