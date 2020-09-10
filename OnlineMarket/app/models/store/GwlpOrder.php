<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/20
 */

namespace app\models\store;

use app\models\system\SystemStore;
use app\models\routine\RoutineTemplate;
use crmeb\repositories\GoodsRepository;
use crmeb\repositories\PaymentRepositories;
use app\models\user\User;
use app\models\user\UserAddress;
use app\models\user\UserBill;
use app\models\user\WechatUser;
use crmeb\basic\BaseModel;
use crmeb\repositories\OrderRepository;
use crmeb\repositories\UserRepository;
use crmeb\services\MiniProgramService;
use crmeb\services\SystemConfigService;
use crmeb\services\WechatService;
use crmeb\services\WechatTemplateService;
use crmeb\services\workerman\ChannelService;
use think\facade\Cache;
use crmeb\traits\ModelTrait;
use think\facade\Log;
use app\commen\login\order as gwOrder;
use think\facade\Route;

/**
 * TODO 订单Model
 * Class StoreOrder
 * @package app\models\store
 */
class GwlpOrder extends BaseModel
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
    protected $name = 'gwlp_order';

    use ModelTrait;

}