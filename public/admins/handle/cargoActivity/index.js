/**
 * 活动列表
 *
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
            cargoActivitys: [],             // 正在做活动的货品列表
            per_page: 10,                   // 一页显示的数据
            search: {}                      // 搜索条件
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
            return this.cargoActivitys.length;
        }
    },
    methods: {
        // 获取页码数据
        fetchDatas(page) {
            // 请求数据
            var data = {
                page: page,                 // 当前页
                perPage: this.per_page,     // 页面展示的数据
                where: this.search          // 搜索条件
            };
            // 请求数据
            axios.post('/admin/cargoActivityList', data).then(response => {
                console.log(response);
                // layer 加载层关闭
                layer.closeAll();
                // 判断请求结果
                if(response.data.ServerNo != 200){
                    sweetAlert("请求失败!", response.data.ResultData, "error");
                    return;
                }
                // 响应式更新数据
                this.cargoActivitys = response.data.ResultData.data;
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
        // 搜索
        searchList(e) {
            var name = e.target.cargo_name.value;
            if(!name){
                this.search = [];
            }else{
                this.search = {name: name};
            }
            this.fetchDatas(this.pagination.current_page);
        },
        // 将PHP时间戳转换成日期格式
        timeConvert(time){
            var date = new Date(parseInt(time)*1000);
            var year = date.getFullYear();
            var month = (date.getMonth()+1) < 10 ? '0'+(date.getMonth()+1) : (date.getMonth()+1);
            var today = date.getDate() < 10 ? '0'+date.getDate() : date.getDate();
            var hour = date.getHours() < 10 ? '0'+date.getHours() : date.getHours();
            var minutes = date.getMinutes() < 10 ? '0'+date.getMinutes() : date.getMinutes();
            var seconds = date.getSeconds() < 10 ? '0'+date.getSeconds() : date.getSeconds();
            var time = year+'-'+month+'-'+today+' '+hour+':'+minutes+':'+seconds;
            return time;
        }
    }
});