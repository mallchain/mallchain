<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\store;

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;

/**
 * 运费模板
 * Class StoreVisit
 * @package app\admin\model\store
 */
class FreightTemplate extends BaseModel
{
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'freight_template';

    use ModelTrait;

    public function getFreightDataAttr($value){
        return json_decode($value);
    }

    public function getSelectedAreaAttr($value){
        return json_decode($value);
    }

    public static function TempList($where){
        $model = new self();
        if($where['name'] != '') $model = $model->where('name','LIKE',"%$where[name]%");
        if($where['type'] != '') $model = $model->where('type',$where['type']);
        $count = $model->count();
        $model = $model->page((int)$where['page'],(int)$where['limit'])->field('id,name,type,is_default,status,add_time');
        $data=($data=$model->select()) && count($data) ? $data->toArray():[];
        return compact('count','data');
    }
}