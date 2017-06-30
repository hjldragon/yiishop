<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\Images;
use frontend\components\SphinxClient;
use frontend\models\GoodsSearchForm;
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
    //商品搜索
    public function actionSearch(){


        $all = Goods::find();
        //$search->search($all);
        if($keyword=\Yii::$app->request->get('keyword')){
        //引入中文收缩的文件
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        //$info = 'JPK';
        $res = $cl->Query($keyword, 'goods');//shopstore_search
//print_r($cl);
       // print_r($res);
            //这是没搜索到
        if(!isset($res['matches'])){
            $all->where(['id'=>0]);
        }else{
            //搜索结果显示
            $ids=ArrayHelper::map($res['matches'],'id','id');
            $goods=$all->where(['in','id',$ids]);
        }
        //var_dump($res);exit;
        }
        $goods=$all->all();
        $keywords = array_keys($res['words']);
        $options = array(
            'before_match' => '<span style="color:red;">',
            'after_match' => '</span>',
            'chunk_separator' => '...',
            'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
        );
//关键字高亮
//        var_dump($models);exit;
        foreach ($goods as $index => $item) {
            $name = $cl->BuildExcerpts([$item->name], 'goods', implode(',', $keywords), $options); //使用的
            //索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
            $goods[$index]->name = $name[0];
//            var_dump($name);
        }
        return $this->render('search',['goods'=>$goods]);
    }
}