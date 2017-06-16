/**
 * 分页获取管理员列表
 * @author zhangyuchao
 */
var userListVue = new Vue({
    // 绑定元素
    el: '#userList',
    // 响应式参数
    data() {
        return {
            pagination: {
                total: 0, // 总条数
                from: 1, // 当前页码第一个栏数据是数据库第几条
                to: 0, // 当前页码最后一栏数据是数据库第几条
                current_page: 1 // 当前页
            },
            adminId: '',
            offset: 4, // 页码偏移量
            datas: [], // 页码内容
            search: {'type': 0, 'value': ''}, // 搜索条件
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
            axios.post('/admin/usersList', data).then(response => {
                if (response.data.ServerNo == 200) {
                    this.datas = response.data.ResultData.data;
                    this.pagination = response.data.ResultData;
                    layer.closeAll();
                }
            }).catch(error => {
                // layer 加载层关闭
                layer.closeAll();
                sweetAlert("请求数据失败!", "", "error");
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
        // 获取管理员ID
        getAdminId(id) {
            this.adminId = id;
        },
        // 删除管理员操作
        deleteAdmin(id, index) {
            axios.post('/admin/users/' + id, {'_method': 'delete'}).then(response => {
                if (response.data.ServerNo == 200) {
                    // 删除成功 页面数据移除
                    this.datas.splice(index, 1)
                    layer.closeAll();
                    sweetAlert("删除成功!", "", "success");
                } else {
                    layer.closeAll();
                    sweetAlert("删除失败!", "", "success");
                }
            }).catch(error => {
                // layer 加载层关闭
                layer.closeAll();
                sweetAlert("请求数据失败!", "", "error");
            });
        },
        // 角色绑定
        fetchRoles(index, id) {
            role.fetchRoles(index, id);
        }
    }
});

/**
 * 角色绑定
 * @author Luoyan
 */
var role = new Vue({
    el: '#bindModal',
    // 响应式参数
    data() {
        return {
            user: [], // 修改时显示的单个数据
            roles: [], // 角色权限列表
        }
    },
    // 方法定义
    methods: {
        // 获取分类下得标签
        fetchRoles(index, id) {
            // layer 加载层
            layer.load(2);
            this.user = [];
            // 将查询分类数据赋值都变量方便提供给修改模态框
            this.user = userListVue.datas[index];
            // 清空标签列表数据
            this.roles = [];
            // 获取当前分类已有的标签
            axios.get('/admin/users/' + id).then(response => {
                // layer 加载层关闭
                layer.closeAll();
                // 判断修改是否成功
                if (response.data.ServerNo != 200) {
                    return sweetAlert("失败!", "获取失败!", "error");
                }
                // 添加到标签列表之中，并且页面自动生成新元素
                this.roles = response.data.ResultData;
            }).catch(error => {
                // layer 加载层关闭
                sweetAlert("失败!", "请求失败!", "error");
            });
        },
        // 完成权限绑定
        doneBind() {
            // layer 加载层
            layer.load(2);
            // 获取选中标签的 id
            var labels = [];
            $('.r_on > input[type="radio"]').each(function (i, e) {
                labels[i] = $(e).val();
            });
            // 发送一次分类绑定标签请求
            axios.patch('/admin/syncRoles/' + this.user.id, labels).then(response => {
                // layer 加载层关闭
                layer.closeAll();
                // 隐藏模态框
                $('#bindModal').modal('hide');
            }).catch(error => {
                // layer 加载层关闭
                sweetAlert("失败!", "请求失败!", "error");
            });
        },
    }
});

// 复选框样式切换
$('#bindModal').on('click', '.label_radio', function (e) {
    // 阻止事件冒泡
    e.preventDefault();

    if ($(this).hasClass('r_on')) {
        return $(this).removeClass('r_on');
    }
    // 样式切换
    $('.label_radio').removeClass('r_on');
    $(this).addClass('r_on');
});