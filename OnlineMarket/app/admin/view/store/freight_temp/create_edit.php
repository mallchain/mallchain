{include file="public/layer_open"}
{include file="public/head"}
<style>
    .layui-table td .layui-input{
        height: 28px
    }
    .layui-table td{
        padding: 5px 10px;
    }
</style>
<body>
<div class="wapper-content" id="app">
    <form class="layui-form layui-form-pane" action="save" style="padding:20px 20px 10px 20px;">
        <input type="hidden" name="id" v-model="id">
        <div class="layui-form-item">
            <label class="layui-form-label">模板名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" lay-reqtext="请填写模板名称" placeholder="请输入模板名称" autocomplete="off" class="layui-input" v-model="name">
            </div>
        </div>
        <div class="layui-inline" pane="">
            <label class="layui-form-label">计费方式</label>
            <div class="layui-input-block" style="border: 1px solid #e6e6e6;margin-right: 60px">
                <div v-if="type == 1">
                    <input type="radio" lay-filter="type" name="type" value="1" title="按重量" checked="">
                    <input type="radio" lay-filter="type" name="type" value="2" title="按件数">
                </div>
                <div v-if="type == 2">
                    <input type="radio" lay-filter="type" name="type" value="1" title="按重量">
                    <input type="radio" lay-filter="type" name="type" value="2" title="按件数" checked="">
                </div>
            </div>
        </div>
        <div class="layui-inline" pane="">
            <label class="layui-form-label">默认运费</label>
            <div class="layui-input-block" style="border: 1px solid #e6e6e6;margin-right: 60px">
                <div v-if="isDefault == 1">
                    <input type="radio" name="is_default" value="1" title="启用" checked="">
                    <input type="radio" name="is_default" value="0" title="不启用">
                </div>
                <div v-if="isDefault == 0">
                    <input type="radio" name="is_default" value="1" title="启用">
                    <input type="radio" name="is_default" value="0" title="不启用" checked="">
                </div>
            </div>
        </div>

        <div class="layui-inline" pane="">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block" style="border: 1px solid #e6e6e6;width: 100px">
                <input type="checkbox" v-if="status == 0" checked="" name="status" lay-skin="switch" lay-filter="switchTest" lay-text="启用|禁用">
                <input type="checkbox" v-if="status == 1" name="status" lay-skin="switch" lay-filter="switchTest" lay-text="启用|禁用">
            </div>
        </div>
        <div class="layui-form-item" style="margin-top: 15px;font-size: 12px;color: #FF5722">
            注：“启用默认运费”则当所设置的配送区域没有符合条件的区域时会选择【默认运费】进行计算运费，“不启用默认运费”则当所设置的配送区域没有符合条件的区域时则该区域不支持配送。
        </div>
        <div style="max-height: 480px;overflow: auto;margin-top: 10px"><!--id="table-cont"-->
            <table class="layui-table" style="margin-top: 0;margin-bottom: 0">
                <thead><!--style="position: relative;z-index: 999"-->
                <tr>
                    <th>配送区域</th>
                    <th width="80px">{{firstUnitName}}</th>
                    <th width="80px">运费（元）</th>
                    <th width="80px">{{continueUnitName}}</th>
                    <th width="80px">运费（元）</th>
                    <th width="80px">操作</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in freightData" v-bind:key="item.id">
                    <td>{{item.area}}</td>
                    <td><input type="text" lay-verify="required" v-model="item.first_unit" autocomplete="off" class="layui-input"></td>
                    <td><input type="text" lay-verify="required" v-model="item.first_money" autocomplete="off" class="layui-input"></td>
                    <td><input type="text" lay-verify="required" v-model="item.continue_unit" autocomplete="off" class="layui-input"></td>
                    <td><input type="text" lay-verify="required" v-model="item.continue_money" autocomplete="off" class="layui-input"></td>
                    <td>
                        <a v-show="item.is_del" class="layui-btn layui-btn-xs" @click="addArea(index)">编辑</a>
                        <a v-show="item.is_del" class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" @click="delArea(index)">删除</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="layui-form-item" style="margin-top: 10px">
            <button type="button" class="layui-btn layui-btn-sm" @click="addArea()"><i class="layui-icon">&#xe654;</i>新增配送区域</button>
        </div>
        <div class="layui-form-item" style="text-align: center">
            <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="submit">立即提交</button>
        </div>
    </form>
