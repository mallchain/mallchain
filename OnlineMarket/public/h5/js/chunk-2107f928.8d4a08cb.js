(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2107f928"],{4316:function(t,e,r){"use strict";r.r(e);var n=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"payment-status"},[!t.isWeixin||t.orderInfo.paid||"offline"==t.orderInfo.pay_type?r("div",{staticClass:"iconfont icon-duihao2 bg-color-red"}):r("div",{staticClass:"iconfont icon-iconfontguanbi fail"}),!t.isWeixin&&"weixin"==t.orderInfo.pay_type||"offline"==t.orderInfo.pay_type?r("div",{staticClass:"status"},[t._v(" 订单创建成功 ")]):t.orderInfo.paid?r("div",{staticClass:"status"},[t._v("订单支付成功")]):r("div",{staticClass:"status"},[t._v("订单支付失败")]),r("div",{staticClass:"wrapper"},[r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[t._v("订单编号")]),r("div",{staticClass:"itemCom",domProps:{textContent:t._s(t.orderInfo.order_id)}})]),r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[t._v("下单时间")]),r("div",{staticClass:"itemCom",domProps:{textContent:t._s(t.orderInfo.add_time)}})]),r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[t._v("支付方式")]),"weixin"==t.orderInfo.pay_type&&2==t.orderInfo.is_channel?r("div",{staticClass:"itemCom"},[t._v(" H5微信支付 ")]):t._e(),"weixin"==t.orderInfo.pay_type&&0==t.orderInfo.is_channel?r("div",{staticClass:"itemCom"},[t._v(" 微信支付 ")]):t._e(),"yue"==t.orderInfo.pay_type?r("div",{staticClass:"itemCom"},[t._v("余额支付")]):t._e(),"offline"==t.orderInfo.pay_type?r("div",{staticClass:"itemCom"},[t._v(" 线下支付 ")]):t._e()]),r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[t._v("支付金额")]),r("div",{staticClass:"itemCom",domProps:{textContent:t._s(t.orderInfo.pay_price)}})]),0==t.orderInfo.paid&&"offline"!=t.orderInfo.pay_type&&t.isWeixin&&t.msgContent?r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[t._v("失败原因")]),r("div",{staticClass:"itemCom",domProps:{textContent:t._s(t.msgContent)}})]):t._e()]),"weixin"==t.orderInfo.pay_type&&2==t.orderInfo.is_channel?r("div",[r("div",{staticClass:"returnBnt bg-color-red",on:{click:function(e){return t.goPages()}}},[t._v("查看支付结果")]),r("div",{staticClass:"returnBnt cart-color",on:{click:function(e){return t.goPages()}}},[t._v(" 支付失败重新支付 ")])]):r("div",[r("div",{staticClass:"returnBnt bg-color-red",on:{click:function(e){return t.goPages()}}},[t._v("查看订单")]),r("div",{staticClass:"returnBnt cart-color",on:{click:function(e){return t.goPages("/")}}},[t._v("返回首页")])])])},o=[],i=(r("8e6e"),r("ac6a"),r("456d"),r("bd86")),s=(r("7f7f"),r("2f62")),a=r("f8b7"),c=r("ed08");function d(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function u(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?d(Object(r),!0).forEach((function(e){Object(i["a"])(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):d(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var f="PayMentStatus",p={name:f,props:{},data:function(){return{id:"",status:0,msgContent:"",orderInfo:{},isWeixin:Object(c["d"])()}},watch:{$route:function(t){t.query.msg&&(this.msgContent=t.query.msg),t.name===f&&this.id!==t.params.id&&(this.id=t.params.id,this.status=parseInt(t.params.status),this.getOrderInfo())}},computed:u({},Object(s["b"])(["userInfo"])),mounted:function(){this.id=this.$route.params.id,this.msgContent=this.$route.query.msg,this.status=parseInt(this.$route.params.status),this.getOrderInfo()},methods:{goPages:function(t){var e=void 0!==t?t:"/order/detail/"+this.orderInfo.order_id;return 5===this.status&&void 0===t&&(e="/order/list/0"),this.$router.push({path:e})},getOrderInfo:function(){var t=this;Object(a["i"])(this.id).then((function(e){t.orderInfo=e.data,t.isWeixin?document.title=t.orderInfo.paid?"支付成功":"支付失败":document.title="订单创建成功"}))}}},l=p,v=r("2877"),m=Object(v["a"])(l,n,o,!1,null,null,null);e["default"]=m.exports},f8b7:function(t,e,r){"use strict";r.d(e,"m",(function(){return o})),r.d(e,"l",(function(){return i})),r.d(e,"e",(function(){return s})),r.d(e,"b",(function(){return a})),r.d(e,"f",(function(){return c})),r.d(e,"g",(function(){return d})),r.d(e,"a",(function(){return u})),r.d(e,"i",(function(){return f})),r.d(e,"h",(function(){return p})),r.d(e,"n",(function(){return l})),r.d(e,"o",(function(){return v})),r.d(e,"c",(function(){return m})),r.d(e,"d",(function(){return _})),r.d(e,"k",(function(){return y})),r.d(e,"j",(function(){return C}));var n=r("b775");function o(t){return n["a"].post("/order/confirm",{cartId:t})}function i(t,e){return n["a"].post("/order/computed/"+t,e)}function s(t){return n["a"].get("/coupons/order/"+(parseFloat(t)||0))}function a(t,e){return n["a"].post("/order/create/"+t,e||{})}function c(){return n["a"].get("/order/data")}function d(t){return n["a"].get("/order/list",t)}function u(t){return n["a"].post("/order/cancel",{id:t})}function f(t){return n["a"].get("/order/detail/"+t)}function p(){return n["a"].get("/order/refund/reason")}function l(t){return n["a"].post("/order/refund/verify",t)}function v(t){return n["a"].post("/order/take",{uni:t})}function m(t){return n["a"].post("/order/del",{uni:t})}function _(t){return n["a"].get("/order/express/"+t)}function y(t,e,r){return n["a"].post("/order/pay",{uni:t,paytype:e,from:r})}function C(t,e){return n["a"].post("/order/order_verific",{verify_code:t,is_confirm:e})}}}]);
//# sourceMappingURL=chunk-2107f928.8d4a08cb.js.map