<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/13
 * Time: 15:44
 * 处理广物商品类
 */
namespace app\common\logic\gwlp;
use app\admin\model\store\StoreCategory;
use app\admin\model\store\StoreProduct;
use app\admin\model\store\StoreProductAttr;
use app\admin\model\store\StoreProductAttrResult;
use app\admin\model\store\StoreProductAttrValue;
use think\facade\Db;
use app\admin\model\system\SystemConfig as ConfigModel;
use app\commen\GWLP\GwlpGoodsApi;
use app\models\user\UserAddress;

class GwlpGoods
{
    protected $gwlp_goods = null;
    protected $goods_ids = [];

    public function __construct()
    {
        $this->gwlp_goods = new GwlpGoodsApi();
    }

    /**
     * 获取广物良品商品id--若商品id过多会请求超时
     * @param int $page
     * @param int $page_count
     * @return array
     */
    public function getGWLPGoodsIds($page = 1,$page_count = 50)
    {
        $params = [
            'page' => $page,
            'page_count' => $page_count
        ];
        $result = $this->gwlp_goods->getGoodsIdList($params);
        if($result['ret'] == 200){
            $data = $result['data'];
            $total = (int)$data['total'];
            if(!empty($data)){
                $data = array_column($data, 'goods_id');
                $this->goods_ids = array_merge($this->goods_ids,$data);
            }
            if($total > $page * $page_count) $this->getGWLPGoodsIds(++$page);
        }
        return $this->goods_ids;
    }

    /**
     * 获取广物供应链商品列表
     * @param int $page
     * @param int $page_count
     * @return mixed
     */
    public function getGWLPGoodsList($page = 1,$page_count = 50,$arr=[])
    {
        $params = [
            'page' => $page,
            'page_count' => $page_count,
            'profit_rate_min' => $arr['price_min'],
            'profit_rate_max' => $arr['price_max'],
        ];
        $result = $this->gwlp_goods->GetGoodsList($params);
        if($result['ret'] == 200){
            return $result['data'];
        }
        return $result['msg'];
    }

    /**
     * 根据广物供应链商品id获取广物供应链商品
     * @param $gwlp_id 广物供应链商品id
     * @return bool
     */
    public function getGWLPGoodsById($gwlp_id)
    {
        $params = [
            'goods_id' => $gwlp_id
        ];
        $result = $this->gwlp_goods->GetGoodsList($params);
        if($result['ret'] != 200){
            return $result['msg'];
        }
        $data = $result['data'];
        if((int)$data['total'] <= 0) return false;
        $this->saveGWLPGoods($data['0']);
    }


    /**
     * 获取三级分类id
     * @param $cate_id
     * @return int|mixed
     */
    public function getCateId($cate_id)
    {
        if(empty($cate_id)) return 0;
        $result = $this->gwlp_goods->getCategoryInfo((int)$cate_id);
        if($result['ret'] == 200 && $result['data']['success']){
            $data = $result['data']['result'];
            $id = StoreCategory::where(['cate_name' => $data['name'],'level' => 3])->value('id');
        }
        return empty($id) ? 0 : $id;
    }

    /**
     * 组装商品数据
     * @param $gwlp_data
     * @return array
     */
    public function buildProductData($gwlp_data)
    { 
        $cate_id = $this->getCateId($gwlp_data['cat_id3']);
        $slider_image = json_encode(explode(",", $gwlp_data['goods_img_list']));

        $integral_price=$this->profitrate($gwlp_data['cost_price'],$gwlp_data['shop_price']);

        $productData = [
            'profit_price'    => $integral_price['profit_price'], //现金
            'use_integral'    => $integral_price['use_integral'], //积分
            'mer_id'          => $gwlp_data['store_id'], //商户Id
            'image'           => $gwlp_data['original_img'], //商品图片
            'slider_image'    => $slider_image, //轮播图
            'store_name'      => $gwlp_data['goods_name'], //商品名称
            'store_info'      => $gwlp_data['goods_name'], //商品简介,暂设为与商品名称一致
            'cate_id'         => $cate_id, //分类id
            'price'           => $gwlp_data['shop_price'], //商品价格
            'vip_price'       => 0, //会员价格,暂设为0
            'ot_price'        => $gwlp_data['shop_price']+($gwlp_data['shop_price']*0.4), //市场价
            'cost'            => $gwlp_data['cost_price'], //成本价
            'gwlp_shop_price' => $gwlp_data['shop_price'], //广物供应链商品售价
            'gwlp_cost_price' => $gwlp_data['cost_price'], //广物供应链商品成本价
            'gwlp_profitrate' => round(($gwlp_data['shop_price'] - $gwlp_data['cost_price']) / $gwlp_data['shop_price'] * 100,2), //广物供应链商品利润率，四舍五入保留两位小数
            'weight'          => $gwlp_data['weight'], //重量
            'stock'           => $gwlp_data['store_count'], //库存
            'is_show'         => empty($cate_id) ? 0 : 1, //状态（0：未上架，1：上架）
            'description'     => html_entity_decode($gwlp_data['goods_content']), //产品描述
            'gwlp_goods_id'   => $gwlp_data['goods_id'],  //广物商品id
            'add_time'        => time(), //添加时间
            'unit_name'       => '件', //单位名
            'keyword'         => '', //关键字,暂无
            'bar_code'        => '', //产品条码（一维码）,暂无
            'is_postage'      => 0, //是否包邮 0不包邮1包邮,暂设为不包邮
        ];
        return $productData;
    }

