<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/6
 * Time: 17:23
 */

namespace app\commen\GWLP;


class gwlp_order_api extends gwlp_base_api
{
    /**
     * 提交订单
     * @param $param
     * @return mixed
     */
    public function SubmitOrder($param){
        $param['service']="App.Order.SubmitOrder";
        return   $this->gwlp_post($param);
    }

    /**
     * 修改订单支付状态
     * @param $param
     * @return mixed
     */
    public function PayAfterStatus($param){
        $param['service']="App.Order.PayAfterStatus";
        $param['account']="63cedecd4c6e0c3ab9ea9a7c75bc03b2";
        return   $this->gwlp_post($param);
    }

    /**
     * 获取物流信息
     * @param $param
     * @return mixed
     */
    public function GetLogisticsInfo($param){
        $param['service']="App.Order.GetLogisticsInfo";
        return   $this->gwlp_post($param);
    }

    /**
     * 取消广物供应链订单
     * @param $param
     * @return mixed
     */
    public function CancelOrder($param){
        $param['service']="App.Order.CancelOrder";
        return   $this->gwlp_post($param);
    }

    /**
     * 申请售后
     * @param $params
     * @return mixed
     */
    public function ReturnOrder($params)
    {
        $params['service'] = "App.Order.Return_order";
        return  $this->gwlp_post($params);
    }
}