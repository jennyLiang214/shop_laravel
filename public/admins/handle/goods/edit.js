/**
 * 添加商品 Vue
 * @author zhulinjie
 */
new Vue({
    // 绑定元素
    el: "#main-content",
    // 响应式参数
    data(){
        return {
            level1: '',                 // 一级分类
            level2: '',                 // 二级分类
            level3: '',                 // 三级分类
            lv1s: [],                   // 存储一级分类数据
            lv2s: [],                   // 存储二级分类数据
            lv3s: [],                   // 存储三级分类数据
            goods_title: '',            // 商品标题
            goodsLabels: [],            // 存储商品标签
            goodsLabelIds: [],          // 存储商品标签ID
            goodsLabel: '',             // 商品标签
            goodsImgs: [],              // 商品图片个数
            goods: '',                  // 商品信息
            goodsCategory: '',          // 商品分类
            cargo: [],                  // 存储货品信息
        }
    },
    // 第一次执行，初始化数据
    mounted(){
        axios.post('/admin/getGoodsDetail', {goods_id: goods_id}).then(response => {
            console.log(response);
            if (response.data.ServerNo != 200) {
                sweetAlert("操作失败!", response.data.ResultData, "error");
                return;
            }
            this.goods = response.data.ResultData;
            this.goodsCategory = response.data.ResultData.goodsCategory;
            this.level1 = this.goodsCategory[0].id;
            this.level2 = this.goodsCategory[1].id;
            this.level3 = this.goodsCategory[2].id;
            this.lv1s = response.data.ResultData.lv1s;
            this.lv2s = response.data.ResultData.lv2s;
            this.lv3s = response.data.ResultData.lv3s;
            this.goods_title = response.data.ResultData.goods_title;
            this.goodsLabels = response.data.ResultData.goodsLabels;
            this.goodsLabelIds = response.data.ResultData.goodsLabelIds;
            this.goodsImgs = JSON.parse(response.data.ResultData.goods_original);
            this.cargo = response.data.ResultData.cargo;
        }).catch(error => {
            sweetAlert("请求失败!", response.request.statusText, "error");
        });
    },
    // 计算属性
    computed: {
        // 判断商品标签是否存在
        isGoodsLabels(){
            return this.goodsLabels.length;
        }
    },
    // 方法定义
    methods: {
        // 获取二级分类
        lv1(e){
            this.level1 = e.target.value;
            this.goodsCategory = [];
            $('#level2 option').attr('selected', false);
            $('#level3 option').attr('selected', false);
            if(this.level1 != -1){
                layer.load(2);
                axios.post('/admin/getCategory', {pid: this.level1}).then(response => {
                    layer.closeAll();
                    this.lv2s = response.data.ResultData;
                    // 初始化二级分类和三级分类
                    this.level2 = '';
                    this.level3 = '';
                    this.lv3s = [];
                    this.goodsLabels = [];
                }).catch(error => {
                    layer.closeAll();
                    sweetAlert("请求失败!", response.request.statusText, "error");
                });
            }else{
                this.goodsLabels = [];
            }
        },
        // 获取三级分类
        lv2(e){
            this.level2 = e.target.value;
            this.goodsCategory = [];
            $('#level3 option').attr('selected', false);
            if(this.level2 != -1){
                if (this.level2) {
                    layer.load(2);
                    axios.post('/admin/getCategory', {pid: this.level2}).then(response => {
                        layer.closeAll();
                        this.lv3s = response.data.ResultData;
                        // 初始化三级分类
                        this.level3 = '';
                        this.goodsLabels = [];
                    }).catch(error => {
                        layer.closeAll();
                        sweetAlert("请求失败!", response.request.statusText, "error");
                    });
                }
            }else{
                this.goodsLabels = [];
            }
        },
        // 获取分类下的商品标签
        lv3(e){
            this.level3 = e.target.value;
            if(this.level3 != -1){
                if (this.level3) {
                    layer.load(2);
                    axios.post('/admin/getGoodsLabel', {category_id: this.level3}).then(response => {
                        layer.closeAll();
                        this.goodsLabels = response.data.ResultData;
                    }).catch(error => {
                        layer.closeAll();
                        sweetAlert("请求失败!", "商品标签获取失败!", "error");
                    });
                }
            }else{
                this.goodsLabels = [];
            }
        },
        // 选择商品标签
        selectLabel(e){
            e.preventDefault();
            if(e.target.tagName == 'LABEL'){
                if ($(e.target).hasClass('c_on')) {
                    // 样式切换
                    $(e.target).removeClass('c_on').addClass('c_off');
                    // 取消选中复选框
                    $(e.target).find('input')[0].checked = false;
                } else {
                    $(e.target).removeClass('c_off').addClass('c_on');
                    // 选中单选按钮
                    $(e.target).find('input')[0].checked = true;
                }
            }
        },
        // 添加商品标签
        addGoodsLabel(){
            // 判断是否选择了分类
            if(!this.level3){
                sweetAlert("操作失败!", "请先选择分类!", "error");
                return;
            }
            // 判断商品标签名称是否已填写
            if(!this.goodsLabel){
                sweetAlert("操作失败!", "请先填写商品标签名!", "error");
                return;
            }
            // 准备数据
            var data = {
                category_id: this.level3,
                goods_label_name: this.goodsLabel
            };
            // 添加请求
            axios.post('/admin/addGoodsLabel', data).then(response => {
                // 添加失败的情况
                if(response.data.ServerNo != 200){
                    sweetAlert("操作失败!", response.data.ResultData, "error");
                    return;
                }
                // 添加成功的情况
                var data = response.data.ResultData;
                // 前端实时显示
                this.goodsLabels.push(data);
                sweetAlert("操作成功!", "添加商品标签成功!", "success");
            // 请求失败的情况
            }).catch(error => {
                sweetAlert("请求失败!", response.request.statusText, "error");
            });
        },
        // 添加商品操作
        updateGoods(e){
            // 前端验证
            $('#goods').bootstrapValidator('validate');
            // 判断是否选择了分类
            if (!this.level3) {
                sweetAlert("操作失败!", "请先选择分类!", "error");
                return;
            }
            // 判断标题是否填写
            if (!this.goods_title) {
                sweetAlert("操作失败!", "请先填写商品名称!", "error");
                return;
            }
            // 商品描述
            var goods_info = $(e.target).parents('form').find('textarea').val();
            // 判断商品描述是否填写
            if (!goods_info) {
                sweetAlert("操作失败!", "请先填写商品详情!", "error");
                return;
            }
            // 构造一个包含Form表单数据的FormData对象，需要在创建FormData对象时指定表单的元素
            var fd = new FormData($(e.target).parents('form')[0]);
            // 添加请求
            axios.post('/admin/updateGoods/'+goods_id, fd).then(response => {
                console.log(response);
                // 添加商品失败的情况
                if (response.data.ServerNo != 200) {
                    sweetAlert("操作失败!", response.data.ResultData, "error");
                    return;
                }
                // 添加商品成功的情况
                swal({
                    title: '操作成功',
                    text: response.data.ResultData,
                    type: 'success'
                }, function(isConfirm){
                    if(isConfirm){
                        // 500毫秒以后跳转到商品列表页
                        setTimeout(function () {
                            location.href="/admin/goods";
                        }, 500);
                    }
                });
            }).catch(error => {  // 请求失败的情况
                sweetAlert("操作失败!", response.request.statusText, "error");
            });
        },
        // 添加商品图片
        addGoodsImg(e){
            e.preventDefault();
            this.goodsImgs.push(this.goodsImgs.length + 1 + '');
        },
        // 上传商品图片
        uploadGoodsImg(e){
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
                axios.post('/admin/goodsImgUpload', fd).then(response => {
                    if (response.data.ServerNo != 200) {
                        sweetAlert("操作失败!", response.data.ResultData, "error");
                        return;
                    }
                    // 接收返回的数据，即上传到七牛云后返回的图片路径名
                    var data = response.data.ResultData;
                    // 设置图片src属性，用于回显
                    $(e.target).attr('src', QINIU_DOMAIN + data + '?imageView2/1/w/350/h/350');
                    // 将返回图片路径名设置到隐藏文本框中，用于提交到数据库
                    $(e.target).nextAll('.goods_original').val(data);
                    // 删除事件，防止事件累加导致一次上传多个文件的问题
                    $(obj).off('change');
                // 请求失败的情况
                }).catch(error => {
                    sweetAlert("操作失败!", response.request.statusText, "error");
                });
            });
        },
        // 判断数组中是否存在某个值
        inArray(v,arr){
            for(var i in arr){
                if(arr[i] == v){
                    return true;
                }
            }
            return false;
        }
    }
});