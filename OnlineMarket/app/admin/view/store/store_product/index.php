{extend name="public/container"}
{block name="content"}
<div class="layui-fluid" style="background: #fff;margin-top: -10px;">
    <div class="layui-tab layui-tab-brief" lay-filter="tab">
        <ul class="layui-tab-title">
            <li lay-id="list" {eq name='type' value='1'}class="layui-this" {/eq} >
                <a href="{eq name='type' value='1'}javascript:;{else}{:Url('index',['type'=>1])}{/eq}">出售中产品({$onsale})</a>
            </li>
            <li lay-id="list" {eq name='type' value='2'}class="layui-this" {/eq}>
                <a href="{eq name='type' value='2'}javascript:;{else}{:Url('index',['type'=>2])}{/eq}">待上架产品({$forsale})</a>
            </li>
            <li lay-id="list" {eq name='type' value='3'}class="layui-this" {/eq}>
                <a href="{eq name='type' value='3'}javascript:;{else}{:Url('index',['type'=>3])}{/eq}">仓库中产品({$warehouse})</a>
            </li>
            <li lay-id="list" {eq name='type' value='4'}class="layui-this" {/eq}>
                <a href="{eq name='type' value='4'}javascript:;{else}{:Url('index',['type'=>4])}{/eq}">已经售馨产品({$outofstock})</a>
            </li>
            <li lay-id="list" {eq name='type' value='5'}class="layui-this" {/eq}>
                <a href="{eq name='type' value='5'}javascript:;{else}{:Url('index',['type'=>5])}{/eq}">警戒库存({$policeforce})</a>
            </li>
            <li lay-id="list" {eq name='type' value='6'}class="layui-this" {/eq}>
                <a href="{eq name='type' value='6'}javascript:;{else}{:Url('index',['type'=>6])}{/eq}">产品回收站({$recycle})</a>
            </li>
        </ul>
    </div>
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <form class="layui-form" action="" lay-filter="product_params">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">活动标签：</label>
                                <div class="layui-input-inline">
                                    <select name="activity">
                                        <option value=" ">全部</option>
                                        <option value="is_seckill">秒杀</option>
                                        <option value="is_bargain">砍价</option>
                                        <option value="is_hot">热销榜单</option>
                                        <option value="is_benefit">促销单品</option>
                                        <option value="is_best">精品推荐</option>
                                        <option value="is_new">首发新品</option>
                                        <option value="is_good">优品推荐</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">商品分类：</label>
                                <div class="layui-input-inline">
                                    <select name="cate_id">
                                        <option value=" ">全部</option>
                                        {volist name='cate' id='vo'}
                                        <option value="{$vo.id}">{$vo.html}{$vo.cate_name}</option>
                                        {/volist}
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">售价区间：</label>
                                <div class="layui-input-inline" style="width: 83px;">
                                    <input type="text" name="price_min" placeholder="￥" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-form-mid">-</div>
                                <div class="layui-input-inline" style="width: 83px;">
                                    <input type="text" name="price_max" placeholder="￥" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">利润率区间：</label>
                                <div class="layui-input-inline" style="width: 83px;">
                                    <input type="text" name="profit_min" placeholder="%" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-form-mid">-</div>
                                <div class="layui-input-inline" style="width: 83px;">
                                    <input type="text" name="profit_max" placeholder="%" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">产品名称：</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="store_name" class="layui-input" placeholder="请输入产品名称,关键字,编号">
                                    <input type="hidden" name="type" value="{$type}">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal search" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                    <button class="layui-btn layui-btn-primary layui-btn-sm export"  lay-submit="export" lay-filter="export">
                                        <i class="fa fa-floppy-o" style="margin-right: 3px;"></i>导出</button>
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
                    <div class="alert alert-info" role="alert">
                        列表[虚拟销量],[库存],[排序]可进行快速修改,双击或者单击进入编辑模式,失去焦点可进行自动保存
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="layui-btn-container">
                        {switch name='type'}
                            {case value="1"}
                                <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}',{h:700,w:1100})">添加产品</button>
                                <button class="layui-btn layui-btn-sm" data-type="unshow">批量下架</button>
                                <button class="layui-btn layui-btn-sm" hidden onclick="$eb.createModalFrame(this.innerText,'{:Url('get_gwgoods_progress')}',{h:450,w:500})">获取供应链商品</button>
                                <button class="layui-btn layui-btn-sm" hidden onclick="$eb.createModalFrame(this.innerText,'{:Url('profit_index')}',{h:360,w:500})">批量调整利润</button>
