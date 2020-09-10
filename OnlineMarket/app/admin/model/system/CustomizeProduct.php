<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\system;

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;

/**
 * 定制模块 model
 * Class StoreProductRelation
 * @package app\admin\model\store
 */
class CustomizeProduct extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'customize_product';

    use ModelTrait;

    public static function getCustomizeProduct($cid)
    {
        $model = self::alias('c')->join('StoreProduct p','c.pid = p.id','LEFT')->where('c.cid',$cid)->field('p.id,p.image,p.store_name,p.price')->order('c.id ASC');
        $data=($data=$model->select()) && count($data) ? $data->toArray():[];
        return $data;
    }
}