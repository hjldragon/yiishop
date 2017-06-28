<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;

class GoodscategoryController extends \yii\web\Controller
{
    public $layout='index';
    public function actionIndex()
    {
        //通过层级为0的来获得所有商品分类的顶级数据
    $models =GoodsCategory::findAll(['parent_id'=>0]);
        return $this->render('index',['models'=>$models]);
    }
        //商品详情显示
    public function actionGoods(){
        return $this->render('goods');
    }
}