<!--                                <button hidden class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('store.copy_taobao/index')}',{h:700,w:1100});">复制淘宝、天猫、1688、京东</button>
-->                         {/case}
                            {case value="2"}
                                <button class="layui-btn layui-btn-sm" data-type="show">批量上架</button>
                            {/case}
                            {case value="3"}
                                <button class="layui-btn layui-btn-sm" hidden onclick="$eb.createModalFrame(this.innerText,'{:Url('profit_index')}',{h:360,w:500})">批量调整利润</button>
                            {/case}
                        {/switch}
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <!--图片-->
                    <script type="text/html" id="image">
                        <img style="cursor: pointer" lay-event="open_image" src="{{d.image}}">
                    </script>
                    <!--上架|下架-->
                    <script type="text/html" id="checkboxstatus">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='上架|下架'  {{ d.is_show == 1 ? 'checked' : '' }}>
                    </script>
                    <!--收藏-->
                    <script type="text/html" id="like">
                        <span><i class="layui-icon layui-icon-praise"></i> {{d.like}}</span>
                    </script>
                    <!--点赞-->
                    <script type="text/html" id="collect">
                        <span><i class="layui-icon layui-icon-star"></i> {{d.collect}}</span>
                    </script>
                    <!-- 活动标签 -->
                    <script type="text/html" id="activity">
                        {{# if(d.is_seckill==1){ }}
                        <p>秒杀</p>
                        {{# } }}
                        {{# if(d.is_bargain==1){ }}
                        <p>砍价</p>
                        {{# } }}
                        {{# if(d.is_combination==1){ }}
                        <p>拼团</p>
                        {{# } }}
                        {{# if(d.is_seckill==0 && d.is_bargain==0 && d.is_combination==0){ }}
                        <p>--</p>
                        {{# } }}
                    </script>
                    <!--产品名称-->
                    <script type="text/html" id="store_name">
                        <h4>{{d.store_name}}</h4>
                        <p>VIP现金支付金额:<font color="red">{{d.price}}</font> </p>
                        {{# if(d.use_integral>0 && d.profit_price>0){ }}
                        <p>积分抵扣售价:<font color="red">{{d.profit_price}}</font> </p>
                        {{# } }}
                        {{# if(d.cate_name!=''){ }}
                        <p>分类:{{d.cate_name}}</p>
                        {{# } }}
                    </script>
                    <!--操作-->
                    <script type="text/html" id="act">
                        <p style="height:30px; ">状态：<input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='上架|下架'  {{ d.is_show == 1 ? 'checked' : '' }}></p>
                        <button type="button" class="layui-btn layui-btn-xs btn-success" lay-event='attr' >
                            属性
                        </button>
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" lay-event='edit'>
                            编辑
                        </button>
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
                            <li>
                                <a href="javascript:void(0);" class="" onclick="$eb.createModalFrame(this.innerText,'{:Url('edit_content')}?id={{d.id}}')">
                                    <i class="fa fa-pencil"></i> 产品详情</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" onclick="$eb.createModalFrame(this.innerText,'{:Url('ump.store_seckill/seckill')}?id={{d.id}}')"">
                                <i class="fa fa-gavel"></i> 开启秒杀</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" onclick="$eb.createModalFrame(this.innerText,'{:Url('ump.store_bargain/bargain')}?id={{d.id}}')">
                                    <i class="fa fa-sort-amount-asc"></i> 开启砍价</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" onclick="$eb.createModalFrame(this.innerText,'{:Url('ump.store_combination/combination')}?id={{d.id}}')">
                                    <i class="fa fa-hand-lizard-o"></i> 开启拼团</a>
                            </li>
                            {{# if(d.is_del){ }}
                            <li>
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 恢复产品
                                </a>
                            </li>
                            {{# }else{ }}
                            <li>
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 移到回收站
                                </a>
                            </li>
                            {{# } }}
                            <li>
                                <a href="{:Url('store.storeProductReply/index')}?product_id={{d.id}}">
                                    <i class="fa fa-warning"></i> 评论查看
                                </a>
                            </li>
                        </ul>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script type="text/javascript" src="{__ADMIN_PATH}util/myUtil.js"></script>
<script>
    var type=<?=$type?>;
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List',"{:Url('product_ist',['type'=>$type])}",function (){
        var join=new Array();
        switch (parseInt(type)){
            case 1:case 3:case 4:case 5:
                join=[
                    {type:'checkbox'},
                    {field: 'id', title: 'ID', sort: true,event:'id',width:'6%'},
                    {field: 'image', title: '产品图片',templet:'#image',width:'10%'},
                    {field: 'store_name', title: '产品名称',templet:'#store_name'},
                    {field: 'cost', title: '成本价',event:'cost',width:'6%',style:'color: red;'},
                    {field: 'profit', title: '利润率',event:'profit',width:'8%'},
                    {field: 'stock', title: '库存',edit:'stock',width:'6%'},
                    {field: 'ficti', title: '虚拟销量',edit:'ficti',width:'7%'},
                    {field: 'sales', title: '销量',sort: true,event:'sales',width:'6%'},
                    {field: 'sort', title: '排序',edit:'sort',width:'6%'},
                    //{field: 'collect', title: '点赞',templet:'#like',width:'6%'},
                    //{field: 'like', title: '收藏',templet:'#collect',width:'6%'},
                    {field: 'like', title: '活动标签',align:'center',templet:'#activity',width:'8%'},
                    //{field: 'status', title: '状态',templet:"#checkboxstatus",width:'8%'},
                    {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'13%'},
                ];
                break;
            case 2:
                join=[
                    {type:'checkbox'},
                    {field: 'id', title: 'ID', sort: true,event:'id',width:'6%'},
                    {field: 'image', title: '产品图片',templet:'#image',width:'10%'},
                    {field: 'store_name', title: '产品名称',templet:'#store_name'},
                    {field: 'cost', title: '成本价',edit:'cost',width:'8%',style:'color: red;'},
                    {field: 'profit', title: '利润率',event:'profit',width:'8%'},
                    {field: 'stock', title: '库存',edit:'stock',width:'6%'},
                    {field: 'ficti', title: '虚拟销量',edit:'ficti',width:'8%'},
                    {field: 'sales', title: '销量',sort: true,event:'sales',width:'6%'},
                    {field: 'sort', title: '排序',edit:'sort',width:'6%'},
                    //{field: 'status', title: '状态',templet:"#checkboxstatus",width:'8%'},
                    {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'14%'},
                ];
                break;
            case 6:
                join=[
                    {field: 'id', title: '产品ID', sort: true,event:'id'},
                    {field: 'image', title: '产品图片',templet:'#image'},
                    {field: 'store_name', title: '产品名称',templet:'#store_name'},
                    {field: 'cost', title: '成本价',edit:'cost',style:'color: red;'},
                    {field: 'profit', title: '利润率',event:'profit',width:'8%'},
                    {field: 'stock', title: '库存',edit:'stock'},
                    {field: 'ficti', title: '虚拟销量',edit:'ficti'},
                    {field: 'sales', title: '销量',sort: true,event:'sales'},
                    {field: 'sort', title: '排序',edit:'sort'},
                    //{field: 'status', title: '状态',templet:"#checkboxstatus"},
                    {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'14%'},
                ];
                break;
        }
        return join;
    })
    //excel下载
    layList.search('export',function(where){
        where.excel = 1;
        location.href=layList.U({c:'store.store_product',a:'product_ist',q:where});
    })
    //下拉框
    $(document).click(function (e) {
        $('.layui-nav-child').hide();
    })
    function dropdown(that){
        var oEvent = arguments.callee.caller.arguments[0] || event;
        oEvent.stopPropagation();
        var offset = $(that).offset();
        var top=offset.top-$(window).scrollTop();
        var index = $(that).parents('tr').data('index');
        $('.layui-nav-child').each(function (key) {
            if (key != index) {
                $(this).hide();
            }
        })
        if($(document).height() < top+$(that).next('ul').height()){
            $(that).next('ul').css({
                'padding': 10,
                'top': - ($(that).parent('td').height() / 2 + $(that).height() + $(that).next('ul').height()/2),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }else{
            $(that).next('ul').css({
                'padding': 10,
                'top':$(that).parent('td').height() / 2 + $(that).height(),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }
    }
    //快速编辑
    layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'price':
                action.set_product('price',id,value);
                break;
            case 'stock':
                action.set_product('stock',id,value);
                break;
            case 'sort':
                action.set_product('sort',id,value);
                break;
            case 'ficti':
                action.set_product('ficti',id,value);
                break;
        }
    });
    //上下加产品
    layList.switch('is_show',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'store.store_product',a:'set_show',p:{is_show:1,id:value}}),function (res) {
                layList.msg(res.msg, function () {
                    layList.reload();
                });
            });
        }else{
            layList.baseGet(layList.Url({c:'store.store_product',a:'set_show',p:{is_show:0,id:value}}),function (res) {
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
                var url=layList.U({c:'store.store_product',a:'delete',q:{id:data.id}});
                if(data.is_del) var code = {title:"操作提示",text:"确定恢复产品操作吗？",type:'info',confirm:'是的，恢复该产品'};
                else var code = {title:"操作提示",text:"确定将该产品移入回收站吗？",type:'info',confirm:'是的，移入回收站'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.del();
                            location.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            case 'open_image':
                $eb.openImage(data.image);
                break;
            case 'edit':
                $eb.createModalFrame(data.store_name+'-编辑',layList.U({a:'edit',q:{id:data.id}}),{h:700,w:1100});
                break;
            case 'attr':
                $eb.createModalFrame(data.store_name+'-属性',layList.U({a:'attr',q:{id:data.id}}),{h:600,w:800})
                break;
        }
    })
    //排序
    layList.sort(function (obj) {
        var type = obj.type;
        switch (obj.field){
            case 'id':
                layList.reload({order: layList.order(type,'p.id')},true,null,obj);
                break;
            case 'sales':
                layList.reload({order: layList.order(type,'p.sales')},true,null,obj);
                break;
        }
    });
    //查询
    layList.search('search',function(where){
        layList.reload(where);
    });
    //自定义方法
    var action={
        set_product:function(field,id,value){
            layList.baseGet(layList.Url({c:'store.store_product',a:'set_product',q:{field:field,id:id,value:value}}),function (res) {
                layList.msg(res.msg);
            });
        },
        show:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'store.store_product',a:'product_show'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要上架的产品');
            }
        },
        unshow:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'store.store_product',a:'product_unshow'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要下架的产品');
            }
        }
    };
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });

    var editTotal = 0;
    var totalPage = 1;
    //调整利润弹窗确定调整调用该方法
    function confirmProfitRate(is_all,profit){
        var ids=layList.getCheckData().getIds('id');
        if(is_all == 0 && ids.length == 0){
            layList.msg('请选择要调整利润的产品',{anim: 6});
            return false;
        }
        var loading = loadingIndex('处理中...');
        var params = $('.layui-form').serializeObject();
        params.is_all = is_all;
        params.profit = profit;
        params.ids = ids;
        layList.basePost(layList.Url({c:'store.store_product',a:'getEditProfitTotal'}),params,function (res) {
            var limit = 10000;
            totalPage = Math.ceil(res.data.total / limit);
            params.page = 1;
            params.limit = limit;
            editProfitRate(params);
        },function (res) {
            layList.msg(res.msg);
            layer.close(loading);
        });
    }

    //进行调整利润
    function editProfitRate(params) {
        if(params.page > totalPage && editTotal > 0) {
            layer.alert('成功调整' + editTotal + '个商品利润',{icon:6},function(){
                layList.reload();
                layer.closeAll();
            });
            return false;
        }
        layList.basePost(layList.Url({c:'store.store_product',a:'confirm_profit_rate'}),params,function (res) {
            editTotal += res.data.total;
            params.page += 1;
            if(params.page < totalPage) {
                loadingIndex('成功调整' + editTotal + '个商品利润，未完成请稍候...');
            }
            sleep(500);
            editProfitRate(params);
        },function (res) {
            layList.msg(res.msg,function () {
                layer.closeAll();
            });
        });
    }

    //休息一下
    function sleep(numberMillis){
        var now = new Date();
        var exitTime = now.getTime() + numberMillis;
        while (true) {
            now = new Date();
            if (now.getTime() > exitTime)
                return;
        }
    }
</script>
{/block}
