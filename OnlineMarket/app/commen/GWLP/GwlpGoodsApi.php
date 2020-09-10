<?php
namespace app\commen\GWLP;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/6
 * Time: 15:32
 */

class GwlpGoodsApi extends gwlp_base_api{

    /**
     * 获取商品id列表
     * @param array $params
     * @return mixed
     */
    public function getGoodsIdList($params){
        $params['service']="App.Goods.GetGoodsIdList";
        return  $this->gwlp_post($params);
    }

    /**
     * 获取商品列表接口
     * @param array $params
     * @return mixed
     */
    public function GetGoodsList($params=[]){
        $params['service']="App.Goods.GetGoodsList";
        return  $this->gwlp_post($params);
    }

    /**
     * 根据广物供应链商品id获取商品详情
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsInfo($goods_id)
    {
        $params = ['goods_id' => $goods_id,'service' => "App.Goods.GetGoodsInfo"];
        return  $this->gwlp_post($params);
    }

    /**
     * 根据分类id获取广物分类详情
     * @param $cate_id
     * @return mixed
     */
    public function getCategoryInfo($cate_id)
    {
        $params = ['cate_id' => $cate_id,'service' => "App.Category.GetCategoryInfo"];
        return  $this->gwlp_post($params);
    }

    /**
     * 查询商品更新消息API
     * @param $cate_id
     * @return mixed
     */
    public function GetGoodsMessage($type=0,$page=1,$start_time=0)
    {
        //默认0： 0:全部 1:上架或商品更新 2:商品下架 3:更新价格
        $params = ['type' => $type,'page'=>$page,'start_time'=>$start_time,'service' => "App.Goods.GetGoodsMessage"];
        return  $this->gwlp_post($params);
    }
    /**
     * 查询商品更新消息API
     * @param $cate_id
     * @return mixed
     */
    public function GetGoodsPrice($goods_id='')
    {
        $params = ['goods_id' => $goods_id,'service' => "App.Goods.GetGoodsPrice"];
        return  $this->gwlp_post($params);
    }

    /**
     * 查询商品是否有货
     */
    public function GetNewStockState($params=[]){
        $params = [
            'goods_id' => $params['goods_id'],
            'goods_num' => $params['goods_num'],
            'spec_key' => $params['spec_key'],
            'province' => $params['province'],
            'city' => $params['city'],
            'district' => $params['district'],
            'address' => $params['address'],
            'service' => "App.Goods.GetNewStockState"];
        return  $this->gwlp_post($params);
    }
}