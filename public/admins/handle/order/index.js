/**
 * 分页 Vue
 * @author Luoyan
 */
new Vue({
    // 绑定元素
    el: '.wrapper',
    // 响应式参数
    data() {
        return {
            // 分页变量数据
            pagination: {
                total: 0, // 总条数
                from: 1, // 当前页码第一个栏数据是数据库第几条
                to: 0, // 当前页码最后一栏数据是数据库第几条
                current_page: 1 // 当前页
            },
            offset: 4, // 页码偏移量
            datas: [], // 页码内容
            search: {type: 0}, // 搜索条件
            orderGuid:'',
            per_page: 20, // 一页显示的数据
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
        }

    },
    // 方法定义
    methods: {
        // 获取页码数据
        fetchDatas(page) {
            // 请求数据
            var data = {
                page: page, // 当前页
                where: this.search, // 搜索条件
                perPage: this.per_page, // 页面展示的数据
            };
            // 请求数据
            axios.post('/admin/orderList', data).then(response => {
                // layer 加载层关闭
                layer.closeAll();
                // 响应式更新数据
                this.datas = response.data.ResultData.data;
                this.pagination = response.data.ResultData;
            }).catch(error => {
                // layer 加载层关闭
                layer.closeAll();
                sweetAlert("请求失败!", "订单列表数据获取失败!", "error");
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
        // 搜索
        searchLists() {
            this.fetchDatas(this.pagination.current_page);
        },
        // 显示收货地址
        showAddress(orderGuid) {
            layer.load(2);
            var data = {
                'guid':orderGuid,
                '_token':token
            }
            axios.post('/admin/orderAddress', data).then(response => {
               if(response.data.ServerNo == 200){
                    $('#address').find('.user').val(response.data.ResultData.consignee);
                    $('#address').find('.tel').val(response.data.ResultData.tel);
                    var tmp = response.data.ResultData.province + response.data.ResultData.city + response.data.ResultData.county + response.data.ResultData.detailed_address
                    $('#address').find('.address').html(tmp);
               }else{
                   sweetAlert("请求失败!", "获取收货地址信息失败!", "error");
               }
                layer.closeAll();
            }).catch(error => {
                // layer 加载层关闭
                layer.closeAll();
                sweetAlert("请求失败!", "获取收货地址信息失败!", "error");
            });
        },
        // 发货
        sendGoods(id,index,status) {
            layer.load(2);
            var data = {
                'id':id,
                '_token':token
            };

            axios.post('/admin/order/sendGoods', data).then(response => {
                if(response.data.ServerNo == 200){

                    this.datas[index].order_status=3;
                    $('.status').html('待收货');
                }else{
                    sweetAlert("请求失败!", "获取收货地址信息失败!", "error");
                }
                layer.closeAll();
            }).catch(error => {
                layer.closeAll();
                sweetAlert("请求失败!", "发货失败!", "error");
            });
        }
    }
});
