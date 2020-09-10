{include file="public/layer_open"}
<script src="{__PLUG_PATH}city.js"></script>
<style>
    .layui-form-item .layui-form-checkbox[lay-skin="primary"] {
        margin: 5px 5px;
    }
    .layui-form-item .layui-input-inline {
        margin-left: 0;
        margin-right: 20px;
        margin-bottom: 10px;
        width: 140px;
        position: relative;
        border: 1px solid #ffffff;
        border-bottom: none;
    }
    .layui-btn {
        padding: 0 38px;
    }
    .children{
        position: absolute;
        left: -1px;
        border: 1px solid #a6d2fe;
        width: 270px;
        z-index: 9999;
        background: #edf6ff;
        display: none;
    }
    .children .layui-form-checkbox[lay-skin="primary"] span {
        padding-right: 5px;
        min-width: 43px;
    }
    .children .layui-form-checkbox[lay-skin="primary"] {
        padding-left: 22px;
    }
</style>
<body>
<div class="wapper-content" id="app">
    <div class="layui-form" style="padding:20px 20px 10px 20px;">
        <div class="layui-form-item" style="margin-bottom: 80px">
            <div class="layui-input-inline" v-for="(city,index) in citys" :key="city.value" @mouseenter="showHideChildren(index)" @mouseleave="showHideChildren(index,2)">
                <input type="checkbox" name="city" lay-skin="primary" lay-filter="city" :data-index="index" :value="city.label" :title="city.label"><span style="color: #ff6600;"></span>
                <div class="children" :id="forId('children',index)">
                    <input  v-for="(item,index2) in city.children" :key="item.value" type="checkbox" name="area" lay-skin="primary" lay-filter="area" :value="item.label" :title="item.label" :data-index="index" :data-index2="index2" :data-value="item.value">
                </div>
            </div>
        </div>

        <div class="layui-form-item" style="text-align: center">
            <button type="submit" class="layui-btn layui-btn-normal" @click="addArea">确定</button>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script>
    var $ = layui.jquery;
    var form = layui.form;
    new Vue({
        el: "#app",
        data: {
            citys:city,
            areaData:[],
            freightIndex:''
        },
        methods:{
            forId:function(text,index){
                return text + '-' + index;
            },
            showHideChildren:function(index,type = 1){
                if(type == 2) {
                    if(city[index].children) $('#children-'+index).hide();
                    $('#children-'+index).parents('.layui-input-inline').css({'border-color':'#ffffff','background':'none'});
                } else {
                    if(city[index].children) $('#children-'+index).show();
                    $('#children-'+index).parents('.layui-input-inline').css({'border-color':'#a6d2fe','background':'#edf6ff'});
                }
            },
            addArea:function () {
                var that = this;
                var province = '';
                that.areaData.forEach(function(value, key) {
                    if(value.length > 0){
                        value.forEach(function (item,key2) {
                            that.areaData[key][key2].province = city[key].label;
                            province += city[key].label;
                        });
                    }
                });
                if(that.freightIndex !== '' && province === '') {
                    layer.msg('请选择地区');
                    return false;
                }
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
                parent.addAreaCallback(that.areaData,that.freightIndex);
            },
            //这个写的很智障
            init:function (selectedArea,selectedIndex,freightIndex) {
                var that = this;
                that.freightIndex = freightIndex;
                if(selectedArea.length > 0){
                    selectedArea.forEach((item,index) =>{
                        if(item.length > 0){
                            item.forEach((val,index2) =>{
                                if(val.length > 0){
                                    var obj = $('#children-' + index2);
                                    if(selectedIndex !== '' && parseInt(selectedIndex) == parseInt(index)){
                                        Vue.set(that.areaData, index2, []);
                                        //编辑，当前编辑行的地区可选，其他已选的不可选
                                        obj.prev('span').prev().addClass("layui-form-checked");
                                        obj.prev('span').prev().prev().prop("checked","true");
                                        obj.prev('span').html('('+val.length+')');
                                        if(parseInt(index2) === 31 || parseInt(index2) === 32 || parseInt(index2) === 33){
                                            that.areaData[index2].push(city[index2]);
                                        }else{
                                            obj.find('input').each(function (e) {
                                                if(val.indexOf($(this).data('value')) !== -1){
                                                    $(this).prop("checked","true");
                                                    $(this).next('div').addClass('layui-form-checked');
                                                    that.areaData[index2].push(city[index2].children[$(this).data('index2')]);
                                                }
                                            });
                                        }
                                    }else{
                                        //新增，其他已选的不可选
                                        obj.prev('span').prev().addClass("layui-disabled layui-checkbox-disbaled");
                                        obj.prev('span').prev().prev().prop("disabled","true");
                                        obj.find('input').each(function (e) {
                                            if(val.indexOf($(this).data('value')) !== -1){
                                                $(this).prop("disabled","true");
                                                $(this).next('div').addClass('layui-disabled layui-checkbox-disbaled');
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
            }
        },
        mounted:function () {
            var that = this;
            window.init=that.init;
            form.render(); //实例化form
            form.on('checkbox(city)', function(data){
                var index = data.elem.dataset.index;
                var childrens = city[index].children;
                if(childrens === undefined) {
                    childrens = [];
                    childrens.push(city[index]);
                }
                var obj = $('#children-' + index);
                if(data.elem.checked){
                    Vue.set(that.areaData, index, childrens);
                    obj.prev('span').html('('+childrens.length+')');
                    obj.find('input').prop("checked","true");
                    obj.find('div').addClass('layui-form-checked');
                }else{
                    Vue.set(that.areaData, index, []);
                    obj.prev('span').html('');
                    obj.find('input').prop("checked","false");
                    obj.find('div').removeClass('layui-form-checked');
                }
            });
            form.on('checkbox(area)', function(data){
                var dataIndex = data.elem.dataset.index;
                var index2 = data.elem.dataset.index2;
                var childrens = city[dataIndex].children;
                var obj = $('#children-' + dataIndex);
                var areaData = that.areaData[dataIndex];
                if(areaData === undefined) {
                    Vue.set(that.areaData, dataIndex, []);
                    areaData = that.areaData[dataIndex];
                }
                if(data.elem.checked){
                    if(childrens === undefined) areaData.push(city[dataIndex]);
                    else areaData.push(childrens[index2]);
                    obj.prev('span').prev().addClass("layui-form-checked");
                    obj.prev('span').prev().prev().prop("checked","true");
                    obj.prev('span').html('('+areaData.length+')');
                }else{
                    areaData.forEach((item,key) => {
                        if(item.value == data.elem.dataset.value) areaData.splice(key,1);
                    });
                    if(areaData.length == 0){
                        Vue.set(that.areaData, dataIndex, []);
                        obj.prev('span').prev().removeClass("layui-form-checked");
                        obj.prev('span').prev().prev().prop("checked","false");
                        obj.prev('span').html('');
                    }else obj.prev('span').html('('+areaData.length+')');
                }
            });
        }
    });
</script>
</body>
</html>