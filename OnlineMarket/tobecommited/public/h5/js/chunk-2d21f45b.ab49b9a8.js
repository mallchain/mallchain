(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d21f45b"],{d8b3:function(t,s,i){"use strict";i.r(s);var e=function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"quality-recommend"},[i("div",{staticClass:"slider-banner swiper"},[i("swiper",{staticClass:"swiper-wrapper",attrs:{options:t.RecommendSwiper}},t._l(t.imgUrls,(function(t,s){return i("swiperSlide",{key:s,staticClass:"swiper-slide"},[i("img",{staticClass:"slide-image",attrs:{src:t.img}})])})),1),i("div",{staticClass:"swiper-pagination"})],1),t._m(0),i("Promotion-good",{attrs:{benefit:t.goodsList}})],1)},a=[function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"title acea-row row-center-wrapper"},[i("div",{staticClass:"line"}),i("div",{staticClass:"name"},[i("span",{staticClass:"iconfont icon-cuxiaoguanli"}),t._v("促销单品\n    ")]),i("div",{staticClass:"line"})])}],n=i("6fe8"),o=(i("e5d0"),i("91c8")),r=i("73f5"),c={name:"GoodsPromotion",components:{swiper:n["swiper"],swiperSlide:n["swiperSlide"],PromotionGood:o["a"]},props:{},data:function(){return{imgUrls:[],goodsList:[],RecommendSwiper:{pagination:{el:".swiper-pagination",clickable:!0},autoplay:{disableOnInteraction:!1,delay:2e3},loop:!0,speed:1e3,observer:!0,observeParents:!0}}},mounted:function(){this.getIndexGroomList()},methods:{getIndexGroomList:function(){var t=this;Object(r["e"])(4).then((function(s){t.imgUrls=s.data.banner,t.goodsList=s.data.list})).catch((function(t){this.$dialog.toast({mes:t.msg})}))}}},l=c,d=i("5511"),p=Object(d["a"])(l,e,a,!1,null,null,null);s["default"]=p.exports}}]);
//# sourceMappingURL=chunk-2d21f45b.ab49b9a8.js.map