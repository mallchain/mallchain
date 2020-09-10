{include file="public/layer_open"}
<style>
    .layui-form-pane .layui-form-label{
        width: 140px;
    }
    .line-wapper{
        margin-top: 40px;
        color: #333
    }
    .layui-form-pane .layui-input{
        width: 280px;
    }
</style>
<body>
<div class="wapper-content layui-form-pane" id="app">
    <div style="text-align: center;padding:0 10px;">
        <div class="line-wapper">
            <div class="layui-inline">
                <label class="layui-form-label">公众号跳转链接</label>
                <div class="layui-input-inline">
                    <input type="text" name="number" autocomplete="off" class="layui-input" readonly value="{$h5_url}">
                </div>
            </div>
            <button type="button" class="layui-btn layui-btn-normal" @click="copyUrl('{$h5_url}')"><i class="layui-icon">&#xe64c;</i>{{copyBtnName}}</button>
        </div>
        <div class="line-wapper">
            <div class="layui-inline">
                <label class="layui-form-label">小程序跳转链接</label>
                <div class="layui-input-inline">
                    <input type="text" name="number" autocomplete="off" class="layui-input" readonly value="{$wxapp_url}">
                </div>
            </div>
            <button type="button" class="layui-btn layui-btn-normal" @click="copyUrl('{$wxapp_url}')"><i class="layui-icon">&#xe64c;</i>{{copyBtnName}}</button>
        </div>
    </div>
</div>
<script>
    var $ = layui.jquery

    new Vue({
        el: "#app",
        data: {
            copyBtnName:'复制链接'
        },
        methods:{
            copyUrl:function (url) {
                const input = document.createElement('input');
                document.body.appendChild(input);
                input.setAttribute('value',url);
                input.select();
                if (document.execCommand('copy')) {
                    document.execCommand('copy');
                    document.body.removeChild(input);
                    layList.msg('复制成功',{time:1000});
                    setTimeout(function(){
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    },1000);
                }else{
                    document.body.removeChild(input);
                    layList.msg('复制失败，请手动复制');
                }
            }
        },
        mounted:function () {
           
        }
    });
</script>
</body>
</html>