<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/6
 * Time: 17:23
 * 处理广物订单类
 *
 */

namespace app\commen\login;
use think\facade\Db;
use app\commen\GWLP\gwlp_order_api;
use app\common\logic\gwlp\GwlpGoods;
use app\models\store\StoreOrder;
use app\models\store\GwlpOrder;
use app\models\store\GwlpRefund;
use app\models\store\StoreCart;
use app\models\store\StoreProduct;

class order
{
    /**
     * 提交订单
     * @param $param
     * @return mixed
     */
    public function SubmitOrder($order_data=[],$user_data=[],$order_sn){

        $date=[
            'platform_sn'=>$order_sn,
            'consignee'=>$user_data['real_name'],
            'mobile'=>$user_data['phone'],
            'province'=>$user_data['province'],
            'city'=>$user_data['city'],
            'district'=>$user_data['district'],
            'address'=>$user_data['detail'],
            'call_back_url'=>'http://'.$_SERVER['HTTP_HOST'].'/api/call_back_delivery',
            'goods'=>[],
        ];
        $goods=[];
        foreach ($order_data as $k=>$v){
           $sp=Db::name("store_product")->field("gwlp_goods_id,mer_id")->where(["id"=>$v['product_id']])->find();
           if($sp['mer_id']<=0){
               continue;
           }
            $key='';
           if($v['product_attr_unique']){
               $key_name=Db::name("store_product_attr_value")->field("gwlp_spec_key")->where(["unique"=>$v['product_attr_unique']])->find();
               $key=$key_name['gwlp_spec_key'];
           }
           $goods[]=[
                'store_id'=>$sp['mer_id'],
                'goods_id'=>$sp['gwlp_goods_id'],
                'goods_num'=>$v['cart_num'],
                'goods_price'=>$v['costPrice'],
                'spec_key'=>$key,
            ];
        }
        if(empty($goods)){
            return ['succeed'=>true,'msg'=>'','master_order_sn'=>0];
        }
        $date['goods']=$goods;
        $obj=new gwlp_order_api();
        $re= $obj->SubmitOrder($date);
       if($re['ret']==200&&$re['data']['success']){
           $platform_sn=$re['data']['platform_sn'];//平台单号
           $master_order_sn=$re['data']['master_order_sn'];//供应链主订单编号
           $insert_gy=[];
           foreach ($re['data']['result'] as $k=>$v){
               $insert_gy[]=[
                   'order_sn'=>$platform_sn,
                   'master_order_sn'=>$master_order_sn,
                   'gwlp_order_sn'=>$v['order_sn'],//202001121611264905
                   'store_id'=>$v['store_id'],//106
                   'goods_ids'=>$v['goods_ids'],//44747,44748,44755
                   'time'=>time(),
               ];
           }
           Db::name("gwlp_order")->insertAll($insert_gy);

           return ['succeed'=>true,'msg'=>'','master_order_sn'=>$master_order_sn];
       }else{
           if($re['ret']==6018){
              $obj=new GwlpGoods();
               foreach ($goods as $k2=>$v2){
                   $obj->getGWLPGoodsById($v2["goods_id"]);
               }
           }
           return ['succeed'=>false,'msg'=>$re['msg'],'master_order_sn'=>0];
       }


    }
    /**
     * 修改订单支付状态
     * @param $param
     * @return mixed
     */
    public function PayAfterStatus($master_order_sn){
        $obj=new gwlp_order_api();
        $re= $obj->PayAfterStatus(['order_sn'=>$master_order_sn]);
        $where=['master_order_sn'=>$master_order_sn];
        $status=-2;
        if($re['ret']==200&&$re['data']['is_success']){
            $status=1;
        }
        Db::name("gwlp_order")->where($where)->update(['status'=>$status]);
    }

    /**
     * 获取物流状态
     * @param $param
     * @return mixed
     */
    public function GetLogisticsInfo($master_order_sn){
        $obj=new gwlp_order_api();
         $order_sn= Db::name("gwlp_order")->where(['master_order_sn'=>$master_order_sn,'status'=>2])->value("gwlp_order_sn");

        $re= $obj->GetLogisticsInfo(['order_sn'=>$order_sn]);

        if($re['ret']==200&&$re['data']['success']){

            foreach ($re['data']['result'][0]['orderTrack']  as $v){
                $date[]=[
                    'time'=>$v['msgTime'],
                    'status'=>$v['content'],
                ];
            }
           return $date;
        }else{
           return [];
        }

    }

