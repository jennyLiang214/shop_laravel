/**
 * 添加活动 Vue
 * @author zhulinjie
 */
new Vue({
    // 绑定元素
    el: "#main-content",
    data(){
        return {
            name: '',                       // 活动名称
            desc: '',                       // 活动描述
            type: '',                       // 活动类型
            length: ''                      // 活动时长
        }
    },
    methods: {
        addActivity(e){
            // 前端验证
            $('#activity').bootstrapValidator('validate');
            // 判断活动名称是否为空
            if(!this.name){
                sweetAlert("操作失败!", "活动名称不能为空!", "error");
                return;
            }
            // 判断活动描述是否为空
            if(!this.desc){
                sweetAlert("操作失败!", "活动描述不能为空!", "error");
                return;
            }
            // 判断是否选择了活动类型
            if(!this.type){
                sweetAlert("操作失败!", "请选择活动类型!", "error");
                return;
            }
            // 判断是否选择了开始时间
            if(!$.trim($('input[name=start_timestamp]').val())){
                sweetAlert("操作失败!", "请选择开始时间!", "error");
                return;
            }
            // 判断时长是否为空
            if(!this.length){
                sweetAlert("操作失败!", "时长不能为空!", "error");
                return;
            }
            
            // 构造一个包含Form表单数据的FormData对象，需要在创建FormData对象时指定表单的元素
            var fd = new FormData($(e.target).parents('form')[0]);
            // 添加请求
            axios.post('/admin/activity', fd).then(response => {
                console.log(response);
                // 添加活动失败的情况
                if (response.data.ServerNo != 200) {
                    sweetAlert("操作失败!", response.data.ResultData, "error");
                    return;
                }
                // 添加活动成功的情况
                swal({
                    title: '操作成功',
                    text: response.data.ResultData,
                    type: 'success'
                }, function(isConfirm){
                    if(isConfirm){
                        // 500毫秒以后跳转到活动列表页
                        setTimeout(function () {
                            location.href="/admin/activity";
                        }, 500);
                    }
                });
            }).catch(error => {  // 请求失败的情况
                sweetAlert("操作失败!", response.request.statusText, "error");
            });
        }
    }
});