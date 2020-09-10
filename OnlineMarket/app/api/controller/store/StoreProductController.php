<?php

namespace app\api\controller\store;

use app\admin\model\system\SystemAttachment;
use app\models\system\SystemStore;
use app\models\store\StoreProduct;
use app\models\store\StoreProductAttr;
use app\models\store\StoreProductRelation;
use app\models\store\StoreProductReply;
use app\models\customize\Customize;
use app\models\customize\CustomizeProduct;
use app\Request;
use app\common\logic\gwlp\GwlpGoods;
use crmeb\services\GroupDataService;
use crmeb\services\QrcodeService;
use crmeb\services\SystemConfigService;
use crmeb\services\UtilService;

/**
 * 商品类
 * Class StoreProductController
 * @package app\api\controller\store
 */
class StoreProductController
{
    /**
     * 商品列表
     * @param Request $request
     * @return mixed
     */
    public function lst(Request $request)
    {
        $data = UtilService::getMore([
            ['sid', 0],
            ['cid', 0],
            ['keyword', ''],
            ['priceOrder', ''],
            ['salesOrder', ''],
            ['news', 0],
            ['page', 0],
            ['limit', 0],
            ['type', 0]
        ], $request);
        return app('json')->successful(StoreProduct::getProductList($data, $request->uid()));
    }

