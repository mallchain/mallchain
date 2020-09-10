<!DOCTYPE html>
<!--suppress JSAnnotator -->
<html lang="zh-CN">
<head>
    {include file="public/head"}
    <script src="{__PLUG_PATH}layuinew/layui.all.js"></script>
    <link href="{__PLUG_PATH}layuinew/css/layui.css" rel="stylesheet">
    <title>{$title}</title>
</head>
<style type="text/css">
    /*.ivu-form-item-content{max-height: 360px;overflow: auto}*/
    .layui-table td, .layui-table th {padding: 5px 10px;}
    .ivu-form-item {margin-bottom: 20px;}
</style>
<body>
<div id="form-add" class="mp-form" v-cloak="">
    <i-Form :model="formData" :label-width="100" >
        <Form-Item label="专题名称">
            <i-input v-model="formData.module_name" placeholder="请输入专题名称" clearable></i-input>
        </Form-Item>
        <Form-Item label="banner图">
            <Row>
                <i-Col span="13">
                    <div class="demo-upload-list" v-if="formData.image">
                        <template>
                            <img :src="formData.image">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click="visible = true "></Icon>
                                <Icon type="ios-trash-outline" @click="formData.image=''"></Icon>
                            </div>
                        </template>
                    </div>
                    <div class="ivu-upload" style="display: inline-block; width: 58px;" @click="openWindows('选择图片','{:Url('widget.images/index',['fodder'=>'image'])}',{w:900,h:550})" v-if="!formData.image">
                        <div class="ivu-upload ivu-upload-drag">
                            <div style="width: 58px; height: 58px; line-height: 58px;">
                                <i class="ivu-icon ivu-icon-camera" style="font-size: 20px;"></i>
                            </div>
                        </div>
                    </div>
                    <Modal title="查看图片" v-model="visible" :visible.sync="visible">
                        <img :src="formData.image" v-if="visible" style="width: 100%">
                    </Modal>
                </i-Col>
            </Row>
        </Form-Item>
        <Form-Item label="排序">
            <i-col span="4">
                <Input-number rows="4" number v-model="formData.sort" placeholder=""></Input-number>
            </i-col>
        </Form-Item>
        <Form-Item label="状态">
            <Radio-group v-model="formData.is_show">
                <Radio :label='1'>显示</Radio>
                <Radio :label='0'>隐藏</Radio>
            </Radio-group>
        </Form-Item>

        <Form-Item label="商品列表">
            <div style="max-height: 360px;overflow: auto"><!--id="table-cont"-->
                <table class="layui-table" style="margin-top: 0">
                    <thead><!--style="position: relative;z-index: 999"-->
                    <tr>
                        <th width="100px">ID</th>
                        <th width="80px">商品图片</th>
                        <th>商品名称</th>
                        <th width="120px">价格</th>
                        <th width="150px">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in formData.productList">
                        <td>{{item.id}}</td>
                        <td><img :src="item.image" style="width: 100%"></td>
                        <td>{{item.store_name}}</td>
                        <td>{{item.price}}</td>
                        <td>
                            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" @click="removeProduct(index)">删除</a>
                            <button type="button" :id="forId(index,'up')" class="layui-btn layui-btn-xs" @mouseenter="tips('上移',index,'up')" @click="sortUp(index)"><i class="layui-icon">&#xe619;</i></button>
                            <button type="button" :id="forId(index,'down')" class="layui-btn layui-btn-xs" @mouseenter="tips('下移',index,'down')" @click="sortDown(index)" style="margin-left: 0"><i class="layui-icon">&#xe61a;</i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            </row>
        </Form-Item>
        <Form-Item><i-button type="primary" @click="openWindows('添加商品','{:Url('choose_product',array('id'=>1))}',{w:900,h:650})">添加商品</i-button></Form-Item>
        <Form-Item :class="'add-submit-item'">
            <i-Button :type="'primary'" :html-type="'submit'" :size="'large'" :long="true" @click.prevent="submit">提交</i-Button>
        </Form-Item>
    </i-Form>
</div>
<script>
    var customize={:json_encode($customize)};
    var productList={:json_encode($productList)};
    var is_show = customize.is_show == 0 ? 0 : 1;
    $eb = parent._mpApi;
    mpFrame.start(function(Vue){
        new Vue({
            el:"#form-add",
            data:{
                visible:false,
                formData:{
                    id: customize.id || 0,
                    module_name: customize.module_name || '',
                    sort: customize.c_sort || 0,
                    is_show:is_show,
                    image: customize.image || '',
                    productList: productList || []
                }
            },
            methods:{
                createFrame:function(title,src,opt){
                    opt === undefined && (opt = {});
                    var h = parent.document.body.clientHeight - 100;
                    return layer.open({
                        type: 2,
                        title:title,
                        area: [(opt.w || 700)+'px', (opt.h || h)+'px'],
                        fixed: false, //不固定
                        maxmin: true,
                        moveOut:true,//true  可以拖出窗外  false 只能在窗内拖
                        anim:5,//出场动画 isOutAnim bool 关闭动画
                        offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
                        shade:0,//遮罩
                        resize:true,//是否允许拉伸
                        content: src,//内容
                        move:'.layui-layer-title'
                    });
                },
                openWindows:function(title,url,opt){
                    return this.createFrame(title,url,opt);
                },
                changeIMG:function(name,url){
                    this.formData[name]=url;
                },
                isInArray:function(arr,value){
                    for(var i = 0; i < arr.length; i++){
                        if(value === arr[i]['id']){
                            return true;
                        }
                    }
                    return false;
                },
                addProduct: function(product){
                    if(this.isInArray(this.formData.productList,product.id)){
                        layer.msg('商品已存在!');
                        return false;
                    }
                    this.formData.productList.push({
                        id: product.id,
                        image: product.image,
                        store_name: product.store_name,
                        price: product.price,
                    });
                    layer.msg('选择成功!');
                },
                removeProduct: function(index){
                    this.formData.productList.splice(index,1);
                },
                sortUp:function(index, row){
                    if (index === 0) {
                        layer.msg('已经排第一了!');
                    } else {
                        let temp = this.formData.productList[index - 1];
                        Vue.set(this.formData.productList, index - 1, this.formData.productList[index]);
                        Vue.set(this.formData.productList, index, temp);
                    }
                },
                sortDown:function(index, row){
                    if (index === (this.formData.productList.length - 1)) {
                        layer.msg('已经排最后了!');
                    } else {
                        let i = this.formData.productList[index + 1];
                        Vue.set(this.formData.productList, index + 1, this.formData.productList[index]);
                        Vue.set(this.formData.productList, index, i);
                    }
                },
                forId:function(index,text){
                    return text + '_' + index;
                },
                tips:function(msg,index,text){
                    var id = this.forId(index,text);
                    layer.tips(msg,'#'+id,{time:1000});
                },
                submit: function(){
                    if(!this.formData.module_name) return  $eb.message('error','请填写专题名称');
                    if(!this.formData.image) return  $eb.message('error','请上传banner图');
                    if(this.formData.productList.length == 0) return  $eb.message('error','请添加商品');
                    $eb.axios.post("{$save}",this.formData).then((res)=>{
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
                }
            },
            mounted:function () {
                window.changeIMG=this.changeIMG;
                window.addProduct=this.addProduct;
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
    });
</script>
</body>
