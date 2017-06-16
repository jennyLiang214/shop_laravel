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
            search: {}, // 搜索条件
            per_page: 10, // 一页显示的数据
            role: [], // 修改时显示的单个分类数据
            permissions: [], // 角色权限列表
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
            axios.post('/admin/aclList', data).then(response => {
                // layer 加载层关闭
                layer.closeAll();
                // 响应式更新数据
                this.datas = response.data.data;
                this.pagination = response.data;
            }).catch(error => {
                // layer 加载层关闭
                layer.closeAll();
                sweetAlert("请求失败!", "用户列表请求失败!", "error");
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
        // 根据 id 获取分类数据
        fetchRoleByIndex(index) {
            // layer 加载层
            layer.load(2);
            this.role = [];
            // 将查询分类数据赋值都变量方便提供给修改模态框
            this.role = this.datas[index];
            // 记录分类列表中的中得索引
            this.role.index = index;
            // layer 加载层关闭
            layer.closeAll();
        },
        // 表单上传
        submit(id, event) {
            // layer 加载层
            layer.load(2);
            // FormData 支援把 Form 元素丟進去
            var formData = new FormData(event.target);
            // 请求数据
            axios.post('/admin/aclUpdate/' + id, formData).then(response => {
                // 判断修改是否成功
                if (response.data.ServerNo != 200) {
                    return sweetAlert("修改失败!", "角色修改!", "error");
                }
                // layer 加载层关闭
                layer.closeAll();
                // 隐藏模态框
                $('#exampleModal').modal('hide');
                // 更新分类列表中修改后的数据
                this.$set(this.datas, this.role.index, response.data.ResultData);
                // 清空修改表单内容
                this.emptyForm();
            }).catch(error => {
                // layer 加载层关闭
                sweetAlert("请求失败!", "用户列表请求失败!", "error");
            });
        },
        // 清空表单内容
        emptyForm() {
            // 清空 vue 变量
            this.role = [];
            // 清空表单内容
            $('.form-group').removeClass('has-success', 'has-error');
            $('small').remove();
            $('textarea').val('');
            $('button[type="submit"]').attr({disabled: 'disabled'});
        },
        // 完成权限绑定
        doneBind() {
            // layer 加载层
            layer.load(2);
            // 获取选中标签的 id
            var labels = [];
            $('.c_on > input[type="checkbox"]').each(function (i, e) {
                labels[i] = $(e).val();
            });
            // 发送一次分类绑定标签请求
            axios.patch('/admin/syncPermission/' + this.role.id, labels).then(response => {
                // layer 加载层关闭
                layer.closeAll();
                // 隐藏模态框
                $('#bindModal').modal('hide');
            }).catch(error => {
                // layer 加载层关闭
                sweetAlert("失败!", "请求失败!", "error");
            });
        },
        // 获取分类下得标签
        fetchPermissions(index, id) {
            // layer 加载层
            layer.load(2);
            this.role = [];
            // 将查询分类数据赋值都变量方便提供给修改模态框
            this.role = this.datas[index];
            // 清空标签列表数据
            this.permissions = [];
            // 获取当前分类已有的标签
            axios.get('/admin/acl/' + id).then(response => {
                // layer 加载层关闭
                layer.closeAll();
                // 判断修改是否成功
                if (response.data.ServerNo != 200) {
                    return sweetAlert("失败!", "获取标签失败!", "error");
                }
                // 添加到标签列表之中，并且页面自动生成新元素
                this.permissions = response.data.ResultData;
            }).catch(error => {
                // layer 加载层关闭
                sweetAlert("失败!", "请求失败!", "error");
            });
        }
    }
});

// 表单立即显示图标
$('.img').on('change', function () {
    // 获取控件中得文件
    var files = $(this).prop('files')[0];
    // 获取当前 id
    var id = $(this).prop('id');
    // 获取图标编码
    var url = getObjectURL(files);
    // 立即显示图片
    $('.' + id + '_img').attr({'src': url});
});

// 复选框样式切换
$('#bindModal').on('click', '.label_check', function (e) {
    // 阻止事件冒泡
    e.preventDefault();
    // 样式切换
    if ($(this).hasClass('c_on')) {
        $(this).removeClass('c_on').addClass('c_off');
    } else {
        $(this).removeClass('c_off').addClass('c_on');
    }
});