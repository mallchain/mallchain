<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/20
 */

namespace app\models\store;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * TODO 订单Model
 * Class StoreOrder
 * @package app\models\store
 */
class GwlpRefund extends BaseModel
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
    protected $name = 'gwlp_refund';

    use ModelTrait;

}