    /**
     * 保存广物供应链的商品规格属性
     * @param $product_id 商品id
     * @param $goods_attrs 商品属性
     * @param $goods_specs 商品规格
     * @param $cost_price 成本价
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function saveProductAttrs($product_id,$goods_attrs,$goods_specs,$cost_price)
    {
        $attrList = [];
        $valueList = [];
        Db::startTrans();
        $uniques = [];
        foreach ($goods_specs as $spec){
            $detail = [];
            $spec_name = '';
            $key_names = explode(';',$spec['key_name']);
            foreach ($key_names as $key_name){
                $key_item = explode(':',$key_name);
                $detail[$key_item[0]] = $key_item[1];
                if(empty($spec_name))  $spec_name .= $key_item[1];
                else $spec_name .= ',' . $key_item[1];
            }
            if(!empty($spec_name)){
                $attr_value = StoreProductAttrValue::where(['product_id' => $product_id,'suk' => $spec_name,'gwlp_spec_key' => $spec['spec_key']])->find();
                $attrValueData = [
                    'product_id'    => $product_id,
                    'suk'           => $spec_name,
                    'stock'         => $spec['actual_count'],
                    'price'         => $spec['price'],
                    'cost'          => $cost_price,
                    'gwlp_spec_key' => $spec['spec_key'],
                    'unique'        => StoreProductAttrValue::uniqueId($product_id.$spec_name.uniqid(true)),
                    'sales'         => 0,
                    'bar_code'      => '',
                    'gwlp_price'    => $spec['price'],
                    //'gwlp_profitrate' => round(($spec['price'] - $cost_price) / $spec['price'] * 100,2), //广物供应链商品利润率，四舍五入保留两位小数
                ];
                if(!empty($attr_value)){
                    unset($attrValueData['price'],$attrValueData['cost'],$attrValueData['unique'],$attrValueData['sales'],$attrValueData['bar_code']);
                    StoreProductAttrValue::edit($attrValueData,$attr_value['unique'],'unique');
                    array_push($uniques,$attr_value['unique']);
                }else{
                    $res = StoreProductAttrValue::create($attrValueData);
                    array_push($uniques,$res['unique']);
                }
                $value_item = [
                    "detail" => $detail,
                    "cost"   => $cost_price,
                    "price"  => empty($res) ? $attr_value['price'] : $res['price'],
                    "sales"  => empty($res) ? $attr_value['sales'] : $res['sales'],
                    "pic"    => empty($res) ? $attr_value['pic'] : $res['pic'],
                    "check"  => false
                ];
                array_push($valueList,$value_item);
            }else Db::rollback();
        }
        $attr_values = StoreProductAttrValue::where(['product_id' => $product_id])->select();
        foreach ($attr_values as $val){
            if(!in_array($val['unique'],$uniques)) StoreProductAttrValue::where(['unique' => $val['unique']])->delete();
        }
        StoreProductAttr::where(['product_id' => $product_id])->delete();
        foreach ($goods_attrs as $attr){
            $attrData = [
                'product_id'  => $product_id,
                'attr_name'   => $attr['name'],
                'attr_values' => $attr['items']
            ];
            $attr_item = [
                "value"       => $attr['name'],
                "detailValue" => "",
                "attrHidden"  => true,
                "detail"      => explode(',',$attr['items'])
            ];
            array_push($attrList,$attr_item);
            StoreProductAttr::create($attrData);
        }
        if(!empty($attrList) && !empty($valueList)){
            StoreProductAttrResult::where(['product_id' => $product_id])->delete();
            $result = ['attr'=>$attrList,'value'=>$valueList];
            StoreProductAttrResult::setResult($result,$product_id);
        }else Db::rollback();
        Db::commit();
    }

    /**
     * 广物供应链获取的商品规格是否有效
     * @param $goods_specs
     * @return bool
     */
    public function gwlpGoodsIsValid($goods_specs)
    {
        $flag = false;
        foreach ($goods_specs as $spec) {
            $key_names = explode(';', $spec['key_name']);
            foreach ($key_names as $key_name) {
                $key_item = explode(':', $key_name);
                if (count($key_item) != 2) {
                    $flag = true;
                    break;
                }
            }
            if($flag) break;
        }
        return $flag;
    }


