<?php

namespace app\admin\controller\store;

use app\admin\controller\AuthController;
use app\admin\model\store\StoreProductAttrValue;
use app\common\logic\gwlp\GwlpGoods;
use crmeb\services\FormBuilder as Form;
use app\admin\model\store\StoreProductAttr;
use app\admin\model\store\StoreProductAttrResult;
use app\admin\model\store\StoreProductRelation;
use app\admin\model\store\FreightTemplate;
use crmeb\services\JsonService;
use think\facade\Db;
use think\facade\Cache;
use crmeb\traits\CurdControllerTrait;
use crmeb\services\UtilService as Util;
use crmeb\services\JsonService as Json;
use crmeb\services\UploadService as Upload;
use app\admin\model\store\StoreCategory as CategoryModel;
use app\admin\model\store\StoreProduct as ProductModel;
use app\admin\model\system\SystemLog as LogModel;
use think\facade\Route as Url;

use app\admin\model\system\SystemAttachment;


/**
 * 产品管理
 * Class StoreProduct
 * @package app\admin\controller\store
 */
class StoreProduct extends AuthController
{

    use CurdControllerTrait;

    protected $bindModel = ProductModel::class;

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $type = $this->request->param('type');
        //获取分类
        $this->assign('cate',CategoryModel::getTierList(null, 1));
        //出售中产品
        $onsale =  ProductModel::where('is_del',0)->where('is_show',1)->count();
        //待上架产品
        $forsale =  ProductModel::where('is_del',0)->where('is_show',0)->count();
        //仓库中产品
        $warehouse =  ProductModel::where('is_del',0)->count();
        //已经售馨产品
        $outofstock = ProductModel::getModelObject()->where(ProductModel::setData(4))->count();
        //警戒库存
        $store_stock = sysConfig('store_stock');
        if($store_stock < 0) $store_stock = 2;
        $policeforce =ProductModel::getModelObject()->where(ProductModel::setData(5))->where('p.stock','<=',$store_stock)->count();
        //回收站
        $recycle =  ProductModel::where('is_del',1)->count();
        if($type == null) $type = 1;
        $this->assign(compact('type','onsale','forsale','warehouse','outofstock','policeforce','recycle'));
        return $this->fetch();
    }
    /**
     * 异步查找产品
     *
     * @return json
     */
    public function product_ist(){
        $where=Util::getMore([
            ['page',1],
            ['limit',20],
            ['activity',''],
            ['price_min',''],
            ['price_max',''],
            ['profit_min',''],
            ['profit_max',''],
            ['store_name',''],
            ['cate_id',''],
            ['excel',0],
            ['order',''],
            ['type',$this->request->param('type')]
        ]);
        return JsonService::successlayui(ProductModel::ProductList($where));
    }
    /**
     * 设置单个产品上架|下架
     *
     * @return json
     */
    public function set_show($is_show='',$id=''){
        ($is_show=='' || $id=='') && JsonService::fail('缺少参数');
        $res=ProductModel::where(['id'=>$id])->update(['is_show'=>(int)$is_show]);
        if($res){
            return JsonService::successful($is_show==1 ? '上架成功':'下架成功');
        }else{
            return JsonService::fail($is_show==1 ? '上架失败':'下架失败');
        }
    }
    /**
     * 快速编辑
     *
     * @return json
     */
    public function set_product($field='',$id='',$value=''){
        $field=='' || $id=='' || $value=='' && JsonService::fail('缺少参数');
        if(ProductModel::where(['id'=>$id])->update([$field=>$value]))
            return JsonService::successful('保存成功');
        else
            return JsonService::fail('保存失败');
    }
    /**
     * 设置批量产品上架
     *
     * @return json
     */
        public function product_show(){
        $post=Util::postMore([
            ['ids',[]]
        ]);
        if(empty($post['ids'])){
            return JsonService::fail('请选择需要上架的产品');
        }else{
            $res=ProductModel::where('id','in',$post['ids'])->update(['is_show'=>1]);
            if($res)
                return JsonService::successful('上架成功');
            else
                return JsonService::fail('上架失败');
        }
    }

    /**
     * 设置批量产品下架
     * @return json
     */
    public function product_unshow(){
        $post=Util::postMore([
            ['ids',[]]
        ]);
        if(empty($post['ids'])){
            return JsonService::fail('请选择需要下架的产品');
        }else{
            $res=ProductModel::where('id','in',$post['ids'])->update(['is_show'=>0]);
            if($res)
                return JsonService::successful('下架成功');
            else
                return JsonService::fail('下架失败');
        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
//        $this->assign(['title'=>'添加产品','action'=>Url::buildUrl('save'),'rules'=>$this->rules()->getContent()]);
//        return $this->fetch('public/common_form');
        $integral_ratio = sysConfig('integral_ratio');
        $field = [
            Form::select('cate_id','产品分类')->setOptions(function(){
                $list = CategoryModel::getTierList(null, 1,3);
                $menus=[];
                foreach ($list as $menu){
                    $menus[] = ['value'=>$menu['id'],'label'=>$menu['html'].$menu['cate_name'],'disabled'=>$menu['level']<3];//,'disabled'=>$menu['pid']== 0];
                }
                return $menus;
            })->filterable(1), //->multiple(1)
            Form::input('store_name','产品名称')->col(Form::col(24)),
            Form::input('store_info','产品简介')->type('textarea'),
            Form::input('keyword','产品关键字')->placeholder('多个用英文状态下的逗号隔开'),
            Form::input('unit_name','产品单位','件')->col(Form::col(12)),
            Form::input('bar_code','产品条码','')->placeholder('请输入商品条形码')->col(Form::col(12)),
            Form::frameImageOne('image','产品主图片(305*305px)',Url::buildUrl('admin/widget.images/index',array('fodder'=>'image')))->icon('image')->width('100%')->height('500px'),
            Form::frameImages('slider_image','产品轮播图(640*640px)',Url::buildUrl('admin/widget.images/index',array('fodder'=>'slider_image')))->maxLength(5)->icon('images')->width('100%')->height('500px')->spin(0),
            Form::number('price','产品售价')->min(0)->col(8),
            Form::number('ot_price','产品市场价')->min(0)->col(8),
            Form::number('give_integral','赠送积分')->min(0)->precision(0)->col(8),
            Form::number('postage','邮费')->min(0)->col(Form::col(8)),
            Form::number('sales','销量',0)->min(0)->precision(0)->col(8)->readonly(1),
            Form::number('ficti','虚拟销量')->min(0)->precision(0)->col(8),
            Form::number('stock','库存')->min(0)->precision(0)->col(8),
            Form::number('cost','产品成本价')->min(0)->col(8),
            Form::number('sort','排序')->col(8),
            Form::number('weight',"产品重量(克)")->min(0)->precision(0)->col(8),
            Form::select('freight_id','运费模板')->col(Form::col(13))->setOptions(function(){
                $list = FreightTemplate::where('status',0)->field('id,name')->select();
                $menus=[];
                foreach ($list as $menu){
                    $menus[] = ['value'=>$menu['id'],'label'=>$menu['name']];//,'disabled'=>$menu['pid']== 0];
                }
                return $menus;
            })->filterable(1),
            Form::radio('is_show','产品状态',0)->options([['label'=>'上架','value'=>1],['label'=>'下架','value'=>0]])->col(8),
            Form::radio('is_hot','热卖单品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_benefit','促销单品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_best','精品推荐',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_new','首发新品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_postage','是否包邮',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_good','是否优品推荐',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::number('use_integral',"积分抵扣(比例{$integral_ratio})")->info('单个商品最多可使用的积分,与比例进行换算即为可抵扣的金额')->placeholder('可使用积分')->min(0)->precision(0)->col(8),
        ];
        $form = Form::make_post_form('添加产品',$field,Url::buildUrl('save'),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**
     * 上传图片
     * @return \think\response\Json
     */
    public function upload()
    {
        $res = Upload::instance()->setUploadPath('store/product/'.date('Ymd'))->image('file');
        SystemAttachment::attachmentAdd($res['name'],$res['size'],$res['type'],$res['dir'],$res['thumb_path'],1,$res['image_type'],$res['time']);
        if(is_array($res))
            return Json::successful('图片上传成功!',['name'=>$res['name'],'url'=>Upload::pathToUrl($res['thumb_path'])]);
        else
            return Json::fail($res);
    }

    /**
     * 保存新建的资源
     *
     *
     */
    public function save()
    {
        $data = Util::postMore([
            ['cate_id',[]],
            'store_name',
            'store_info',
            'keyword',
            ['unit_name','件'],
            'bar_code',
            ['image',[]],
            ['slider_image',[]],
            ['postage',0],
            ['ot_price',0],
            ['price',0],
            ['sort',0],
            ['weight',0],
            ['freight_id',0],
            ['stock',100],
            'sales',
            ['ficti',100],
            ['give_integral',0],
            ['is_show',0],
            ['cost',0],
            ['is_hot',0],
            ['is_benefit',0],
            ['is_best',0],
            ['is_new',0],
            ['mer_use',0],
            ['is_postage',0],
            ['is_good',0],
            ['use_integral',0]
        ]);
        if(count($data['cate_id']) < 1) return Json::fail('请选择产品分类');
        $cate_id=$data['cate_id'];
        $data['cate_id'] = implode(',',$data['cate_id']);
        if(!$data['store_name']) return Json::fail('请输入产品名称');
        if(count($data['image'])<1) return Json::fail('请上传产品图片');
        if(count($data['slider_image'])<1) return Json::fail('请上传产品轮播图');
        if($data['price'] == '' || $data['price'] < 0) return Json::fail('请输入产品售价');
        if($data['ot_price'] == '' || $data['ot_price'] < 0) return Json::fail('请输入产品市场价');
        if($data['weight'] == '' || $data['weight'] < 0) return Json::fail('请输入产品重量');
        if($data['stock'] == '' || $data['stock'] < 0) return Json::fail('请输入库存');
        $data['image'] = $data['image'][0];
        $data['slider_image'] = json_encode($data['slider_image']);
        $data['add_time'] = time();
        $data['description'] = '';
        $data['code_path'] = '';
        $res = ProductModel::create($data);
        foreach ($cate_id as $cid){
            Db::name('store_product_cate')->insert(['product_id'=>$res['id'],'cate_id'=>$cid,'add_time'=>time()]);
        }
        return Json::successful('添加产品成功!');
    }


    public function edit_content($id){
        if(!$id) return $this->failed('数据不存在');
        $product = ProductModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        $this->assign([
            'content'=>ProductModel::where('id',$id)->value('description'),
            'field'=>'description',
            'action'=>Url::buildUrl('change_field',['id'=>$id,'field'=>'description'])
        ]);
        return $this->fetch('public/edit_content');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        if(!$id) return $this->failed('数据不存在');
        $product = ProductModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        $integral_ratio = sysConfig('integral_ratio');
        $field = [
            Form::select('cate_id','产品分类',$product->getData('cate_id'))->setOptions(function(){  //explode(',',$product->getData('cate_id'))
                $list = CategoryModel::getTierList(null, 1,3);
                $menus=[];
                foreach ($list as $menu){
                    $menus[] = ['value'=>$menu['id'],'label'=>$menu['html'].$menu['cate_name'],'disabled'=>$menu['level']< 3];//,'disabled'=>$menu['pid']== 0];
                }
                return $menus;
            })->filterable(1), //->multiple(1)
            Form::input('store_name','产品名称',$product->getData('store_name')),
            Form::input('store_info','产品简介',$product->getData('store_info'))->type('textarea'),
            Form::input('keyword','产品关键字',$product->getData('keyword'))->placeholder('多个用英文状态下的逗号隔开'),
            Form::input('unit_name','产品单位',$product->getData('unit_name'))->col(12),
            Form::input('bar_code','产品条码',$product->getData('bar_code'))->col(12),
            Form::frameImageOne('image','产品主图片(305*305px)',Url::buildUrl('admin/widget.images/index',array('fodder'=>'image')),$product->getData('image'))->icon('image')->width('100%')->height('500px'),
            Form::frameImages('slider_image','产品轮播图(640*640px)',Url::buildUrl('admin/widget.images/index',array('fodder'=>'slider_image')),json_decode($product->getData('slider_image'),1) ? : [])->maxLength(5)->icon('images')->width('100%')->height('500px'),
            Form::number('price','产品售价',$product->getData('price'))->min(0)->col(8),
            Form::number('ot_price','产品市场价',$product->getData('ot_price'))->min(0)->col(8),
            Form::number('give_integral','赠送积分',$product->getData('give_integral'))->min(0)->col(8),
            Form::number('postage','邮费',$product->getData('postage'))->min(0)->col(8),
            Form::number('sales','销量',$product->getData('sales'))->min(0)->precision(0)->col(8)->readonly(1),
            Form::number('ficti','虚拟销量',$product->getData('ficti'))->min(0)->precision(0)->col(8),
            Form::number('stock','库存',ProductModel::getStock($id)>0?ProductModel::getStock($id):$product->getData('stock'))->min(0)->precision(0)->col(8),
            Form::number('cost','产品成本价',$product->getData('cost'))->min(0)->col(8),
            Form::number('sort','排序',$product->getData('sort'))->col(8),
            Form::number('weight',"产品重量(克)",$product->getData('weight'))->min(0)->precision(0)->col(8),
            Form::select('freight_id','运费模板',(string)$product->getData('freight_id'))->setOptions(function(){
                $list = FreightTemplate::where('status',0)->field('id,name')->select()->toArray();
                $menus=[];
                foreach ($list as $menu){
                    $menus[] = ['value'=>$menu['id'],'label'=>$menu['name']];//,'disabled'=>$menu['pid']== 0];
                }
                return $menus;
            })->filterable(1)->col(Form::col(13)),
            Form::radio('is_show','产品状态',$product->getData('is_show'))->options([['label'=>'上架','value'=>1],['label'=>'下架','value'=>0]])->col(8),
            Form::radio('is_hot','热卖单品',$product->getData('is_hot'))->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_benefit','促销单品',$product->getData('is_benefit'))->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_best','精品推荐',$product->getData('is_best'))->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_new','首发新品',$product->getData('is_new'))->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_postage','是否包邮',$product->getData('is_postage'))->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::radio('is_good','是否优品推荐',$product->getData('is_good'))->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::number('use_integral',"积分抵扣(比例{$integral_ratio})",$product->getData('use_integral'))->info('单个商品最多可使用的积分,与比例进行换算即为可抵扣的金额')->placeholder('可使用积分')->min(0)->precision(0)->col(8),
        ];
        $form = Form::make_post_form('编辑产品',$field,Url::buildUrl('update',array('id'=>$id)),9);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }


    /**
     * 保存更新的资源
     *
     * @param $id
     */
    public function update($id)
    {
        $data = Util::postMore([
            ['cate_id',[]],
            'store_name',
            'store_info',
            'keyword',
            'bar_code',
            ['unit_name','件'],
            ['image',[]],
            ['slider_image',[]],
            ['postage',0],
            ['ot_price',0],
            ['price',0],
            ['sort',0],
            ['weight',0],
            ['freight_id',0],
            ['stock',0],
            ['ficti',100],
            ['give_integral',0],
            ['is_show',0],
            ['cost',0],
            ['is_hot',0],
            ['is_benefit',0],
            ['is_best',0],
            ['is_new',0],
            ['mer_use',0],
            ['is_postage',0],
            ['is_good',0],
            ['use_integral',0]
        ]);
        if(count($data['cate_id']) < 1) return Json::fail('请选择产品分类');
        $cate_id=$data['cate_id'];
        $data['cate_id'] = implode(',',$data['cate_id']);
        if(!$data['store_name']) return Json::fail('请输入产品名称');
        if(count($data['image'])<1) return Json::fail('请上传产品图片');
        if(count($data['slider_image'])<1) return Json::fail('请上传产品轮播图');
       // if(count($data['slider_image'])>8) return Json::fail('轮播图最多5张图');
        if($data['price'] == '' || $data['price'] < 0) return Json::fail('请输入产品售价');
        if($data['ot_price'] == '' || $data['ot_price'] < 0) return Json::fail('请输入产品市场价');
        if($data['weight'] == '' || $data['weight'] < 0) return Json::fail('请输入产品重量');
        if($data['stock'] == '' || $data['stock'] < 0) return Json::fail('请输入库存');
        $data['image'] = $data['image'][0];
        $data['slider_image'] = json_encode($data['slider_image']);
        ProductModel::edit($data,$id);
        Db::name('store_product_cate')->where('product_id',$id)->delete();
        foreach ($cate_id as $cid){
            Db::name('store_product_cate')->insert(['product_id'=>$id,'cate_id'=>$cid,'add_time'=>time()]);
        }
        return Json::successful('修改成功!');
    }

    public function attr($id)
    {
        if(!$id) return $this->failed('数据不存在!');
//        $result = StoreProductAttrResult::getResult($id);
        $result = StoreProductAttrValue::getStoreProductAttrResult($id);
        $image = ProductModel::where('id',$id)->value('image');
        $this->assign(compact('id','result','image'));
        return $this->fetch();
    }
    /**
     * 生成属性
     * @param int $id
     */
    public function is_format_attr($id = 0){
        if(!$id) return Json::fail('产品不存在');
        list($attr,$detail) = Util::postMore([
            ['items',[]],
            ['attrs',[]]
        ],null,true);
        $product = ProductModel::get($id);
        if(!$product) return Json::fail('产品不存在');
        $attrFormat = attrFormat($attr)[1];
        if(count($detail)){
            foreach ($attrFormat as $k=>$v){
                foreach ($detail as $kk=>$vv){
                    if($v['detail'] == $vv['detail']){
                        $attrFormat[$k]['price'] = $vv['price'];
                        $attrFormat[$k]['cost'] = isset($vv['cost']) ? $vv['cost'] : $product['cost'];
                        $attrFormat[$k]['sales'] = $vv['sales'];
                        $attrFormat[$k]['pic'] = $vv['pic'];
                        $attrFormat[$k]['check'] = false;
                        break;
                    }else{
                        $attrFormat[$k]['cost'] = $product['cost'];
                        $attrFormat[$k]['price'] = '';
                        $attrFormat[$k]['sales'] = '';
                        $attrFormat[$k]['pic'] = $product['image'];
                        $attrFormat[$k]['check'] = true;
                    }
                }
            }
        }else{
            foreach ($attrFormat as $k=>$v){
                $attrFormat[$k]['cost'] = $product['cost'];
                $attrFormat[$k]['price'] = $product['price'];
                $attrFormat[$k]['sales'] = $product['stock'];
                $attrFormat[$k]['pic'] = $product['image'];
                $attrFormat[$k]['check'] = false;
            }
        }
        return Json::successful($attrFormat);
    }

    public function set_attr($id)
    {
        if(!$id) return $this->failed('产品不存在!');
        list($attr,$detail) = Util::postMore([
            ['items',[]],
            ['attrs',[]]
        ],null,true);
        $res = StoreProductAttr::createProductAttr($attr,$detail,$id);
        if($res)
            return $this->successful('编辑属性成功!');
        else
            return $this->failed(StoreProductAttr::getErrorInfo());
    }

    public function clear_attr($id)
    {
        if(!$id) return $this->failed('产品不存在!');
        if(false !== StoreProductAttr::clearProductAttr($id) && false !== StoreProductAttrResult::clearResult($id))
            return $this->successful('清空产品属性成功!');
        else
            return $this->failed(StoreProductAttr::getErrorInfo('清空产品属性失败!'));
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
        if(!ProductModel::be(['id'=>$id])) return $this->failed('产品数据不存在');
        if(ProductModel::be(['id'=>$id,'is_del'=>1])){
            $data['is_del'] = 0;
            if(!ProductModel::edit($data,$id))
                return Json::fail(ProductModel::getErrorInfo('恢复失败,请稍候再试!'));
            else
                return Json::successful('成功恢复产品!');
        }else{
            $data['is_del'] = 1;
            if(!ProductModel::edit($data,$id))
                return Json::fail(ProductModel::getErrorInfo('删除失败,请稍候再试!'));
            else
                return Json::successful('成功移到回收站!');
        }

    }




    /**
     * 点赞
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function collect($id){
        if(!$id) return $this->failed('数据不存在');
        $product = ProductModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        $this->assign(StoreProductRelation::getCollect($id));
        return $this->fetch();
    }

    /**
     * 收藏
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function like($id){
        if(!$id) return $this->failed('数据不存在');
        $product = ProductModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        $this->assign(StoreProductRelation::getLike($id));
        return $this->fetch();
    }

    /**
     * 修改产品价格
     */
    public function edit_product_price(){
        $data = Util::postMore([
            ['id',0],
            ['price',0],
        ]);
        if(!$data['id']) return Json::fail('参数错误');
        $res = ProductModel::edit(['price'=>$data['price']],$data['id']);
        if($res) return Json::successful('修改成功');
        else return Json::fail('修改失败');
    }

    /**
     * 修改产品库存
     *
     */
    public function edit_product_stock(){
        $data = Util::postMore([
            ['id',0],
            ['stock',0],
        ]);
        if(!$data['id']) return Json::fail('参数错误');
        $res = ProductModel::edit(['stock'=>$data['stock']],$data['id']);
        if($res) return Json::successful('修改成功');
        else return Json::fail('修改失败');
    }

    /**
     * 获取供应链商品弹窗
     * @return string
     */
    public function get_gwgoods_progress()
    {
        $page = cache('GET_GWLP_GOODS_PAGE');
        if(!$page) $page = 1;
        $this->assign('page',$page);
        return $this->fetch();
    }

    /**
     * 获取广物供应链商品
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGWLPGoods()
    {
        $data = Util::postMore([
            ['page',1],
            ['type',1],
            ['total_page',1],
            ['price_min',0],
            ['price_max',0]
        ]);
        $expire = 24 * 60 * 60;
        $count = 0;
        $page_count = $data['type'] == 2 ? 20 : 50;
        $gwlp_goods = new GwlpGoods();
        cache('GET_GWLP_GOODS_PAGE',(int)$data['page'],$expire);
        $result = $gwlp_goods->getGWLPGoodsList($data['page'],$page_count,['price_min'=>$data['price_min'],'price_max'=>$data['price_max']]);
        if(is_array($result)){
            if($data['type'] == 2){
                $total = (int)$result['total'];
                //$total = 500;
                if($total == 0) $total_page = 0;
                else $total_page = ceil($total / 50);
                return Json::successful('',['total_page' => $total_page,'total_count' => $total]);
            }
            unset($result['total']);
            if(!empty($result)){
                foreach ($result as $detail){
                    $saveRes = $gwlp_goods->saveGWLPGoods($detail);
                    if($saveRes) $count++;
                }
                if($data['page'] == $data['total_page']) cache('GET_GWLP_GOODS_PAGE',1,$expire);
                return Json::successful('',['get_count' => $count]);
            }else {
                cache('GET_GWLP_GOODS_PAGE',1,$expire);
                return Json::successful('',['successful' => true]);
            }
        }else {
            return Json::fail($result);
        }
    }

    /**
     *修改商品利润率--暂用--20200207
     */
    public function updateGwlpProfitRate()
    {
        $list = ProductModel::where('gwlp_goods_id','>',0)->select();
        $count = 0;
        foreach ($list as $key => $value) {
            $profit_rate = round(($value['gwlp_shop_price'] - $value['gwlp_cost_price']) / $value['gwlp_shop_price'] * 100,2);
            $res = ProductModel::edit(['gwlp_profitrate' => $profit_rate],$value['id']);
            if($res !== false) $count++;
        }
        echo "成功修改$count";
    }

    /**
     * 批量调整商品利润弹窗页面
     */
    public function profit_index()
    {
        return $this->fetch();
    }

    /**
     * 获取当前条件需调整利润率的商品数量
     */
    public function getEditProfitTotal()
    {
        $where=Util::postMore([
            ['is_all',1],
            ['ids',[]],
            ['activity',''],
            ['price_min',''],
            ['price_max',''],
            ['profit_min',''],
            ['profit_max',''],
            ['store_name',''],
            ['cate_id',''],
            ['type',$this->request->param('type')]
        ]);
        $total = ProductModel::getEditProfitCount($where);
        Json::successful("",['total' => $total]);
    }


    /**
     * 批量修改商品利润
     */
    public function confirm_profit_rate()
    {
        $where=Util::postMore([
            ['page',1],
            ['limit',20],
            ['is_all',1],
            ['profit',''],
            ['ids',[]],
            ['activity',''],
            ['price_min',''],
            ['price_max',''],
            ['profit_min',''],
            ['profit_max',''],
            ['store_name',''],
            ['cate_id',''],
            ['type',$this->request->param('type')]
        ]);
        $result = ProductModel::updateProfitRate($where);
        if(!$result) return Json::fail('未找到需调整利润的商品');
        else {
            LogModel::setCurrentVisit($this->adminInfo,"调整{$result['total']}个商品的利润为[{$where['profit']}%]");
            Json::successful("成功调整{$result['total']}个商品利润，请等待...",['total' => $result['total']]);
        }
    }

    /**
     * 更新产品价格
     */
    public function updateProductPrice(){
        $date=Util::postMore([['page',1]]);
        $gwlp_goods = new GwlpGoods();
        $next_page= $gwlp_goods->updateProductPrice($date['page']);
        Json::successful("",['page' => $date['page'],'next_page'=>$next_page]);

    }
}