</div>
<!--<script src="{__ADMIN_PATH}js/layuiList.js"></script>-->
<script>
    var $ = layui.jquery;
    var table = layui.table;
    var form = layui.form;
    var temp={:json_encode($temp)};
    var is_default = temp.is_default === 0 ? 0 : 1;
    var status = temp.status === 0 || temp.status === undefined ? 0 : 1;
    new Vue({
        el: "#app",
        data: {
            id: temp.id || 0,
            name: temp.name || '',
            type: temp.type || 1,
            isDefault: is_default,
            status: status,
            firstUnitName: temp.type === 1 ? '首重（克）' : '首件（个）' || '首重（克）',
            continueUnitName: temp.type === 1 ? '续重（克）' : '续件（个）' || '续重（克）',
            freightData: temp.freight_data || [
                {
                    area:'全国【默认运费】',
                    first_unit:'',
                    first_money:'',
                    continue_unit:'',
                    continue_money:'',
                    is_del:0,
                    selectedIndex:0
                }
            ],
            selectedArea: [[]]
        },
        methods:{
            addArea:function(freightIndex = ''){
                var that = this;
                layer.open({
                    type: 2
                    ,area: ['800px', '600px']
                    ,title: '选择配送区域'
                    ,shade: 0.2 //遮罩透明度
                    ,maxmin: false //是否允许全屏最小化
                    ,anim: 1 //0-6的动画形式，-1不开启
                    ,content: 'area'
                    ,success: function (layero, index) {
                        // 获取子页面的iframe
                        var iframe = window['layui-layer-iframe' + index];
                        // 向子页面的全局函数init传参
                        var selectedIndex = '';
                        if(freightIndex !== '') {
                            selectedIndex = that.freightData[freightIndex].selectedIndex;
                        }
                        iframe.init(that.selectedArea,selectedIndex,freightIndex);
                    }
                });
            },
            addAreaCallback:function (areaData,freightIndex) {
                if(areaData){
                    var areas = '';
                    if(freightIndex !== '') {
                        var selectedIndex = this.freightData[freightIndex].selectedIndex;
                        areaData.forEach((item,index) =>{
                            Vue.set(this.selectedArea[selectedIndex], index, []);
                            var selectedArea = this.selectedArea[selectedIndex][index];
                            if(item.length > 0){
                                item.forEach(val =>{
                                    if(val.label){
                                        areas += val.province + '-' + val.label + ';';
                                        selectedArea.push(val.value);
                                    }
                                });
                            }
                        });
                        this.freightData[freightIndex].area = areas;
                    }else{
                        this.selectedArea.push([]);
                        var selectedLength = this.selectedArea.length;
                        areaData.forEach((item,index) =>{
                            if (this.selectedArea[selectedLength-1][index] === undefined) {
                                Vue.set(this.selectedArea[selectedLength-1], index, []);
                                var selectedArea = this.selectedArea[selectedLength-1][index];
                            }else var selectedArea = this.selectedArea[selectedLength-1][index];
                            if(item.length > 0){
                                item.forEach(val =>{
                                    if(val.label){
                                        areas += val.province + '-' + val.label + ';';
                                        selectedArea.push(val.value);
                                    }
                                });
                            }
                        });
                        if(areas) {
                            this.freightData.push({
                                area:areas,
                                first_unit:'',
                                first_money:'',
                                continue_unit:'',
                                continue_money:'',
                                is_del:1,
                                selectedIndex:selectedLength-1
                            });
                        }
                    }
                }
            },
            delArea:function (index) {
                this.selectedArea[this.freightData[index].selectedIndex] = [];
                this.freightData.splice(index,1);
            }
        },
        mounted:function () {
            var that = this;
            window.addAreaCallback=that.addAreaCallback;
            if(temp.selected_area){
                for(var index in temp.selected_area){
                    var item = temp.selected_area;
                    var item = item[index];
                    var selected_area = [];
                    for(var val in item){
                        Vue.set(selected_area, val, item[val]);
                    }
                    Vue.set(that.selectedArea, index, selected_area);
                }
            }
            form.render(); //实例化form
            form.on('radio(type)', function (data) {
                that.type = data.value;
                if(data.value == 1) {
                    that.firstUnitName = "首重（克）";
                    that.continueUnitName = "续重（克）";
                } else {
                    that.firstUnitName = "首件（个）";
                    that.continueUnitName = "续件（个）";
                }
            });
            //监听提交
            form.on('submit(submit)', function(data){
                var postData = data.field;
                postData.freightData = that.freightData;
                postData.selectedArea = that.selectedArea;
                $eb.axios.post("{$save}",postData).then((res)=>{
                    if(res.status && res.data.code == 200)
                        return Promise.resolve(res.data);
                    else
                        return Promise.reject(res.data.msg || '添加失败,请稍候再试!');
                }).then((res)=>{
                    $eb.message('success',res.msg || '操作成功!');
                    $eb.closeModalFrame(window.name);
                    parent.$(".J_iframe:visible")[0].contentWindow.location.reload(); //刷新父页面
                }).catch((err)=>{
                    this.loading=false;
                    $eb.message('error',err);
                });
                return false;
            });
            window.onload = function(){
                var tableCont = document.querySelector('#table-cont');
                function scrollHandle (e){
                    console.log(this);
                    var scrollTop = this.scrollTop;
                    this.querySelector('thead').style.transform = 'translateY(' + scrollTop + 'px)';
                }

                tableCont.addEventListener('scroll',scrollHandle);
            }
        }
    });
</script>
</body>
</html>