    /**
     * 保存广物商品数据
     * @param $detail 广物商品明细数据
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function saveGWLPGoods($detail)
    {
        if(!$detail) return false;
        $productData = $this->buildProductData($detail);
        $product = StoreProduct::get(['gwlp_goods_id' => $detail['goods_id']]);
        if($product){
            $id = $product['id'];
            unset($productData['cate_id'],$productData['vip_price'],$productData['ot_price'],$productData['is_show'],
                $productData['add_time'],$productData['unit_name'],$productData['keyword'],$productData['bar_code'],$productData['is_postage']);
            StoreProduct::edit($productData,$id);
        }else{
            $createData = StoreProduct::create($productData);
            $id = $createData['id'];
            $productData['cate_id'] > 0 && Db::name('store_product_cate')->insert(['product_id' => $id,'cate_id' => $productData['cate_id'],'add_time' => time()]);
        }
        if(!empty($detail['goods_item']) && !empty($detail['goods_attrs'])) {
            $flag = $this->gwlpGoodsIsValid($detail['goods_item']);
            if($flag) StoreProduct::where(['id' => $id])->delete();
            else $this->saveProductAttrs($id,$detail['goods_attrs'],$detail['goods_item'],$detail['cost_price']);
        }
        return true;
    }

    /**
     * 更新商品价格
     */
    public function updateProductPrice($page=1,$type=0){

        if($page==1){
            cache("temporary_updateProductPrice_start_time",time());
        }
        $result=$this->gwlp_goods->GetGoodsMessage($type,$page,cache("updateProductPrice_start_time"));

        $next_page=0;
        if($result['ret'] == 200 ){
            $next_page = $result['data']['next_page'];
            $data = $result['data']['list'];
           if(count($data)>0){
               $goods_ids='';
               $goods_is_sold_id='';
               $goods_is_update_id='';
               foreach ($data as $k=>$v){

                   if($v['type']==3){
                       //更新商品价格
                       $goods_ids.=$v['id'].',';
                   }else if($v['type']==2){
                       //产品下架
                       $goods_is_sold_id.=$v['id'].',';
                   }else if($v['type']==1){
                       //更新上架或内容更新
                       $goods_ids.=$v['id'].',';
                       $goods_is_update_id.=$v['id'].',';
                   }
               }
               if($goods_is_sold_id){
                   //产品下架
                   $goods_is_sold_id= rtrim($goods_is_sold_id, ',');
                   StoreProduct::whereIn('gwlp_goods_id',$goods_is_sold_id)->update(['is_show'=>0]);

               }

               if($goods_is_update_id){
                   //更新上架或内容更新
                   $goods_is_update_id= rtrim($goods_is_update_id, ',');
                   StoreProduct::whereIn('gwlp_goods_id',$goods_is_update_id)->update(['is_show'=>1]);

               }
               if($goods_ids){

                   $goods_ids= rtrim($goods_ids, ',');
                   $result_price= $this->gwlp_goods->GetGoodsPrice($goods_ids);
                   if($result_price['ret'] == 200 ){
                       $goods_idss='';
                       $sql="update eb_store_product SET ";
                       $profit_price=$use_integral=$price=$ot_price=$cost=$gwlp_shop_price=$gwlp_cost_price=$gwlp_profitrate='';
                       foreach ($result_price['data'] as $k2=>$v2){
                           $goods_id=$v2['id'];

                           $integral_price=$this->profitrate($v2['cost_price'],$v2['shop_price']);
                           $updates=[
                               'profit_price'    => $integral_price['profit_price'], //商品价格
                               'use_integral'    => $integral_price['use_integral'], //商品价格
                               'price'           => $v2['shop_price'], //商品价格
                               'ot_price'        => $v2['shop_price']+($v2['shop_price']*0.4), //市场价
                               'cost'            => $v2['cost_price'], //成本价
                               'gwlp_shop_price' => $v2['shop_price'], //广物供应链商品售价
                               'gwlp_cost_price' => $v2['cost_price'], //广物供应链商品成本价
                               'gwlp_profitrate' => round(($v2['shop_price'] - $v2['cost_price']) / $v2['shop_price'] * 100,2), //广物供应链商品利润率，四舍五入保留两位小数
                           ];
                           $profit_price.="WHEN $goods_id THEN {$updates['profit_price']} ";
                           $use_integral.="WHEN $goods_id THEN {$updates['use_integral']} ";
                           $price.="WHEN $goods_id THEN {$updates['price']} ";
                           $ot_price.="WHEN $goods_id THEN {$updates['ot_price']} ";
                           $cost.="WHEN $goods_id THEN {$updates['cost']} ";
                           $gwlp_shop_price.="WHEN $goods_id THEN {$updates['gwlp_shop_price']} ";
                           $gwlp_cost_price.="WHEN $goods_id THEN {$updates['gwlp_cost_price']} ";
                           $gwlp_profitrate.="WHEN $goods_id THEN {$updates['gwlp_profitrate']} ";

                           $goods_idss.=$goods_id.',';
                           //    StoreProduct::where(['gwlp_goods_id'=>$goods_id])->update($updates);
                       }
                   }




                   if($goods_idss){

                       $sql.=" profit_price = CASE gwlp_goods_id $profit_price END, ";
                       $sql.=" use_integral = CASE gwlp_goods_id $use_integral END, ";
                       $sql.=" price = CASE gwlp_goods_id $price END, ";
                       $sql.=" ot_price = CASE gwlp_goods_id $ot_price END, ";
                       $sql.=" cost = CASE gwlp_goods_id $cost END, ";
                       $sql.=" gwlp_shop_price = CASE gwlp_goods_id $gwlp_shop_price END, ";
                       $sql.=" gwlp_cost_price = CASE gwlp_goods_id $gwlp_cost_price END, ";
                       $sql.=" gwlp_profitrate = CASE gwlp_goods_id $gwlp_profitrate END ";

                       $goods_idss= rtrim($goods_idss, ',');
                       $sql .= " WHERE gwlp_goods_id IN ($goods_idss)";
                       Db::execute($sql);
                   }

                   /* var_dump($sql);
                    exit();*/

                   //  var_dump(Db::name("")->getLastSql());

               }

           }



        }

        if($next_page==0){
            cache("updateProductPrice_start_time", cache("temporary_updateProductPrice_start_time"));
        }

        return $next_page;

    }