    /**
     * 产品分享二维码 推广员
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function code(Request $request, $id)
    {
        if (!$id || !($storeInfo = StoreProduct::getValidProduct($id, 'id'))) return app('json')->fail('商品不存在或已下架');
        $userType = $request->get('user_type', 'wechat');
        $user = $request->user();
        try {
            switch ($userType) {
                case 'wechat':
                    //公众号
                    $name = $id . '_product_detail_' . $user['uid'] . '_is_promoter_' . $user['is_promoter'] . '_wap.jpg';
                    $url = QrcodeService::getWechatQrcodePath($name, '/detail/' . $id . '?spread=' . $user['uid']);
                    if ($url === false)
                        return app('json')->fail('二维码生成失败');
                    else
                        return app('json')->successful(['code' => UtilService::setImageBase64($url)]);
                    break;
                case 'routine':
                    //小程序
                    $name = $id . '_' . $user['uid'] . '_' . $user['is_promoter'] . '_product.jpg';
                    $imageInfo = SystemAttachment::getInfo($name, 'name');
                    $siteUrl = sysConfig('site_url') . DS;
                    if (!$imageInfo) {
                        $data = 'id=' . $id;
                        if ($user['is_promoter'] || sysConfig('store_brokerage_statu') == 2) $data .= '&pid=' . $user['uid'];
                        $res = \app\models\routine\RoutineCode::getPageCode('pages/goods_details/index', $data, 280);
                        if (!$res) return app('json')->fail('二维码生成失败');
                        $imageInfo = \crmeb\services\UploadService::instance()->setUploadPath('routine/product')->imageStream($name, $res);
                        if (is_string($imageInfo)) return app('json')->fail($imageInfo);
                        if ($imageInfo['image_type'] == 1) $remoteImage = UtilService::remoteImage($siteUrl . $imageInfo['dir']);
                        else $remoteImage = UtilService::remoteImage($imageInfo['dir']);
                        if (!$remoteImage['status']) return app('json')->fail('小程序二维码未能生成');
                        SystemAttachment::attachmentAdd($imageInfo['name'], $imageInfo['size'], $imageInfo['type'], $imageInfo['dir'], $imageInfo['thumb_path'], 1, $imageInfo['image_type'], $imageInfo['time'], 2);
                        $url = $imageInfo['dir'];
                    } else $url = $imageInfo['att_dir'];
                    if ($imageInfo['image_type'] == 1) $url = $siteUrl . $url;
                    return app('json')->successful(['code' => $url]);
            }
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage(), [
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 产品详情
     * @param Request $request
     * @param $id
     * @param int $type
     * @return mixed
     */
    public function detail(Request $request, $id, $type = 0)
    {


        if (!$id || !($storeInfo = StoreProduct::getValidProduct($id))) return app('json')->fail('商品不存在或已下架');
        $siteUrl = sysConfig('site_url');
        $uid = $request->uid();
        //广物库存查询
        $gwlp_obj=new GwlpGoods();
        $is_stock_ok= $gwlp_obj->cache_stock($id,$storeInfo['gwlp_goods_id'],$uid,[]);
        if(!$is_stock_ok)return app('json')->fail('商品无货');
        //广物库存查询

        $storeInfo['integral_money'] = UtilService::getIntegralMoney($storeInfo['use_integral']);
        $storeInfo['image'] = UtilService::setSiteUrl($storeInfo['image'], $siteUrl);
        $storeInfo['image_base'] = UtilService::setSiteUrl($storeInfo['image'], $siteUrl);
        $storeInfo['code_base'] = QrcodeService::getWechatQrcodePath($id . '_product_detail_wap.jpg', '/detail/' . $id);

      //  $data['uid'] = $uid;
        //替换windows服务器下正反斜杠问题导致图片无法显示
        $storeInfo['description'] = preg_replace_callback('#<img.*?src="([^"]*)"[^>]*>#i', function ($imagsSrc) {
            return isset($imagsSrc[1]) && isset($imagsSrc[0]) ? str_replace($imagsSrc[1], str_replace('\\', '/', $imagsSrc[1]), $imagsSrc[0]) : '';
        }, $storeInfo['description']);
        $storeInfo['userCollect'] = StoreProductRelation::isProductRelation($id, $uid, 'collect');
        $storeInfo['userLike'] = StoreProductRelation::isProductRelation($id, $uid, 'like');
        list($productAttr, $productValue) = StoreProductAttr::getProductAttrDetail($id, $uid, $type);
        setView($uid, $id, $storeInfo['cate_id'], 'viwe');
        $data['storeInfo'] = StoreProduct::setLevelPrice($storeInfo, $uid, true);
        $data['similarity'] = StoreProduct::cateIdBySimilarityProduct($storeInfo['cate_id'], 'id,store_name,image,price,sales,ficti', 4);
        $data['productAttr'] = $productAttr;
        $data['productValue'] = $productValue;
        $data['priceName'] = 0;
        if ($uid) {
            $storeBrokerageStatus = sysConfig('store_brokerage_statu') ?? 1;
            if ($storeBrokerageStatus == 2)
                $data['priceName'] = StoreProduct::getPacketPrice($storeInfo, $productValue);
            else {
                $user = $request->user();
                if ($user->is_promoter)
                    $data['priceName'] = StoreProduct::getPacketPrice($storeInfo, $productValue);
            }
            if (!strlen(trim($data['priceName'])))
                $data['priceName'] = 0;
        }
        $data['reply'] = StoreProductReply::getRecProductReply($storeInfo['id']);
        $data['replyCount'] = StoreProductReply::productValidWhere()->where('product_id', $storeInfo['id'])->count();
        if ($data['replyCount']) {
            $goodReply = StoreProductReply::productValidWhere()->where('product_id', $storeInfo['id'])->where('product_score', 5)->count();
            $data['replyChance'] = $goodReply;
            if ($goodReply) {
                $data['replyChance'] = bcdiv($goodReply, $data['replyCount'], 2);
                $data['replyChance'] = bcmul($data['replyChance'], 100, 2);
            }
        } else $data['replyChance'] = 0;
        $data['mer_id'] = $storeInfo['mer_id'];
        $data['system_store'] = ($res = SystemStore::getStoreDispose()) ? $res : [];
        $data['good_list'] = StoreProduct::getGoodList(18, 'image,store_name,price,id,ot_price');
        $data['mapKey'] = sysConfig('tengxun_map_key');
        $data['store_self_mention'] = (int)sysConfig('store_self_mention') ?? 0;//门店自提是否开启
        return app('json')->successful($data);
    }

