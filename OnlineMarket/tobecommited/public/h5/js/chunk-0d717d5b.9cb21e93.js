(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0d717d5b"],{"50fc":function(e,t,n){"use strict";n.d(t,"d",(function(){return a})),n.d(t,"e",(function(){return i})),n.d(t,"c",(function(){return s})),n.d(t,"h",(function(){return o})),n.d(t,"i",(function(){return c})),n.d(t,"b",(function(){return u})),n.d(t,"a",(function(){return d})),n.d(t,"g",(function(){return l})),n.d(t,"f",(function(){return f})),n.d(t,"j",(function(){return p})),n.d(t,"k",(function(){return v}));var r=n("b775");function a(){return r["a"].get("/admin/order/statistics",{},{login:!0})}function i(e){return r["a"].get("/admin/order/data",e,{login:!0})}function s(e){return r["a"].get("/admin/order/list",e,{login:!0})}function o(e){return r["a"].post("/admin/order/price",e,{login:!0})}function c(e){return r["a"].post("/admin/order/remark",e,{login:!0})}function u(e){return r["a"].get("/admin/order/detail/"+e,{},{login:!0})}function d(e){return r["a"].get("/admin/order/delivery/gain/"+e,{},{login:!0})}function l(e){return r["a"].post("/admin/order/delivery/keep",e,{login:!0})}function f(e){return r["a"].get("/admin/order/time",e,{login:!0})}function p(e){return r["a"].post("/admin/order/offline",e,{login:!0})}function v(e){return r["a"].post("/admin/order/refund",e,{login:!0})}},"61f7":function(e,t,n){"use strict";n.d(t,"e",(function(){return o})),n.d(t,"a",(function(){return f})),n.d(t,"d",(function(){return p})),n.d(t,"b",(function(){return _}));n("8e6e"),n("ac6a"),n("456d");var r=n("bd86");n("a481");function a(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function i(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?a(Object(n),!0).forEach((function(t){Object(r["a"])(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):a(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var s=function(e,t){e.message=function(e){return t.replace("%s",e||"")}};function o(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return i({required:!0,message:e,type:"string"},t)}function c(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return i({type:"url",message:e},t)}function u(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return i({type:"email",message:e},t)}function d(e){return b.pattern(/^[\w]+$/,e)}function l(e){return b.pattern(/^[\w\d_-]+$/,e)}function f(e){return b.pattern(/^[\w\d]+$/,e)}function p(e){return b.pattern(/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/,e)}function v(e){return b.pattern(/^[\u4e00-\u9fa5]+$/,e)}function m(e){return b.pattern(/^[\u4e00-\u9fa5\w]+$/,e)}function g(e){return b.pattern(/^[\u4e00-\u9fa5\w\d]+$/,e)}function h(e){return b.pattern(/^[\u4e00-\u9fa5\w\d-_]+$/,e)}function _(e){return b.pattern(/^1(3|4|5|7|8|9|6)\d{9}$/i,e)}s(o,"请输入%s"),s(c,"请输入正确的链接"),s(u,"请输入正确的邮箱地址"),s(d,"%s必须是字母"),s(l,"%s只能包含由字母、数字，以及 - 和 _"),s(f,"%s只能包含字母、数字"),s(p,"%s格式不正确"),s(v,"%s只能是汉字"),s(m,"%s只能包含汉字、字母"),s(g,"%s只能包含汉字、字母和数字"),s(h,"%s只能包含由汉字、字母、数字，以及 - 和 _"),s(_,"请输入正确的手机号码");var w={min:"%s最小长度为:min",max:"%s最大长度为:max",length:"%s长度必须为:length",range:"%s长度为:range",pattern:"$s格式错误"},b=Object.keys(w).reduce((function(e,t){return e[t]=function(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{},s="range"===t?{min:e[0],max:e[1]}:Object(r["a"])({},t,e);return i({message:n.replace(":".concat(t),"range"===t?"".concat(e[0],"-").concat(e[1]):e),type:"string"},s,{},a)},s(e[t],w[t]),e}),{});t["c"]=b},"6c59":function(e,t,n){},"7cb6":function(e,t,n){"use strict";n.r(t);var r=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{ref:"container",staticClass:"pos-order-list"},[n("div",{staticClass:"nav acea-row row-around row-middle"},[n("div",{staticClass:"item",class:0==e.where.status?"on":"",on:{click:function(t){return e.changeStatus(0)}}},[e._v(" 待付款 ")]),n("div",{staticClass:"item",class:1==e.where.status?"on":"",on:{click:function(t){return e.changeStatus(1)}}},[e._v(" 待发货 ")]),n("div",{staticClass:"item",class:2==e.where.status?"on":"",on:{click:function(t){return e.changeStatus(2)}}},[e._v(" 待收货 ")]),n("div",{staticClass:"item",class:3==e.where.status?"on":"",on:{click:function(t){return e.changeStatus(3)}}},[e._v(" 待评价 ")]),n("div",{staticClass:"item",class:4==e.where.status?"on":"",on:{click:function(t){return e.changeStatus(4)}}},[e._v(" 已完成 ")]),n("div",{staticClass:"item",class:-3==e.where.status?"on":"",on:{click:function(t){return e.changeStatus(-3)}}},[e._v(" 退款 ")])]),n("div",{staticClass:"list"},e._l(e.list,(function(t,r){return n("div",{key:r,staticClass:"item"},[n("div",{staticClass:"order-num acea-row row-middle",on:{click:function(n){return e.toDetail(t)}}},[e._v(" 订单号："+e._s(t.order_id)+" "),n("span",{staticClass:"time"},[e._v("下单时间："+e._s(t.add_time))])]),e._l(t._info,(function(r,a){return n("div",{key:a,staticClass:"pos-order-goods"},[n("div",{staticClass:"goods acea-row row-between-wrapper",on:{click:function(n){return e.toDetail(t)}}},[n("div",{staticClass:"picTxt acea-row row-between-wrapper"},[n("div",{staticClass:"pictrue"},[n("img",{attrs:{src:r.cart_info.productInfo.image}})]),n("div",{staticClass:"text acea-row row-between row-column"},[n("div",{staticClass:"info line2"},[e._v(" "+e._s(r.cart_info.productInfo.store_name)+" ")]),r.cart_info.productInfo.suk?n("div",{staticClass:"attr"},[e._v(" "+e._s(r.cart_info.productInfo.suk)+" ")]):e._e()])]),n("div",{staticClass:"money"},[n("div",{staticClass:"x-money"},[e._v("￥"+e._s(r.cart_info.productInfo.price))]),n("div",{staticClass:"num"},[e._v("x"+e._s(r.cart_info.cart_num))]),n("div",{staticClass:"y-money"},[e._v(" ￥"+e._s(r.cart_info.productInfo.ot_price)+" ")])])])])})),n("div",{staticClass:"public-total"},[e._v(" 共"+e._s(t.total_num)+"件商品，应支付 "),n("span",{staticClass:"money"},[e._v("￥"+e._s(t.pay_price))]),e._v(" ( 邮费 ¥"+e._s(t.total_postage)+" ) ")]),n("div",{staticClass:"operation acea-row row-between-wrapper"},[n("div",{staticClass:"more"}),n("div",{staticClass:"acea-row row-middle"},[0==e.where.status?n("div",{staticClass:"bnt",on:{click:function(n){return e.modify(t,0)}}},[e._v(" 一键改价 ")]):e._e(),n("div",{staticClass:"bnt",on:{click:function(n){return e.modify(t,1)}}},[e._v("订单备注")]),-3==e.where.status&&1===t.refund_status?n("div",{staticClass:"bnt",on:{click:function(n){return e.modify(t,0)}}},[e._v(" 立即退款 ")]):e._e(),"offline"===t.pay_type&&0===t.paid?n("div",{staticClass:"bnt cancel",on:{click:function(n){return e.offlinePay(t)}}},[e._v(" 确认付款 ")]):e._e(),1==e.where.status?n("router-link",{staticClass:"bnt",attrs:{to:"/customer/delivery/"+t.order_id}},[e._v("去发货 ")]):e._e()],1)])],2)})),0),n("Loading",{attrs:{loaded:e.loaded,loading:e.loading}}),n("PriceChange",{attrs:{change:e.change,orderInfo:e.orderInfo,status:e.status},on:{closechange:function(t){return e.changeclose(t)},savePrice:e.savePrice}})],1)},a=[],i=(n("96cf"),n("3b8d")),s=n("de46"),o=n("3a5e"),c=n("50fc"),u=n("61f7"),d=n("cba2"),l={name:"AdminOrderList",components:{PriceChange:s["a"],Loading:o["a"]},props:{},data:function(){return{current:"",change:!1,types:0,where:{page:1,limit:5,status:0},list:[],loaded:!1,loading:!1,orderInfo:{},status:""}},watch:{"$route.params.types":function(e){var t=this;void 0!=e&&(t.where.status=e,t.init())},types:function(){this.getIndex()}},mounted:function(){var e=this;e.where.status=e.$route.params.types,e.current="",e.getIndex(),e.$scroll(e.$refs.container,(function(){!e.loading&&e.getIndex()}))},methods:{more:function(e){this.current===e?this.current="":this.current=e},modify:function(e,t){this.change=!0,this.orderInfo=e,this.status=t},changeclose:function(e){this.change=e},savePrice:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n,r,a,i,s,o;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(n=this,r={},a=t.price,i=t.refund_price,s=n.orderInfo.refund_status,o=t.remark,r.order_id=n.orderInfo.order_id,0!=n.status||0!==s){e.next=15;break}return e.prev=3,e.next=6,this.$validator({price:[Object(u["e"])(u["e"].message("金额")),Object(u["d"])(u["d"].message("金额"))]}).validate({price:a});case 6:e.next=11;break;case 8:return e.prev=8,e.t0=e["catch"](3),e.abrupt("return",Object(d["b"])(e.t0));case 11:r.price=a,Object(c["h"])(r).then((function(){n.change=!1,n.$dialog.success("改价成功"),n.init()}),(function(){n.change=!1,n.$dialog.error("改价失败")})),e.next=39;break;case 15:if(0!=n.status||1!==s){e.next=29;break}return e.prev=16,e.next=19,this.$validator({refund_price:[Object(u["e"])(u["e"].message("金额")),Object(u["d"])(u["d"].message("金额"))]}).validate({refund_price:i});case 19:e.next=24;break;case 21:return e.prev=21,e.t1=e["catch"](16),e.abrupt("return",Object(d["b"])(e.t1));case 24:r.price=i,r.type=t.type,Object(c["k"])(r).then((function(e){n.change=!1,n.$dialog.success(e.msg),n.init()}),(function(e){n.change=!1,n.$dialog.error(e.msg)})),e.next=39;break;case 29:return e.prev=29,e.next=32,this.$validator({remark:[Object(u["e"])(u["e"].message("备注"))]}).validate({remark:o});case 32:e.next=37;break;case 34:return e.prev=34,e.t2=e["catch"](29),e.abrupt("return",Object(d["b"])(e.t2));case 37:r.remark=o,Object(c["i"])(r).then((function(e){n.change=!1,n.$dialog.success(e.msg),n.init()}),(function(e){n.change=!1,n.$dialog.error(e.msg)}));case 39:case"end":return e.stop()}}),e,this,[[3,8],[16,21],[29,34]])})));function t(t){return e.apply(this,arguments)}return t}(),init:function(){this.list=[],this.where.page=1,this.loaded=!1,this.loading=!1,this.getIndex(),this.current=""},getIndex:function(){var e=this;e.loading||e.loaded||(e.loading=!0,Object(c["c"])(e.where).then((function(t){e.loading=!1,e.loaded=t.data.length<e.where.limit,e.list.push.apply(e.list,t.data),e.where.page=e.where.page+1}),(function(t){e.$dialog.error(t.msg)})))},changeStatus:function(e){this.where.status!=e&&(this.where.status=e,this.init())},toDetail:function(e){this.$router.push({path:"/customer/orderdetail/"+e.order_id})},offlinePay:function(e){var t=this;console.log(e),Object(c["j"])({order_id:e.order_id}).then((function(e){t.$dialog.success(e.msg),t.init()}),(function(e){t.$dialog.error(e.msg)}))}}},f=l,p=n("2877"),v=Object(p["a"])(f,r,a,!1,null,null,null);t["default"]=v.exports},a716:function(e,t,n){"use strict";var r=n("6c59"),a=n.n(r);a.a},de46:function(e,t,n){"use strict";var r=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"priceChange",class:!0===e.change?"on":""},[n("div",{staticClass:"priceTitle"},[e._v(" "+e._s(0==e.status?1===e.orderInfo.refund_status?"立即退款":"一键改价":"订单备注")+" "),n("span",{staticClass:"iconfont icon-guanbi",on:{click:e.close}})]),0==e.status?n("div",{staticClass:"listChange"},[0===e.orderInfo.refund_status?n("div",{staticClass:"item acea-row row-between-wrapper"},[n("div",[e._v("商品总价(¥)")]),n("div",{staticClass:"money"},[e._v(" "+e._s(e.orderInfo.total_price)),n("span",{staticClass:"iconfont icon-suozi"})])]):e._e(),0===e.orderInfo.refund_status?n("div",{staticClass:"item acea-row row-between-wrapper"},[n("div",[e._v("原始邮费(¥)")]),n("div",{staticClass:"money"},[e._v(" "+e._s(e.orderInfo.pay_postage)),n("span",{staticClass:"iconfont icon-suozi"})])]):e._e(),0===e.orderInfo.refund_status?n("div",{staticClass:"item acea-row row-between-wrapper"},[n("div",[e._v("实际支付(¥)")]),n("div",{staticClass:"money"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.price,expression:"price"}],class:!0===e.focus?"on":"",attrs:{type:"text"},domProps:{value:e.price},on:{focus:e.priceChange,input:function(t){t.target.composing||(e.price=t.target.value)}}})])]):e._e(),1===e.orderInfo.refund_status?n("div",{staticClass:"item acea-row row-between-wrapper"},[n("div",[e._v("实际支付(¥)")]),n("div",{staticClass:"money"},[e._v(" "+e._s(e.orderInfo.pay_price)),n("span",{staticClass:"iconfont icon-suozi"})])]):e._e(),1===e.orderInfo.refund_status?n("div",{staticClass:"item acea-row row-between-wrapper"},[n("div",[e._v("退款金额(¥)")]),n("div",{staticClass:"money"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.refund_price,expression:"refund_price"}],class:!0===e.focus?"on":"",attrs:{type:"text"},domProps:{value:e.refund_price},on:{focus:e.priceChange,input:function(t){t.target.composing||(e.refund_price=t.target.value)}}})])]):e._e()]):n("div",{staticClass:"listChange"},[n("textarea",{directives:[{name:"model",rawName:"v-model",value:e.remark,expression:"remark"}],attrs:{placeholder:e.orderInfo.remark?e.orderInfo.remark:"请填写备注信息..."},domProps:{value:e.remark},on:{input:function(t){t.target.composing||(e.remark=t.target.value)}}})]),n("div",{staticClass:"modify",on:{click:e.save}},[e._v(" "+e._s(0===e.orderInfo.refund_status?"立即修改":"确认退款")+" ")]),1===e.orderInfo.refund_status?n("div",{staticClass:"modify1",on:{click:e.refuse}},[e._v(" 拒绝退款 ")]):e._e()]),n("div",{directives:[{name:"show",rawName:"v-show",value:!0===e.change,expression:"change === true"}],staticClass:"mask",on:{touchmove:function(e){e.preventDefault()}}})])},a=[],i={name:"PriceChange",components:{},props:{change:Boolean,orderInfo:Object,status:String},data:function(){return{focus:!1,price:0,refund_price:0,remark:""}},watch:{orderInfo:function(){this.price=this.orderInfo.pay_price,this.refund_price=this.orderInfo.pay_price,this.remark=""}},mounted:function(){},methods:{priceChange:function(){this.focus=!0},close:function(){this.price=this.orderInfo.pay_price,this.$emit("closechange",!1)},save:function(){var e=this;e.$emit("savePrice",{price:e.price,refund_price:e.refund_price,type:1,remark:e.remark})},refuse:function(){var e=this;e.$emit("savePrice",{price:e.price,refund_price:e.refund_price,type:2,remark:e.remark})}}},s=i,o=(n("a716"),n("2877")),c=Object(o["a"])(s,r,a,!1,null,"55fd55d7",null);t["a"]=c.exports}}]);
//# sourceMappingURL=chunk-0d717d5b.9cb21e93.js.map