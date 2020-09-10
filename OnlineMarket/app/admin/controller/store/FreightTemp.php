<?php
namespace app\admin\controller\store;

use app\admin\controller\AuthController;
use crmeb\services\UtilService as Util;
use crmeb\services\JsonService as Json;
use app\admin\model\store\FreightTemplate;
use app\admin\model\store\FreightConfig;
use app\admin\model\store\FreightRegion;
use app\admin\model\store\StoreProduct as ProductModel;
use think\facade\Route as Url;

/**
 * 运费模板控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class FreightTemp extends AuthController
{

    /**
     * 显示资源列表
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }
     /*
     *  异步获取运费模板列表
     *  @return json
     */
    public function temp_ist()
    {
        $where = Util::getMore([
            ['name',''],
            ['type',''],
            ['page',1],
            ['limit',20]
        ]);
        return Json::successlayui(FreightTemplate::TempList($where));
    }

    /**
     * 设置单个产品上架|下架
     *
     * @return json
     */
    public function set_status($status='',$id=''){
        ($status=='' || $id=='') && Json::fail('缺少参数');
        $res=FreightTemplate::where(['id'=>$id])->update(['status'=>(int)$status]);
        if($res){
            return Json::successful($status==1 ? '禁用成功':'启用成功');
        }else{
            return Json::fail($status==1 ? '禁用失败':'启用失败');
        }
    }

    /**
     * 快速编辑
     *
     * @return json
     */
    public function set_temp($field='',$id='',$value=''){
        $field=='' || $id=='' || $value=='' && Json::fail('缺少参数');
        if(FreightTemplate::where(['id'=>$id])->update([$field=>$value]))
            return Json::successful('保存成功');
        else
            return Json::fail('保存失败');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if(!$id) return $this->failed('数据不存在');
        if(!FreightTemplate::be(['id'=>$id])) return $this->failed('运费模板数据不存在');
        if(ProductModel::where('freight_id',$id)->count())
            return $this->failed('该运费模板已绑定商品，不允许删除');
        else{
            FreightTemplate::where('id',$id)->delete();
            FreightConfig::where('template_id',$id)->delete();
            FreightRegion::where('template_id',$id)->delete();
            return Json::successful('删除成功!');
        }
    }

    /**
     * 添加|编辑 运费模板页面
     * @param int $id
     * @return mixed
     */
    public function create_edit($id = 0)
    {
        $temp = [];
        if($id){
            $temp = FreightTemplate::get($id);
        }
        $this->assign(['save' => Url::buildUrl('save'),'temp' => $temp]);
        return $this->fetch();
    }

    /**
     * 选择配送地区页面
     * @return mixed
     */
    public function area()
    {
        return $this->fetch();
    }

    /**
     * 保存运费模板数据
     */
    public function save()
    {
        $params = request()->post();
        $id = $params['id'];
        $freight_data = $params['freightData'];
        $selected_area = $params['selectedArea'];
        foreach ($selected_area as $key=>$val){
            if(empty($val)) {
                unset($selected_area[$key]);
                continue;
            }
            foreach ($val as $key2=>$val2){
                if(empty($val2)) unset($selected_area[$key][$key2]);
            }
        }
        $saveData = [
            'name' => $params['name'],
            'type' => $params['type'],
            'is_default' => $params['is_default'],
            'status' => isset($params['status']) ? 0 : 1,
            'freight_data' => json_encode($freight_data,JSON_UNESCAPED_UNICODE),
            'selected_area' => json_encode($selected_area,JSON_UNESCAPED_UNICODE)
        ];
        if($id){ //编辑
            $res = FreightTemplate::edit($saveData,$id);
            FreightConfig::where('template_id',$id)->delete();
            FreightRegion::where('template_id',$id)->delete();
        }else{ //新增
            $saveData['add_time'] = time();
            $res = FreightTemplate::create($saveData);
            $id = $res['id'];
        }
        foreach ($freight_data as $key => $val){
            if(empty($val['is_del']) && !$saveData['is_default']) continue;
            $configData = [
                'first_unit' => $val['first_unit'],
                'first_money' => $val['first_money'],
                'continue_unit' => $val['continue_unit'],
                'continue_money' => $val['continue_money'],
                'template_id' => $id,
                'is_default' => $val['is_del'] == 1 ? 0 : 1
            ];
            $res2 = FreightConfig::create($configData);
            if(empty($val['is_del'])) continue;
            $regionData = [];
            $areas = explode(';',$val['area']);
            foreach ($areas as $area){
                if(empty($area)) continue;
                $item = [
                    'template_id' => $id,
                    'config_id' => $res2['id'],
                    'province' => substr($area,0,strpos($area, '-')),
                    'area' => trim(strrchr($area, '-'),'-')
                ];
                array_push($regionData,$item);
            }
            if(!empty($regionData)) FreightRegion::setAll($regionData);
        }
        return Json::successful('保存成功!');
    }
}
