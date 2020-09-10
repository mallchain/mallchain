{extend name="public/container"}
{block name="content"}
<div class="layui-fluid" style="background: #fff;margin-top: -10px;">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <form class="layui-form" action="" lay-filter="product_params">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">计费方式：</label>
                                <div class="layui-input-inline">
                                    <select name="type">
                                        <option value="">全部</option>
                                        <option value="1">按重量</option>
                                        <option value="2">按件数</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">模板名称：</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="name" class="layui-input" placeholder="请输入模板名称">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal search" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--产品列表-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <!--<div class="alert alert-info" role="alert">
                        列表[虚拟销量],[库存],[排序]可进行快速修改,双击或者单击进入编辑模式,失去焦点可进行自动保存
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>-->
                    <div class="layui-btn-container">
                        <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create_edit')}',{h:800,w:1100})"><i class="layui-icon">&#xe654;</i>添加模板</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <!--是否默认-->
                    <script type="text/html" id="is_default">
                        {{# if(d.is_default==1){ }}
                        启用
                        {{# }else{ }}
                        不启用
                        {{# } }}
                    </script>
                    <!--启用|禁用-->
                    <script type="text/html" id="checkboxstatus">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='启用|禁用'  {{ d.status == 1 ? '' : 'checked' }}>
                    </script>
                    <!--计费类型-->
                    <script type="text/html" id="type">
                        {{# if(d.type==1){ }}
                        按重量
                        {{# }else{ }}
                        按件数
                        {{# } }}
                    </script>
                    <!--操作-->
                    <script type="text/html" id="act">
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" lay-event='edit'>
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" lay-event='delstor'>
                            <i class="fa fa-warning"></i> 删除
                        </button>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script type="text/javascript" src="{__ADMIN_PATH}util/myUtil.js"></script>
<script>
    var type=1;
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List',"{:Url('temp_ist')}",function (){
        var join=[
            {field: 'id', title: '模板ID', sort: true,event:'id',width:'10%'},
            {field: 'name', title: '模板名称',event:'id',edit:'name'},
            {field: 'type', title: '计费方式',templet:'#type',width:'12%'},
            {field: 'status', title: '状态',templet:'#checkboxstatus',width:'12%'},
            {field: 'is_default', title: '默认运费配置',templet:'#is_default',width:'12%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'13%'},
        ];
        return join;
    });
    //下拉框
    $(document).click(function (e) {
        $('.layui-nav-child').hide();
    });
    //快速编辑
    layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'name':
                action.set_temp('name',id,value);
                break;
        }
    });
    //设置状态
    layList.switch('is_show',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'store.freight_temp',a:'set_status',p:{status:0,id:value}}),function (res) {
                layList.msg(res.msg, function () {
                    layList.reload();
                });
            });
        }else{
            layList.baseGet(layList.Url({c:'store.freight_temp',a:'set_status',p:{status:1,id:value}}),function (res) {
                layList.msg(res.msg, function () {
                    layList.reload();
                });
            });
        }
    });
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'store.freight_temp',a:'delete',q:{id:data.id}});
                var code = {title:"操作提示",text:"确定删除该运费模板吗？",type:'info',confirm:'是的，删除该模板'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.del();
                            layList.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            case 'edit':
                $eb.createModalFrame(data.name+'-编辑',layList.U({a:'create_edit',q:{id:data.id}}),{h:800,w:1100});
                break;
        }
    });
    //排序
    layList.sort(function (obj) {
        var type = obj.type;
        switch (obj.field){
            case 'id':
                layList.reload({order: layList.order(type,'id')},true,null,obj);
                break;
        }
    });
    //查询
    layList.search('search',function(where){
        layList.reload(where);
    });
    //自定义方法
    var action={
        set_temp:function(field,id,value){
            layList.baseGet(layList.Url({c:'store.freight_temp',a:'set_temp',q:{field:field,id:id,value:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    };
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });

</script>
{/block}