    /**
     * 获取积分价格，与现金
     * @param $cost_price
     * @param $shop_price
     */
    public function profitrate($cost_price,$shop_price){
        $default_shop_profitrate = ConfigModel::getConfigValue('default_shop_profitrate');//默认利润率
        if($default_shop_profitrate>0){
            $default_shop_profitrate=$default_shop_profitrate/100;
        }
        $integral_ratio = sysConfig('integral_ratio');
        $price = round($cost_price + ($shop_price - $cost_price) * $default_shop_profitrate,2); //根据利润值算出的目标售价
        $integral = round($shop_price - $price) / $integral_ratio; //根据目标售价算出的积分额，修改后的算法

       if($integral<=0){
           $price=0;
           $integral=0;
        }
        return ['profit_price'=>$price,'use_integral'=>$integral];

    }

    /**
     * 广物库存查询
     * @param int $p_id
     * @param int $gwlp_id
     * @param int $uid
     * @param array $address
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cache_stock($p_id=0,$gwlp_id=0,$uid=0,$address=[]){

        if(cache("check_stock_$p_id")!=1){
            cache("check_stock_$p_id",1,C('cache_stock_item'));
        if(empty($address)&&!empty($uid)){
            //地址为空就通过用户id查询地址
            $address=UserAddress::getUserDefaultAddress($uid);
        }else if(empty($address)&&empty($uid)){
            //用户没有地址不用查询库存
            return true;
        }
            if($gwlp_id==0){
                $product_id = StoreProduct::field("gwlp_goods_id")->find($p_id)->toArray();
                if($product_id){$gwlp_id=$product_id['gwlp_goods_id'];}
            }
            if($gwlp_id>=1){
                $params = [
                    'goods_id' => $gwlp_id,
                    'goods_num' => 1,
                    'spec_key' => '',
                    'province' => $address['province'],
                    'city' => $address['city'],
                    'district' => $address['district'],
                    'address' => $address['detail']
                ];
                $result= $this->gwlp_goods->GetNewStockState($params);

                if($result['ret'] == 200 ){

                    if($result['data']['stockState']==0){
                        StoreProduct::where('id',$p_id)->update(['stock'=>0,'is_show'=>0]);
                        //商品无库存返回
                        return false;
                    }else{
                        //商品有库存
                        return true;
                    }
                }
            }else{
                //商品不是通过接口获取过来的，系统已经自动判断库存了，无须判断
                return true;
            }
        }else{
            //之前已经判断过库存无须再次判断
            return true;
        }




    }
}