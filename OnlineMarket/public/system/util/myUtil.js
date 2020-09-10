// 自定义方法-表单序列化
jQuery.prototype.serializeObject=function(){
    var obj=new Object();
    $.each(this.serializeArray(),function(index,param){
        if(!(param.name in obj)){
            obj[param.name]=param.value;
        }
    });
    return obj;
}

//加载层
function loadingIndex(msg){
    var loading = layList.msg(msg, {
        icon: 16
        ,shade: 0.1
        ,time:0
    });
    return loading;
}

//列表刷新当前页
function reloadCurrent() {
    var where = $('.layui-form').serializeObject();
    layList.reload(where);
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