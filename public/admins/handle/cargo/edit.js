/**
 * 修改货品 Vue
 * @author zhulinjie
 */
new Vue({
    // 绑定元素
    el: "#main-content",
    // 响应式参数
    data(){
        return {
            lv1s: {},                // 一级分类
            lv2s: {},                // 二级分类
            lv3s: {},                // 三级分类
            goods: {},               // 商品信息
            cargo: {},               // 货品信息
            labelCargo: {},          // 货品拥有的分类标签键值对
            cargo_ids: {},           // 货品拥有的商品标签键值对
            categoryLabels: [],      // 分类标签
            goodsLabels: [],         // 商品标签
            cargoImgs: []            // 货品图片
        }
    },
    // 第一次执行
    mounted(){
        // 获取货品相关数据
        axios.post('/admin/cargo/detail', {cargo_id: cargo_id, goods_id: goods_id}).then(response => {
            console.log(response);
            // 获取数据失败的情况
            if (response.data.ServerNo != 200) {
                // sweetAlert("请求失败!", response.data.ResultData, "error");
                return;
            }
            // 一级分类
            this.lv1s = response.data.ResultData.lv1s;
            // 二级分类
            this.lv2s = response.data.ResultData.lv2s;
            // 三级分类
            this.lv3s = response.data.ResultData.lv3s;
            // 商品信息
            this.goods = response.data.ResultData.goods;
            // 货品信息
            this.cargo = response.data.ResultData.cargo;
            // 分类标签
            this.categoryLabels = response.data.ResultData.lv3s.labels;
            // 商品标签
            this.goodsLabels = response.data.ResultData.goodsLabels;
            // 货品拥有的分类标签键值对
            if(response.data.ResultData.labelCargo.category_attr_ids != undefined){
                this.labelCargo = JSON.parse(response.data.ResultData.labelCargo.category_attr_ids);
            }
            // 货品拥有的商品标签键值对
            this.cargo_ids = JSON.parse(response.data.ResultData.cargo.cargo_ids);
            // 货品图片
            this.cargoImgs = JSON.parse(response.data.ResultData.cargo.cargo_original);
        }).catch(error => {
            sweetAlert("请求失败!", error, "error");
        });
    },
    // 方法定义
    methods: {
        // 选择分类标签值
        selectLabel(e){
            e.preventDefault();
            if(e.target.tagName == 'LABEL'){
                if (!$(e.target).hasClass('r_on')) {
                    // 取消选中其它的单选按钮
                    $(e.target).parents('.form-group').find('.label_radio').removeClass('r_on');
                    // 选中当前的单选按钮
                    $(e.target).addClass('r_on');
                    $(e.target).find('input')[0].checked = true;
                }
            }
        },
        // 添加分类标签值
        addCategoryAttr(e){
            // 获取分类标签值
            var attribute_name = $.trim($(e.target).parents('.add').find('input[type=text]').val());
            var category_label_id = $(e.target).data('category_label_id');

            // 分类标签值不能为空
            if (!attribute_name) {
                sweetAlert("操作失败!", "请先填写分类标签值!", "error");
                return;
            }

            // 分类标签值添加请求
            axios.post('/admin/addCategoryAttr', {
                category_label_id: category_label_id,
                attribute_name: attribute_name
            }).then(response => {
                if (response.data.ServerNo != 200) {
                    sweetAlert("操作失败!", response.data.ResultData, "error");
                    return;
                }
                // 接收返回数据
                var data = response.data.ResultData;
                // 获取标签索引
                var index = $(e.target).data('index');
                // 前端实时添加
                this.categoryLabels[index].labels.push(data);
                sweetAlert("操作成功!", '分类标签值添加成功', "success");
                // 请求失败的情况
            }).catch(error => {
                sweetAlert("操作失败!", error, "error");
            });
        },
        // 添加商品标签值
        addGoodsAttr(e){
            // 获取分类标签值
            var goods_label_name = $.trim($(e.target).parents('.add').find('input[type=text]').val());
            var goods_label_id = $(e.target).data('goods_label_id');

            // 商品标签值不能为空
            if (!goods_label_name) {
                sweetAlert("操作失败!", "请先填写商品标签值!", "error");
                return;
            }

            // 商品标签值添加请求
            axios.post('/admin/addGoodsAttr', {
                goods_label_id: goods_label_id,
                goods_label_name: goods_label_name
            }).then(response => {
                console.log(response);
                if (response.data.ServerNo != 200) {
                    sweetAlert("操作失败!", response.data.ResultData, "error");
                    return;
                }
                // 接收返回数据
                var data = response.data.ResultData;
                // 获取标签索引
                var index = $(e.target).data('index');
                // 前端实时添加
                this.goodsLabels[index].attrs.push(data);
                sweetAlert("操作成功!", '商品标签值添加成功', "success");
                // 请求失败的情况
            }).catch(error => {
                sweetAlert("操作失败!", error, "error");
            });
        },
        // 添加货品图片
        addCargoImg(e){
            e.preventDefault();
            this.cargoImgs.push(this.cargoImgs.length + 1 + '');
        },
        // 上传货品图片
        uploadCargoImg(e){
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
                if ($.inArray(file.type, allowType) == -1) {
                    sweetAlert("操作失败!", "图片格式有误，请上传jpg、png、gif格式的图片!", "error");
                    return;
                }
                // 将上传表单控件添加到FormData对象中
                fd.append('image', file);
                // 图片上传请求
                axios.post('/admin/cargoImgUpload', fd).then(response => {
                    if (response.data.ServerNo != 200) {
                        sweetAlert("操作失败!", response.data.ResultData, "error");
                        return;
                    }
                    // 接收返回的数据，即上传到七牛云后返回的图片路径名
                    var data = response.data.ResultData;
                    // 设置图片src属性，用于回显
                    $(e.target).attr('src', QINIU_DOMAIN + data + '?imageView2/1/w/350/h/350');
                    // 将返回图片路径名设置到隐藏文本框中，用于提交到数据库
                    $(e.target).nextAll('.cargo_original').val(data);
                    // 删除事件，防止事件累加导致一次上传多个文件的问题
                    $(obj).off('change');
                    // 请求失败的情况
                }).catch(error => {
                    sweetAlert("操作失败!", error, "error");
                });
            });
        },
        // 添加货品操作
        updateCargo(e){
            // 前端验证
            $('#cargo').bootstrapValidator('validate');

            // 货品名称不能为空
            if (!$.trim($('input[name=cargo_name]').val())) {
                sweetAlert("操作失败!", "请先填写货品名称!", "error");
                return;
            }
            // 货品原价不能为空
            if (!$.trim($('input[name=cargo_price]').val())) {
                sweetAlert("操作失败!", "请先填写货品原价!", "error");
                return;
            }
            // 库存量不能为空
            if (!$.trim($('input[name=inventory]').val())) {
                sweetAlert("操作失败!", "请先填写库存量!", "error");
                return;
            }
            // 货品详情
            var cargo_info = $(e.target).parents('form').find('textarea').val();
            // 判断货品详情是否填写
            if (!cargo_info) {
                sweetAlert("操作失败!", "请先填写货品详情!", "error");
                return;
            }
            // 构造一个包含Form表单数据的FormData对象，需要在创建FormData对象时指定表单的元素
            var fd = new FormData($(e.target).parents('form')[0]);
            // 添加请求
            axios.post('/admin/updateCargo/' + cargo_id, fd).then(response => {
                console.log(response);
                // 添加货品失败的情况
                if (response.data.ServerNo != 200) {
                    sweetAlert("操作失败!", response.data.ResultData, "error");
                    return;
                }
                // 添加货品成功的情况
                swal({
                    title: '操作成功',
                    text: response.data.ResultData,
                    type: 'success'
                }, function (isConfirm) {
                    if (isConfirm) {
                        // 500毫秒以后跳转到货品列表页
                        setTimeout(function () {
                            location.href = "/admin/cargoList/" + goods_id;
                        }, 500);
                    }
                });
            }).catch(error => {  // 请求失败的情况
                sweetAlert("操作失败!", error, "error");
            });
        }
    }
});