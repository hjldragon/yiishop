<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\Images;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class GoodsController extends Controller{
        public $layout='goods';
    public function actionList($cate_id){
        //var_dump($cate_id);exit;
        $all =Goods::find();
        $total =$all->count();
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>8,
        ]);
        //找到所有分类的一级商品分类数据
        $cate=GoodsCategory::findOne(['id'=>$cate_id]);
        //获取所有一级商品分类下面的所有子ID
        //var_dump($cate->id);exit;
        $cates=GoodsCategory::find()->where(['>=','lft',$cate->lft])->andWhere(['<=','rgt',$cate->rgt])
            ->andWhere(['tree'=>$cate->tree])->all();
        //var_dump($cates);exit;
        //找到所有分类的id
        $cateIds=ArrayHelper::map($cates,'id','id');
//        if($cate->depth==2){
//            $goods =$all->offset($page->offset)->where(['in','good_category_id',$cate->id])->limit($page->limit)->all();
//        }
        //var_dump($cateIds);exit;
       //通过所有分类id来找到所有分类下面的商品
        //$goods = Goods::findAll(['good_category_id'=>$cateIds]);
        $goods =$all->offset($page->offset)->where(['in','good_category_id',$cateIds])->limit($page->limit)->all();

        //var_dump($goods);exit;

        return $this->render('list',['goods'=>$goods,'page'=>$page]);
    }
    //商品详情展示
    public  function actionGoods($id){
        //var_dump($id);exit;
        //通过id找到该商品的所有数据
        $goods =Goods::findOne(['id'=>$id]);
        $images=Images::findAll(['goods_id'=>$id]);
        //var_dump($images);exit;
        //var_dump($model);exit;
        return $this->render('goods',['goods'=>$goods,'images'=>$images]);
    }

}