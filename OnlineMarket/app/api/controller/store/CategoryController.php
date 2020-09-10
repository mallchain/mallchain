<?php
namespace app\api\controller\store;


use app\models\store\StoreCategory;
use app\Request;

class CategoryController
{
    public function category(Request $request)
    {
        $cateogry = StoreCategory::where(['pid' => 0,'is_show' => 1])->order('sort desc,id desc')->select();
        $cateogry2 = StoreCategory::with('children')->where(['level' => 2,'is_show' => 1])->order('sort desc,id desc')->select();
        $cateogry = $cateogry->hidden(['add_time','is_show','sort'])->toArray();
        $cateogry2 = $cateogry2->hidden(['add_time','is_show','sort','children.sort','children.add_time','children.pid','children.is_show'])->toArray();
        foreach ($cateogry as $key => $cate){
            $cateogry[$key]['children'] = [];
            foreach ($cateogry2 as $key2 => $cate2){
                if($cate['id'] == $cate2['pid']){
                    array_push($cateogry[$key]['children'],$cate2);
                }
            }
        }
        return app('json')->success($cateogry);
    }
}