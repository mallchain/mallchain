<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/6
 * Time: 15:07
 */
namespace app\commen\GWLP;

class gwlp_base_api{


    public $wid="";
    public $url="http://api.gylp.gwulp.com/";
    public $access_token="";



    public function gwlp_post($param){
        $time=time();
        $param['wid']=$this->wid;
        $param['timestamp']=$time;
        $param['token']=$this->get_access_token($time);
        write_log(json_encode($param),"广物供应链公共接口");
       $re= httpPost($this->url,$param);
        write_log(json_encode($re),"广物供应链公共接口");
        return $re;

    }

    public function get_access_token($time){
      return  MD5($this->wid.$this->access_token.$time);
    }


}
