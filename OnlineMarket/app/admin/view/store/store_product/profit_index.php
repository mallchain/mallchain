{include file="public/layer_open"}
<style>
    .line-wapper{
        margin-top: 20px;
        color: #333;
    }
    .layui-form .layui-form-label{
        margin-left: 40px;
    }
    .desc{
        text-align: left;
        line-height: 36px;
        font-size: 13px;
        color: #FF9933;
    }
</style>
<body>
<div class="wapper-content layui-form" id="app">
    <div style="text-align: center;padding:0 10px;">
        <div class="line-wapper">
            <div class="layui-form-item">
                <label class="layui-form-label">批量调整：</label>
                <div class="layui-input-block" style="text-align: left;">
                    <input type="radio" lay-filter="is_all" name="is_all" value="1" title="是">
                    <input type="radio" lay-filter="is_all" name="is_all" value="0" title="否" checked="">
                </div>
                <div class="layui-input-block desc">{{isAllDesc}}</div>    
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">利润率值：</label>
                <div class="layui-input-inline">
                    <input type="text" name="profit_rate" placeholder="%" autocomplete="off" class="layui-input" v-model="profitRate" v-on:input="clearNoNum">
                </div>
            </div>
            <div class="layui-form-item desc" style="text-align: center;">注：商品的售价和积分额度都会根据所填写的利润值变动</div>
            <button type="button" class="layui-btn layui-btn-normal" @click="adjustProfit" style="margin-top: 20px;">确定调整</button>
        </div>
    </div>
</div>
<script>
    var $ = layui.jquery;
    var form = layui.form;

    new Vue({
        el: "#app",
        data: {
            isAllDesc:'当前选择的商品批量调整利润',
            isAll:0,
            profitRate:''
        },
        methods:{
            adjustProfit:function(){
                var that = this;
                if(that.profitRate == '') {
                    layer.msg('利润率值是必填项，岂能为空？',{icon:5,shift: 6});
                    return false;
                }
                parent.$(".J_iframe:visible")[0].contentWindow.confirmProfitRate(that.isAll,that.profitRate);//调用父页面中的方法
                parent.layer.close(parent.layer.getFrameIndex(window.name)); //关闭弹窗
            },
            //限制输入框
            clearNoNum:function(){
                var that = this;
                that.profitRate = that.profitRate.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符   
                that.profitRate = that.profitRate.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的   
                that.profitRate = that.profitRate.replace(".","$#$").replace(/\./g,"").replace("$#$",".");  
                that.profitRate = that.profitRate.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数   
                if(that.profitRate.indexOf(".") < 0 && that.profitRate != ""){
                    //以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02  
                    that.profitRate= parseFloat(that.profitRate);  
                }
                if(that.profitRate > 99){
                    that.profitRate = 99;
                }
            }
        },
        mounted:function () {
            var that = this;
            form.render(); //解决leyui单选框不显示且不能点击的问题
            form.on('radio(is_all)', function (data) {
                that.isAll = data.value;
                if(data.value ==1) that.isAllDesc = "当前搜索条件下的所有商品批量调整利润";
                else that.isAllDesc = "当前选择的商品批量调整利润";
            });
        }
    });
</script>
</body>
</html>