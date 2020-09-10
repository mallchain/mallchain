{extend name="public/container"}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12" style="padding-bottom: 1px">
            <div class="layui-card" style="">
                <!--<div class="layui-card-header">搜索条件</div>-->
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">所有模块</label>
                                <div class="layui-input-block">
                                    <select name="is_show">
                                        <option value="">是否显示</option>
                                        <option value="1">显示</option>
                                        <option value="0">隐藏</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">专题名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="module_name" class="layui-input" placeholder="请输入专题名称">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--模块列表-->
        <div class="layui-col-md12" style="padding-top: 0">
            <div class="layui-card">
                <!--<div class="layui-card-header">分类列表</div>-->
                <div class="layui-card-body">
                    <!--<div class="alert alert-info" role="alert">
                        注:
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>-->
                    <div class="layui-btn-container">
                        <!--<a class="layui-btn layui-btn-sm" href="{:Url('index')}">分类首页</a>-->
                        <button type="button" class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('customize_create')}',{h:820,w:1100})">添加专区模块</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="pic">
                        {{# if(d.image){ }}
                        <img style="cursor: pointer" lay-event='open_image' src="{{d.image}}">
                        {{# }else{ }}
                        暂无图片
                        {{# } }}
                    </script>
                    <script type="text/html" id="is_show">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='显|隐'  {{ d.is_show == 1 ? 'checked' : '' }}>
                    </script>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('{{d.module_name}}-编辑','{:Url('customize_create')}?id={{d.id}}',{h:820,w:1100})">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        <button class="layui-btn layui-btn-xs" lay-event='delstor'>
                            <i class="fa fa-warning"></i> 删除
                        </button>
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('复制链接','{:Url('copy_url')}?id={{d.id}}',{h:250,w:620})">
                            <i class="fa fa-paste"></i> 复制链接
                        </button>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
<script>
    setTimeout(function () {
        $('.alert-info').hide();
    },3000);
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List',"{:Url('customize_list')}",function (){
        return [
            {field: 'id', title: '模块ID', sort: true,event:'id',width:'8%',align:'center'},
            {field: 'module_name', title: '专题名称',edit:'module_name',align:'center'},
            {field: 'image', title: 'banner图',templet:'#pic',align:'center',width:'15%' },
            {field: 'product_total', title: '商品总数',sort: true,event:'product_total',width:'10%',align:'center'},
            {field: 'sort', title: '排序',sort: true,event:'sort',edit:'sort',width:'10%',align:'center'},
            {field: 'is_show', title: '状态',templet:'#is_show',width:'10%',align:'center'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'15%',align:'center'},
        ];
    });
    //自定义方法
    var action= {
        set_customize: function (field, id, value) {
            layList.baseGet(layList.Url({
                c: 'setting.system_group_data',
                a: 'set_customize',
                q: {field: field, id: id, value: value}
            }), function (res) {
                layList.msg(res.msg);
            });
        },
    }
    //查询
    layList.search('search',function(where){
        layList.reload(where);
    });
    layList.switch('is_show',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'setting.system_group_data',a:'set_show',p:{is_show:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'setting.system_group_data',a:'set_show',p:{is_show:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    //快速编辑
    layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'module_name':
                action.set_customize('module_name',id,value);
                break;
            case 'sort':
                action.set_customize('sort',id,value);
                break;
        }
    });
    //监听并执行排序
    layList.sort(['id','sort','product_total'],true);
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'setting.system_group_data',a:'customize_delete',q:{id:data.id}});
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                });
                break;
            case 'open_image':
                $eb.openImage(data.image);
                break;
        }
    })
</script>
{/block}
