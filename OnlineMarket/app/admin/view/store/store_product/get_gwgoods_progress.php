{include file="public/layer_open"}
<body>
<div class="wapper-content" id="app">
    <div style="text-align: center;padding:0 10px;">
        <div style="margin-top: 40px;color: #333">
            当前可从供应链获取的商品总数：<span style="font-weight: bold;font-size: 16px">{{totalCount}}</span>
        </div>
        <div style="margin-top: 40px;color: #333">
            已获取总数：<span style="font-weight: bold;font-size: 16px;margin-right: 10px">{{getTotalCount}}</span>
            剩余待获取：<span style="font-weight: bold;font-size: 16px">{{totalCount - getTotalCount}}</span>
        </div>
        <div class="layui-form-item" style="margin-top: 40px;color: #333">
            <div class="layui-inline">
                <label class="layui-form-label">利润率</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="price_min" placeholder="" v-model="price_min" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid">-</div>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="price_max" placeholder="" v-model="price_max" autocomplete="off" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-progress layui-progress-big" lay-showpercent="yes" lay-filter="demo" style="margin-top: 40px;">
            <div class="layui-progress-bar layui-bg-blue" lay-percent="0%">{{percent}}</div>
        </div>
        <div class="site-demo-button" style="margin: 50px 0;">
            <button id="confirm-btn" class="layui-btn layui-btn-normal" @click="getGWLPGoods()">{{confirmName}}</button>
            <button id="confirm-btn" class="layui-btn layui-btn-normal" @click="getGWLPGoodsCount()">{{reconfirmName}}</button>
            <button id="confirm-btn" class="layui-btn layui-btn-normal" @click="updateGWLPGoodsPrice()">{{pricefirmName}}</button>
        </div>
    </div>
</div>
<script>
    var $ = layui.jquery
        ,element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
    var page = <?=$page?>;

    new Vue({
        el: "#app",
        data: {
            percent:'0%',
            page:page,
            page_price:1,
            confirmName:'确定获取',
            reconfirmName:'重新计算',
            pricefirmName:'更新价格与下架',
            totalCount:0,
            totalPage:0,
            price_min:0,
            price_max:0,
            getTotalCount:0,
            pageCount:50
        },
        methods:{
            confirmBtnStyle:function(type="none"){
                if(type == "none"){
                    $('#confirm-btn').addClass('layui-btn-disabled');
                    $('#confirm-btn').attr('disabled','disabled');
                }else{
                    $('#confirm-btn').removeClass('layui-btn-disabled');
                    $('#confirm-btn').removeAttr('disabled');
                }
            },
            getGWLPGoodsCount:function () {
                var that = this;
                layList.basePost('getGWLPGoods',{type:2,price_min:that.price_min,price_max:that.price_max},function (res) {
                    var data = res.data;
                    if(parseInt(data.total_count) == 0){
                        that.confirmBtnStyle();
                    }else{
                        if(that.page > 1 && that.page <= data.total_page){
                            that.totalCount = data.total_count - ((that.page - 1) * that.pageCount);
                            that.totalPage = data.total_page;
                            layList.msg('上次获取商品未完成，本次将从第' + that.page + '页开始获取');
                        }else{
                            that.page = 1;
                            that.totalCount = data.total_count;
                            that.totalPage = data.total_page;
                        }
                    }
                },function (res) {
                    that.confirmBtnStyle();
                    layer.alert(res.msg,{icon:2});
                });
            },
            sleep:function(numberMillis){
                var now = new Date();
                var exitTime = now.getTime() + numberMillis;
                while (true) {
                    now = new Date();
                    if (now.getTime() > exitTime)
                        return;
                }
            },
            updateGWLPGoodsPrice:function(){
                var that = this;
                if(that.page_price==1){
                    var index = layer.load(1, {shade: [0.1,'#fff'] });//0.1透明度的白色背景
                    layList.msg('正在更新价格，请勿关闭弹窗');
                    that.confirmBtnStyle();
                }

                layList.basePost('updateProductPrice',{page:that.page_price},function (res) {
                    if(res.data['next_page']==1){
                        that.page_price = that.page_price + 1;
                        that.sleep(1000);
                        that.updateGWLPGoodsPrice();
                    }else{
                        layer.alert("更新完成",{icon:1},function () {
                            var indexs = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(indexs); //再执行关闭
                        });
                    }

                },function (res) {
                    that.confirmName = '确定获取';
                    that.confirmBtnStyle("show");
                    layer.alert(res.msg,{icon:2});
                });
            },

            getGWLPGoods:function () {
                // 获取广物供应链商品
                var that = this;
                layList.msg('正在获取，请勿关闭弹窗');
                that.confirmName = '正在获取...';
                that.confirmBtnStyle();
                if(that.page > that.totalPage){
                    that.confirmName = '确定获取';
                    that.confirmBtnStyle("show");
                    layer.alert("获取完成，共获取" + that.getTotalCount + "个商品",{icon:1},function () {
                        var indexs = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(indexs); //再执行关闭
                    });
                    return false;
                }
                layList.basePost('getGWLPGoods',{page:that.page,total_page:that.totalPage,price_min:that.price_min,price_max:that.price_max},function (res) {
                    var data = res.data;
                    that.page = that.page + 1;
                    that.getTotalCount += data.get_count;
                    var n = (that.getTotalCount / that.totalCount * 100).toFixed(2);
                    element.progress('demo', n+'%');
                    that.percent = n+'%';
                    that.sleep(500);
                    that.getGWLPGoods();
                },function (res) {
                    that.confirmName = '确定获取';
                    that.confirmBtnStyle("show");
                    layer.alert(res.msg,{icon:2});
                });
            }
        },
        mounted:function () {
            this.getGWLPGoodsCount();
        }
    });
</script>
</body>
</html>