/**
 * 首页填充数据
 * @author zhangyuchao
 */
var EchartsData = new Vue({
    // 绑定元素
    el: '.wrapper',
    // 响应式参数
    data() {
        return {
            header: [], // 数字统计
        }
    },
    // 第一次执行
    mounted() {
        // 获取第一页数据
        this.fetchDatas();
    },
    // 方法定义
    methods: {
        // 获取页码数据
        fetchDatas() {
            // 请求数据
            axios.post('/admin/index/count',{'_token':token}).then(response => {
                    if(response.data.ServerNo == 200){
                        this.header = response.data.ResultData.header;
                        // 注册方式统计饼图数据
                        this.userCount(response.data.ResultData.main.user);
                        // 订单成交率统计饼图数据
                        this.orderCount(response.data.ResultData.main.order);
                    }
                }).catch(error => {
                    // layer 加载层关闭
                    layer.closeAll();
                    sweetAlert("请求数据失败!", "", "error");
                });

        },
        // 注册方式统计
        userCount(obj){
            option = {
                title : {
                    text: '注册方式统计',
                    subtext: 'LAMP兄弟连教学使用',
                    x:'center'
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['手机注册','邮箱注册']
                },
                series : [
                    {
                        name: '注册方式',
                        type: 'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data:[
                            {value:obj.tel, name:'手机注册'},
                            {value:obj.email, name:'邮箱注册'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };

            userChart.setOption(option);
        },
        // 订单成交率统计
        orderCount(obj){
            option = {
                title : {
                    text: '订单成交率统计',
                    subtext: 'LAMP兄弟连教学使用',
                    x:'center'
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['未支付','支付超时','支付成功']
                },
                series : [
                    {
                        name: '订单成交率',
                        type: 'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data:[
                            {value:obj.Unpaid, name:'未支付'},
                            {value:obj.cancel, name:'支付超时'},
                            {value:obj.payment, name:'支付成功'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };

            orderChart.setOption(option);
        }
    }
});