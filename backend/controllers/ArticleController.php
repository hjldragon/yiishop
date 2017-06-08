<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Articlecategory;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
        //建立添加方法
    public  function  actionAdd(){
        //获取数据
        $model= new Article();
        //判断传送过来的情况
        if($model->load(\Yii::$app->request->post())){
            //验证是否符合要求
            if($model->validate()){
                $model->create_time=time();
                $model->save();
                //弹出提示消息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                return $this->redirect(['article/list']);
            }
        }
        $article=Articlecategory::find()->all();
        //var_dump($article);exit;
        //视图
        return $this->render('add',['model'=>$model,'article'=>$article]);
    }
    public function actionList(){
        $all=Article::find();
        //总条数
        $total=$all->count();
        //设置总条条数和页数
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>3,
        ]);
        //设置每页显示
        $models=$all->offset($page->offset)->limit($page->limit)->all();
        return $this->render('list',['models'=>$models,'page'=>$page]);
    }
    public  function actionDel($id){
        $model=Article::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect(['article/list']);
    }
    //设置修改的方法
    public  function actionEdit($id){
        $model=Article::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('warning','修改成功');
                return $this->redirect(['article/list']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        $article=Articlecategory::find()->all();

        //视图
        return $this->render('add',['model'=>$model,'article'=>$article]);
    }
}
