(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3f565f36"],{"50fc":function(e,t,r){"use strict";r.d(t,"d",(function(){return i})),r.d(t,"e",(function(){return a})),r.d(t,"c",(function(){return s})),r.d(t,"h",(function(){return o})),r.d(t,"i",(function(){return c})),r.d(t,"b",(function(){return d})),r.d(t,"a",(function(){return u})),r.d(t,"g",(function(){return l})),r.d(t,"f",(function(){return v})),r.d(t,"j",(function(){return p})),r.d(t,"k",(function(){return f}));var n=r("b775");function i(){return n["a"].get("/admin/order/statistics",{},{login:!0})}function a(e){return n["a"].get("/admin/order/data",e,{login:!0})}function s(e){return n["a"].get("/admin/order/list",e,{login:!0})}function o(e){return n["a"].post("/admin/order/price",e,{login:!0})}function c(e){return n["a"].post("/admin/order/remark",e,{login:!0})}function d(e){return n["a"].get("/admin/order/detail/"+e,{},{login:!0})}function u(e){return n["a"].get("/admin/order/delivery/gain/"+e,{},{login:!0})}function l(e){return n["a"].post("/admin/order/delivery/keep",e,{login:!0})}function v(e){return n["a"].get("/admin/order/time",e,{login:!0})}function p(e){return n["a"].post("/admin/order/offline",e,{login:!0})}function f(e){return n["a"].post("/admin/order/refund",e,{login:!0})}},"61f7":function(e,t,r){"use strict";r.d(t,"e",(function(){return o})),r.d(t,"a",(function(){return v})),r.d(t,"d",(function(){return p})),r.d(t,"b",(function(){return _}));r("8e6e"),r("ac6a"),r("456d");var n=r("bd86");r("a481");function i(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function a(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?i(Object(r),!0).forEach((function(t){Object(n["a"])(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):i(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var s=function(e,t){e.message=function(e){return t.replace("%s",e||"")}};function o(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return a({required:!0,message:e,type:"string"},t)}function c(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return a({type:"url",message:e},t)}function d(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return a({type:"email",message:e},t)}function u(e){return w.pattern(/^[\w]+$/,e)}function l(e){return w.pattern(/^[\w\d_-]+$/,e)}function v(e){return w.pattern(/^[\w\d]+$/,e)}function p(e){return w.pattern(/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/,e)}function f(e){return w.pattern(/^[\u4e00-\u9fa5]+$/,e)}function m(e){return w.pattern(/^[\u4e00-\u9fa5\w]+$/,e)}function g(e){return w.pattern(/^[\u4e00-\u9fa5\w\d]+$/,e)}function y(e){return w.pattern(/^[\u4e00-\u9fa5\w\d-_]+$/,e)}function _(e){return w.pattern(/^1(3|4|5|7|8|9|6)\d{9}$/i,e)}s(o,"请输入%s"),s(c,"请输入正确的链接"),s(d,"请输入正确的邮箱地址"),s(u,"%s必须是字母"),s(l,"%s只能包含由字母、数字，以及 - 和 _"),s(v,"%s只能包含字母、数字"),s(p,"%s格式不正确"),s(f,"%s只能是汉字"),s(m,"%s只能包含汉字、字母"),s(g,"%s只能包含汉字、字母和数字"),s(y,"%s只能包含由汉字、字母、数字，以及 - 和 _"),s(_,"请输入正确的手机号码");var h={min:"%s最小长度为:min",max:"%s最大长度为:max",length:"%s长度必须为:length",range:"%s长度为:range",pattern:"$s格式错误"},w=Object.keys(h).reduce((function(e,t){return e[t]=function(e){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{},s="range"===t?{min:e[0],max:e[1]}:Object(n["a"])({},t,e);return a({message:r.replace(":".concat(t),"range"===t?"".concat(e[0],"-").concat(e[1]):e),type:"string"},s,{},i)},s(e[t],h[t]),e}),{});t["c"]=w},afa3:function(e,t,r){e.exports=r.p+"h5/img/line.05bf1c84.jpg"},da81:function(e,t,r){"use strict";r.r(t);var n=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"deliver-goods"},[r("header",[r("div",{staticClass:"order-num acea-row row-between-wrapper"},[r("div",{staticClass:"num line1"},[e._v("订单号："+e._s(e.order_id))]),r("div",{staticClass:"name line1"},[r("span",{staticClass:"iconfont icon-yonghu2"}),e._v(e._s(e.delivery.nickname)+" ")])]),r("div",{staticClass:"address"},[r("div",{staticClass:"name"},[e._v(" "+e._s(e.delivery.real_name)),r("span",{staticClass:"phone"},[e._v(e._s(e.delivery.user_phone))])]),r("div",[e._v(e._s(e.delivery.user_address))])]),e._m(0)]),r("div",{staticClass:"wrapper"},[r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[e._v("发货方式")]),r("div",{staticClass:"mode acea-row row-middle row-right"},e._l(e.types,(function(t,n){return r("div",{key:n,staticClass:"goods",class:e.active===n?"on":"",on:{click:function(r){return e.changeType(t,n)}}},[e._v(" "+e._s(t.title)),r("span",{staticClass:"iconfont icon-xuanzhong2"})])})),0)]),r("div",{directives:[{name:"show",rawName:"v-show",value:0===e.active,expression:"active === 0"}],staticClass:"list"},[r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[e._v("发货方式")]),r("select",{directives:[{name:"model",rawName:"v-model",value:e.delivery_name,expression:"delivery_name"}],staticClass:"mode",on:{change:function(t){var r=Array.prototype.filter.call(t.target.options,(function(e){return e.selected})).map((function(e){var t="_value"in e?e._value:e.value;return t}));e.delivery_name=t.target.multiple?r:r[0]}}},[r("option",{attrs:{value:""}},[e._v("选择快递公司")]),e._l(e.logistics,(function(t,n){return r("option",{key:n,domProps:{value:t.name}},[e._v(e._s(t.name))])}))],2),r("span",{staticClass:"iconfont icon-up"})]),r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[e._v("快递单号")]),r("input",{directives:[{name:"model",rawName:"v-model",value:e.delivery_id,expression:"delivery_id"}],staticClass:"mode",attrs:{type:"text",placeholder:"填写快递单号"},domProps:{value:e.delivery_id},on:{input:function(t){t.target.composing||(e.delivery_id=t.target.value)}}})])]),r("div",{directives:[{name:"show",rawName:"v-show",value:1===e.active,expression:"active === 1"}],staticClass:"list"},[r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[e._v("送货人")]),r("input",{directives:[{name:"model",rawName:"v-model",value:e.delivery_name,expression:"delivery_name"}],staticClass:"mode",attrs:{type:"text",placeholder:"填写送货人"},domProps:{value:e.delivery_name},on:{input:function(t){t.target.composing||(e.delivery_name=t.target.value)}}})]),r("div",{staticClass:"item acea-row row-between-wrapper"},[r("div",[e._v("送货电话")]),r("input",{directives:[{name:"model",rawName:"v-model",value:e.delivery_id,expression:"delivery_id"}],staticClass:"mode",attrs:{type:"text",placeholder:"填写送货电话"},domProps:{value:e.delivery_id},on:{input:function(t){t.target.composing||(e.delivery_id=t.target.value)}}})])])]),r("div",{staticStyle:{height:"1.2rem"}}),r("div",{staticClass:"confirm",on:{click:e.saveInfo}},[e._v("确认提交")])])},i=[function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"line"},[n("img",{attrs:{src:r("afa3")}})])}],a=(r("96cf"),r("3b8d")),s=r("50fc"),o=r("e876"),c=r("61f7"),d=r("cba2"),u={name:"GoodsDeliver",components:{},props:{},data:function(){return{types:[{type:"express",title:"发货"},{type:"send",title:"送货"},{type:"fictitious",title:"无需发货"}],active:0,order_id:"",delivery:[],logistics:[],delivery_type:"express",delivery_name:"",delivery_id:""}},watch:{"$route.params.oid":function(e){var t=this;void 0!=e&&(t.order_id=e,t.getIndex())}},mounted:function(){this.order_id=this.$route.params.oid,this.getIndex(),this.getLogistics()},methods:{changeType:function(e,t){this.active=t,this.delivery_type=e.type,this.delivery_name="",this.delivery_id=""},getIndex:function(){var e=this;Object(s["a"])(e.order_id).then((function(t){e.delivery=t.data}),(function(t){e.$dialog.error(t.msg)}))},getLogistics:function(){var e=this;Object(o["h"])().then((function(t){e.logistics=t.data}),(function(t){e.$dialog.error(t.msg)}))},saveInfo:function(){var e=Object(a["a"])(regeneratorRuntime.mark((function e(){var t,r,n,i,a;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:t=this,r=t.delivery_type,n=t.delivery_name,i=t.delivery_id,a={},a.order_id=t.order_id,a.delivery_type=t.delivery_type,e.t0=r,e.next="send"===e.t0?6:"express"===e.t0?18:"fictitious"===e.t0?30:32;break;case 6:return e.prev=6,e.next=9,this.$validator({delivery_name:[Object(c["e"])(c["e"].message("快递公司"))],delivery_id:[Object(c["e"])(c["e"].message("快递单号"))]}).validate({delivery_name:n,delivery_id:i});case 9:e.next=14;break;case 11:return e.prev=11,e.t1=e["catch"](6),e.abrupt("return",Object(d["b"])(e.t1));case 14:return a.delivery_name=n,a.delivery_id=i,t.setInfo(a),e.abrupt("break",32);case 18:return e.prev=18,e.next=21,this.$validator({delivery_name:[Object(c["e"])(c["e"].message("发货人姓名"))],delivery_id:[Object(c["e"])(c["e"].message("发货人电话"))]}).validate({delivery_name:n,delivery_id:i});case 21:e.next=26;break;case 23:return e.prev=23,e.t2=e["catch"](18),e.abrupt("return",Object(d["b"])(e.t2));case 26:return a.delivery_name=n,a.delivery_id=i,t.setInfo(a),e.abrupt("break",32);case 30:return t.setInfo(a),e.abrupt("break",32);case 32:case"end":return e.stop()}}),e,this,[[6,11],[18,23]])})));function t(){return e.apply(this,arguments)}return t}(),setInfo:function(e){var t=this;console.log(e),Object(s["g"])(e).then((function(e){t.$dialog.success(e.msg),t.$router.go(-1)}),(function(e){t.$dialog.error(e.msg)}))}}},l=u,v=r("2877"),p=Object(v["a"])(l,n,i,!1,null,null,null);t["default"]=p.exports}}]);
//# sourceMappingURL=chunk-3f565f36.69303fed.js.map