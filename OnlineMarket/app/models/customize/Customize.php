<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\models\customize;

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use app\models\customize\CustomizeProduct;

/**
 * 定制模块 model
 * Class StoreProductRelation
 * @package app\admin\model\store
 */
class Customize extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'customize';

    use ModelTrait;

    public static function CustomizeList($where)
    {
        $model = new self;
        if($where['is_show'] != '') $model = $model->where('is_show',$where['is_show']);
        if($where['module_name'] != '') $model = $model->where('module_name','LIKE',"%$where[module_name]%");
        $data=($data=$model->page((int)$where['page'],(int)$where['limit'])->select()) && count($data) ? $data->toArray() :[];
        foreach ($data as &$item){
            $item['product_total'] = CustomizeProduct::where('cid',$item['id'])->count();
        }
        $count=$model->count();
        return compact('count','data');
    }

    public static function delCustomize($id){
        $res = CustomizeProduct::where('cid',$id)->delete();
        if($res !== false) $res = self::del($id);
        return $res;
    }

}