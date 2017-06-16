/**
 * 货品列表
 * @author zhulinjie
 */
new Vue({
    el: ".wrapper",
    data(){
        return {
            pagination: {
                total: 0,                   // 总条数
                from: 1,                    // 当前页码第一个栏数据是数据库第几条
                to: 0,                      // 当前页码最后一栏数据是数据库第几条
                current_page: 1             // 当前页
            },
            offset: 4,                      // 页码偏移量
            cargos: [],                     // 货品列表
            per_page: 10,                   // 一页显示多少条的数据
            recommends: [],                 // 存储所有的推荐位
            recommendIds: [],               // 存储一个货品对应的推荐位的所有ID
            cargo_id: '',                   // 货品ID
            activitys: [],                  // 所有活动
            promotion_price: '',            // 促销价
            number: '',                     // 促销数量
            index: ''                       // 当前货品索引
        }
    },
    // 第一次执行
    mounted() {
        // 获取第一页数据
        this.fetchDatas(this.pagination.current_page);
    },
    // 计算属性
    computed: {
        // 选中页
        isActived() {
            return this.pagination.current_page;
        },
        // 页码
        pagesNumber() {
            // 无数据返回空
            if (!this.pagination.to) return [];
            // 获取第一个页码
            var from = this.pagination.current_page - this.offset;
            if (from < 1) from = 1;
            // 最后一个页码
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) to = this.pagination.last_page;
            // 所有页码
            var pages = [];
            for (; from <= to; from++) {
                pages.push(from);
            }
            return pages;
        },
        // 上一页省略号
        preOmit() {
            return (this.pagination.current_page - 1) > this.offset;
        },
        // 下一页省略号
        nextOmit() {
            return (this.pagination.last_page - this.pagination.current_page) > this.offset;
        },
        // 判断是存在数据
        isData(){
            return this.cargos.length;
        }
    },
    methods: {
        // 获取页码数据
        fetchDatas(page) {
            // 请求数据
            var data = {
                page: page,                 // 当前页
                perPage: this.per_page,     // 一页显示多少条的数据
                goods_id: goods_id          // 商品ID
            };
            // 请求数据
            axios.post('/admin/getCargoList', data).then(response => {
                console.log(response);
                // layer 加载层关闭
                layer.closeAll();
                // 判断请求结果
                if(response.data.ServerNo != 200){
                    sweetAlert("请求失败!", response.data.ResultData, "error");
                }
                // 响应式更新数据
                this.cargos = response.data.ResultData.data;
                this.pagination = response.data.ResultData;
            }).catch(error => {
                // layer 加载层关闭
                layer.closeAll();
                sweetAlert("请求失败!", response.request.statusText, "error");
            });
        },
        // 改变页码
        changePage(page) {
            // 防止重复点击当前页
            if (page == this.pagination.current_page) return;
            // layer 加载层
            layer.load(2);
            // 修正当前页
            this.pagination.current_page = page;
            // 执行修改
            this.fetchDatas(page);
        },
        // 更改货品状态
        updateStatus(e){
            var cargo_id = $(e.target).data('cargo_id');
            var index = $(e.target).data('index');
            var status = e.target.getAttribute('data-status');

            layer.load(2);

            axios.post('/admin/updateCargoStatus', {status: status, cargo_id: cargo_id}).then(response => {
                console.log(response);
                layer.closeAll('loading');
                // 判断请求结果
                if(response.data.ServerNo != 200){
                    sweetAlert("请求失败!", response.data.ResultData, "error");
                    return;
                }
                this.$set(this.cargos, index, response.data.ResultData);
            }).catch(error => {
                sweetAlert("请求失败!", response.request.statusText, "error");
                layer.closeAll('loading');
            });
        },
        // 选择推荐位界面，获取相关数据
        getRecommend(e){
            this.cargo_id = $(e.target).data('cid');
            this.index = $(e.target).data('index');

            axios.post('/admin/getRecommend', {cargo_id: this.cargo_id}).then(response => {
                console.log(response);
                // 判断请求结果
                if(response.data.ServerNo != 200){
                    sweetAlert("请求失败!", response.data.ResultData, "error");
                }
                this.recommends = response.data.ResultData.recommends;
                this.recommendIds = response.data.ResultData.recommendIds;
            }).catch(error => {
                sweetAlert("请求失败!", response.request.statusText, "error");
            });
        },
        // 选择推荐位操作
        selectRecommend(e){
            // 构造一个包含Form表单数据的FormData对象，需要在创建FormData对象时指定表单的元素
            var fd = new FormData($('#recommend')[0]);
            fd.append('cargo_id', this.cargo_id);

            axios.post('/admin/selectRecommend', fd).then(response => {
                console.log(response);
                if(response.data.ServerNo != 200){
                    sweetAlert("操作失败!", response.request.ResultData, "error");
                }
                this.$set(this.cargos, this.index, response.data.ResultData);
                // 隐藏模态框
                $('#myModal-1').modal('hide');
            }).catch(error => {
                sweetAlert("请求失败!", response.request.statusText, "error");
            });
        },
        // 货品推荐位 字符串形式
        recommendStr(recommends){
            if(!recommends.length){
                return '无';
            }
            var str = '';
            for(var i in recommends){
                str += recommends[i].recommend_name + ' ';
            }
            return $.trim(str);
        },
        // 获取所有活动，暂时只做秒杀活动
        getActivity(e){
            this.cargo_id = $(e.target).data('cid');
            // {cargo_id: this.cargo_id}
            axios.post('/admin/getActivity').then(response => {
                console.log(response);
                // 判断请求结果
                if(response.data.ServerNo != 200){
                    sweetAlert("请求失败!", response.data.ResultData, "error");
                }
                this.activitys = response.data.ResultData;
            }).catch(error => {
                sweetAlert("请求失败!", response.request.statusText, "error");
            });
        },
        // 做活动操作
        activity(){
            // 前端验证
            $('#activity').bootstrapValidator('validate');
            // 促销价不能为空
            if(!this.promotion_price){
                sweetAlert("操作失败!", "促销价不能为空!", "error");
                return;
            }
            // 数量不能为空
            if(!this.number){
                sweetAlert("操作失败!", "数量不能为空!", "error");
                return;
            }
            // 构造一个包含Form表单数据的FormData对象，需要在创建FormData对象时指定表单的元素
            var fd = new FormData($('#activity')[0]);
            // 做活动操作
            axios.post('/admin/cargoActivity', fd).then(response => {
                console.log(response);
                // 失败的情况
                if (response.data.ServerNo != 200) {
                    sweetAlert("操作失败!", response.data.ResultData, "error");
                    return;
                }
                // 成功的情况
                swal({
                    title: '操作成功',
                    text: response.data.ResultData,
                    type: 'success'
                }, function(isConfirm){
                    if(isConfirm){
                        // 500毫秒以后跳转到货品列表页
                        setTimeout(function () {
                            location.href="/admin/cargoList/" + goods_id;
                        }, 500);
                    }
                });
            }).catch(error => {  // 请求失败的情况
                sweetAlert("操作失败!", response.request.statusText, "error");
            });
        },
        // 判断数组中是否存在某个值
        inArray(recommendId){
            for(var i in this.recommendIds){
                if(this.recommendIds[i] == recommendId){
                    return true;
                }
            }
            return false;
        }
    }
});