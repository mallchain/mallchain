(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e14b291c"],{ca41:function(t,n,i){"use strict";i.d(n,"n",(function(){return r})),i.d(n,"m",(function(){return e})),i.d(n,"o",(function(){return o})),i.d(n,"q",(function(){return u})),i.d(n,"p",(function(){return s})),i.d(n,"r",(function(){return c})),i.d(n,"t",(function(){return l})),i.d(n,"s",(function(){return d})),i.d(n,"f",(function(){return f})),i.d(n,"a",(function(){return g})),i.d(n,"h",(function(){return p})),i.d(n,"i",(function(){return b})),i.d(n,"b",(function(){return m})),i.d(n,"e",(function(){return v})),i.d(n,"c",(function(){return h})),i.d(n,"j",(function(){return C})),i.d(n,"d",(function(){return _})),i.d(n,"g",(function(){return k})),i.d(n,"l",(function(){return w})),i.d(n,"k",(function(){return L}));var a=i("b775");function r(t){return a["a"].get("/combination/list",t,{login:!1})}function e(t){return a["a"].get("/combination/detail/"+t,{},{login:!1})}function o(t){return a["a"].get("/combination/pink/"+t)}function u(t){return a["a"].post("/combination/remove",t)}function s(t){return a["a"].post("/combination/poster",t)}function c(){return a["a"].get("/seckill/index",{},{login:!1})}function l(t,n){return a["a"].get("/seckill/list/"+t,n,{login:!1})}function d(t){return a["a"].get("/seckill/detail/"+t,{},{login:!1})}function f(t){return a["a"].get("/bargain/list",t,{login:!1})}function g(t){return a["a"].get("/bargain/detail/"+t)}function p(t){return a["a"].post("/bargain/share",t)}function b(t){return a["a"].post("/bargain/start",t)}function m(t){return a["a"].post("/bargain/help",t)}function v(t){return a["a"].post("/bargain/help/price",t)}function h(t){return a["a"].post("/bargain/help/count",t)}function C(t){return a["a"].post("/bargain/start/user",t)}function _(t){return a["a"].post("/bargain/help/list",t)}function k(t){return a["a"].post("/bargain/poster",t)}function w(t){return a["a"].get("/bargain/user/list",t)}function L(t){return a["a"].post("/bargain/user/cancel",t)}},cbc6:function(t,n,i){"use strict";i.r(n);var a=function(){var t=this,n=t.$createElement,i=t._self._c||n;return i("div",{staticClass:"bargain-list"},[i("div",{staticClass:"header"}),i("div",{staticClass:"list"},[t._l(t.bargainLis,(function(n,a){return i("div",{key:a,staticClass:"item acea-row row-between-wrapper"},[i("div",{staticClass:"pictrue"},[i("img",{attrs:{src:n.image}})]),i("div",{staticClass:"text acea-row row-column-around"},[i("div",{staticClass:"line1",domProps:{textContent:t._s(n.title)}}),i("div",{staticClass:"num"},[i("span",{staticClass:"iconfont icon-pintuan"}),t._v(t._s(n.people)+"人正在参与 ")]),i("div",{staticClass:"money font-color-red"},[t._v(" 可砍至: ￥"),i("span",{staticClass:"price",domProps:{textContent:t._s(n.min_price)}})])]),i("div",{staticClass:"cutBnt bg-color-red",on:{click:function(i){return t.goDetail(n.id)}}},[i("span",{staticClass:"iconfont icon-kanjia"}),t._v("参与砍价 ")])])})),t.status?t._e():i("div",{staticClass:"load font-color-red",on:{click:t.getBargainList}},[t._v(" 点击加载更多 ")])],2)])},r=[],e=i("ca41"),o={name:"GoodsBargain",components:{},props:{},data:function(){return{bargainLis:[],status:!1,loading:!1,page:1,limit:20}},mounted:function(){this.getBargainList()},methods:{getBargainList:function(){var t=this;t.loading||t.status||(t.loading=!0,Object(e["f"])({page:t.page,limit:t.limit}).then((function(n){t.status=n.data.length<t.limit,t.bargainLis.push.apply(t.bargainLis,n.data),t.page++,t.loading=!1})))},goDetail:function(t){this.$router.push({path:"/activity/dargain_detail/"+t+"/0"})}}},u=o,s=i("2877"),c=Object(s["a"])(u,a,r,!1,null,null,null);n["default"]=c.exports}}]);
//# sourceMappingURL=chunk-e14b291c.2672dafc.js.map