<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\models\customize;

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use app\models\store\StoreProduct;

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

    public static function getCustomizeProduct($cid,$field = '*', $limit = 0, $uid = 0)
    {
        $model = self::alias('c')->join('StoreProduct p','c.pid = p.id','LEFT')->where('c.cid',$cid)->where('p.is_show',1)->field($field)->order('c.id ASC');
        if ($limit) $model->limit($limit);
        //$data=($data=$model->select()) && count($data) ? $data->toArray():[];
        return StoreProduct::setLevelPrice($model->select(), $uid);
    }
}