    /**
     * 取消订单
     */
    public function CancelOrder($order_sn){
        $obj=new gwlp_order_api();
        $gwlp_order=Db::name("gwlp_order")->field("gwlp_order_sn,id")->where(['master_order_sn'=>$order_sn,'status'=>0])->select();
        foreach($gwlp_order as $k=>$v){
            $re= $obj->CancelOrder(['order_sn'=>$v['gwlp_order_sn']]);
            $status=-3;
            if($re['ret']==200&&$re['data']['is_success']){
                $status=1;
            }
            Db::name("gwlp_order")->where(['gwlp_order_sn'=>$v['gwlp_order_sn']])->update(['status'=>$status]);
        }
        return true;
    }

    /**
     * 订单已支付待发货时申请退款，对接供应链
     * @param $uni
     * @param $uid
     * @param string $refundReasonWap
     * @param string $refundReasonWapExplain
     * @return bool
     */
    public function refundGWLPOrder($uni, $uid, $refundReasonWap = '', $refundReasonWapExplain = '')
    {
        $order = StoreOrder::getUserOrderDetail($uid, $uni);
        $master_order_sn = $order['master_order_sn'];
        if(empty($master_order_sn)) return false;
        $gwlp_order = GwlpOrder::where(['master_order_sn' => $master_order_sn])
                        ->where('status','<>',-1)->field("id,order_sn,status,gwlp_order_sn,goods_ids")->select();
        $gwlp_order = $gwlp_order->toArray();
        if(!$gwlp_order) return false;
        $goa = new gwlp_order_api();
        foreach ($gwlp_order as $item){
            $count = GwlpRefund::where(['gwlp_order_sn' => $item['gwlp_order_sn']])->count();
            if($count > 0) continue;
            $resData = $goa->CancelOrder(['order_sn' => $item['gwlp_order_sn']]); // 先取消订单
            if($resData['ret'] == 200 && $resData['data']['is_success'])
                GwlpOrder::where(['gwlp_order_sn' => $item['gwlp_order_sn']])->update(['status' => -1]);
            else{ //订单不能直接取消则申请售后
                $this->returnGWLPOrder($order,$item['gwlp_order_sn'],$item['goods_ids'],0,$refundReasonWap,$refundReasonWapExplain);
            }
        }
        return true;
    }

    /**
     * 申请供应链售后
     * @param $order_sn
     * @param $goodsIds
     * @param $order
     * @param $type
     * @param string $reason
     * @param string $describe
     * @return bool
     */
    public function returnGWLPOrder($order, $order_sn, $goodsIds, $type, $reason = '', $describe = '')
    {
        $returnData = [
            'order_sn'     => $order_sn, //订单编号
            'type'         => $type, //0仅退款 1退货退款 2换货 3维修
            'reason'       => $reason, //退换货退款申请原因
            'describe'     => $describe, //问题描述
            'callback_url' => getBaseURL() . '/api/refund_notify', //回调地址,状态更新回调地址
        ];
        $gwlpRefundData = [
            'order_sn'       => $order['order_id'],
            'gwlp_order_sn'  => $order_sn,
            'status'         => 0,
            'add_time'       => time(),
        ];
        $goa = new gwlp_order_api();
        $goodsIds = explode(',',$goodsIds);
        $goodsIds = array_values(array_unique($goodsIds)); //供应链返回的商品id可能会重复，此处去重并重新排序
        foreach ($goodsIds as $goods_id){
            $returnData['goods_id'] = $goods_id; //申请售后的商品id
            $product = StoreProduct::where(['gwlp_goods_id' => $goods_id,'is_del' => 0])->field('id,mer_id')->find(); //优品商品数据
            $orderProducts = StoreCart::where('id','in',$order['cart_id'])->field('id,product_id,product_attr_unique,cart_num')
                            ->where(['type' => 'product','is_del' => 0,'product_id' => $product['id']])->select(); //可能存在商品id相同规格不同的情况
            foreach ($orderProducts as $item){
                if($item['product_attr_unique'])
                    $returnData['spec_key'] = Db::name('store_product_attr_value')->where(['unique' => $item['product_attr_unique']])->value('gwlp_spec_key'); //如有商品有规格就必填
                else $returnData['spec_key'] = '';
                $resData = $goa->ReturnOrder($returnData);
                write_log(json_encode($resData),"申请广物供应链售后返回");
                if(!empty($resData) && $resData['ret'] == 200 && $resData['data']['is_success']){
                    $gwlpRefundData['cart_id'] = $item['id'];
                    $gwlpRefundData['gwlp_goods_id'] = $goods_id;
                    $gwlpRefundData['gwlp_spec_key'] = $returnData['spec_key'];
                    $gwlpRefundData['gwlp_refund_id'] = $resData['data']['id'];
                    GwlpRefund::create($gwlpRefundData);
                }
            }
        }
    }
}