    /**
     * 为你推荐
     *
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function product_hot(Request $request)
    {
        list($page, $limit) = UtilService::getMore([
            ['page', 0],
            ['limit', 0]
        ], $request, true);
        if (!$limit) return app('json')->successful([]);
        $productHot = StoreProduct::getHotProductLoading('id,image,store_name,cate_id,price,unit_name,ot_price', (int)$page, (int)$limit);
        return app('json')->successful($productHot);
    }

    /**
     * 获取首页推荐不同类型产品的轮播图和产品
     * @param Request $request
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function groom_list(Request $request, $type)
    {
        $info['banner'] = [];
        $info['list'] = [];
        if ($type == 1) {//TODO 精品推荐
            $info['banner'] = GroupDataService::getData('routine_home_bast_banner') ?: [];//TODO 首页精品推荐图片
            $info['list'] = StoreProduct::getBestProduct('id,image,store_name,cate_id,price,ot_price,IFNULL(sales,0) + IFNULL(ficti,0) as sales,unit_name,sort');//TODO 精品推荐个数
        } else if ($type == 2) {//TODO  热门榜单
            $info['banner'] = GroupDataService::getData('routine_home_hot_banner') ?: [];//TODO 热门榜单 猜你喜欢推荐图片
            $info['list'] = StoreProduct::getHotProduct('id,image,store_name,cate_id,price,ot_price,unit_name,sort,IFNULL(sales,0) + IFNULL(ficti,0) as sales', 0, $request->uid());//TODO 热门榜单 猜你喜欢
        } else if ($type == 3) {//TODO 首发新品
            $info['banner'] = GroupDataService::getData('routine_home_new_banner') ?: [];//TODO 首发新品推荐图片
            $info['list'] = StoreProduct::getNewProduct('id,image,store_name,cate_id,price,ot_price,unit_name,sort,IFNULL(sales,0) + IFNULL(ficti,0) as sales', 0, $request->uid());//TODO 首发新品
        } else if ($type == 4) {//TODO 促销单品
            $info['banner'] = GroupDataService::getData('routine_home_benefit_banner') ?: [];//TODO 促销单品推荐图片
            $info['list'] = StoreProduct::getBenefitProduct('id,image,store_name,cate_id,price,ot_price,stock,unit_name,sort');//TODO 促销单品
        }
        return app('json')->successful($info);
    }

    /**
     * 获取首页推荐不同类型产品的轮播图和产品
     * @param Request $request
     * @param $cid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function customize_list(Request $request, $cid)
    {
        $imgUrl = [];
        $customize = Customize::where('is_show',1)->where('id',$cid)->find();
        if(!isset($customize['module_name'])) return app('json')->fail('该模块暂无商品!');
        $info['title'] = $customize['module_name'];
        if($customize['image']) array_push($imgUrl,['img' => $customize['image']]);
        $info['banner'] = $imgUrl;//TODO banner图
        $info['list'] = CustomizeProduct::getCustomizeProduct($cid,'p.id,p.image,p.store_name,p.cate_id,p.price,p.ot_price,p.unit_name,p.sort,IFNULL(p.sales,0) + IFNULL(p.ficti,0) as sales', 0, $request->uid());//TODO 自定义模块产品
        return app('json')->successful($info);
    }

    /**
     * 产品评价数量和好评度
     * @param $id
     * @return mixed
     */
    public function reply_config($id)
    {
        if (!$id || !is_numeric($id)) return app('json')->fail('参数错误!');
        return app('json')->successful(StoreProductReply::productReplyCount($id));
    }

    /**
     * 获取产品评论
     * @param Request $request
     * @param $id
     * @param $type
     * @return mixed
     */
    public function reply_list(Request $request, $id)
    {
        list($page, $limit, $type) = UtilService::getMore([
            ['page', 0], ['limit', 0], ['type', 0]
        ], $request, true);
        if (!$id || !is_numeric($id)) return app('json')->fail('参数错误!');
        $list = StoreProductReply::getProductReplyList($id, (int)$type, $page, $limit);
        return app('json')->successful($list);
    }

}