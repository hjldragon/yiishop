<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    //设置商品分类添加类
    public function actionAdd(){
        //获取数据模型
        $model = new GoodsCategory();
       // var_dump($model);exit;
        //加载数据并判断传送方式
        if($model->load(\Yii::$app->request->post())){
            //如果是，判断是否符合模型的验证规则
            if($model->validate()){
                //如果都符合验证要求就保存数据
//                var_dump($model);exit;
                $model->save();
                //弹出提示框，显示添加成功
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页页面
                return $this->redirect(['goods-category/list']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //分配视图显示
        return $this->render('add',['model'=>$model]);
    }
    //建立显示商品分类的的视图,并设置分页
    public function actionList(){
        //找到数据模型的所有数据
        $all=GoodsCategory::find();
        //获取总条数
        $total=$all->count();

        //调用框架的分页方法来设置总条数和每页显示条数
        $page = new Pagination([
            //总页数
            'totalCount'=>$total,
            //每页显示数
            'defaultPageSize'=>4,
        ]);

        //设置一个变量来分分页数据和所有模型数据
        $models=$all->offset($page->offset)->orderBy('id desc')->limit($page->limit)->all();
        //var_dump($models);exit;
        //var_dump($models);
        //显示视图
        return $this->render('list',['models'=>$models,'page'=>$page]);
    }
        //设置分类的删除方法类
    public function actionDel($id){
        //找到删除id对的数据
        $model=GoodsCategory::findOne(['id'=>$id]);
        //进行删除保存
        $model->delete();
        //提示删除成功
        \Yii::$app->session->setFlash('danger','删除成功');
        //跳转页面
        return $this->redirect(['goods-category/list']);

    }
    //设置修改数据的方法
    public function actionEdit($id){
        //找到要修改的数据
        $model=GoodsCategory::findOne(['id'=>$id]);
        //var_dump($model);exit;
        //判断修改的传送方式是否正确
        if($model->load(\Yii::$app->request->post())){
            //验证是否符合模型的验证规则
            if($model->validate()){
                //保存修改数据
                $model->save();
                //提示修改成
                \Yii::$app->session->setFlash('waring','修改成功');
                //跳转页面
                return $this->redirect(['goods-category/list']);
            }else{
                 var_dump($model->getErrors());
            }
        }
        //视图分配
        return $this->render('add',['model'=>$model]);
    }
}
