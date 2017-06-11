<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Articlecategory;
use backend\models\Articledetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
        //建立添加方法
    public  function  actionAdd(){
        //获取文章模型数据
        $model= new Article();
        //获取文章详情的数据模型
        $content = new Articledetail();
        //判断传送过来的情况
        if($model->load(\Yii::$app->request->post()) && $content->load(\Yii::$app->request->post())){
            //验证是否符合要求
            if($model->validate() && $content->validate()){
                //保存文章的内容
                $model->create_time=time();

                $model->save();
                //var_dump($model->id);exit;
                //保存文章详情的内容
                $content->article_id=$model->id;
                $content->save();
                //弹出提示消息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                return $this->redirect(['article/list']);
            }else{
               var_dump($model->getErrors());
                var_dump($content->getErrors());exit;
           }
        }
        //获取文章分类的模型所有数据
        $article=Articlecategory::findAll(['status'=>1]);
        //var_dump($article);exit;
        //分配显示视图
        return $this->render('add',['model'=>$model,'article'=>$article,'content'=>$content]);
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
        $models=$all->offset($page->offset)->orderBy('sort desc')->limit($page->limit)->all();
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
        $content=Articledetail::findOne(['article_id'=>$id]);
//            var_dump($content->content);
//            var_dump($model->name);
//            exit;
        if($model->load(\Yii::$app->request->post()) && $content->load(\Yii::$app->request->post())){
            if($model->validate() && $content->validate()){
                $model->save();
                $content->save();
                \Yii::$app->session->setFlash('warning','修改成功');
                return $this->redirect(['article/list']);
            }else{
                var_dump($model->getErrors());
                var_dump($content->getErrors());
                exit;
            }
        }
        //获取分类的数据
        $article=Articlecategory::find()->all();
        //视图
        return $this->render('add',['model'=>$model,'article'=>$article,'content'=>$content]);
    }
    //查看文章内容详细情况
    public function actionContent($id){
        //通过id来获取该文章的标题
        $title = Article::findOne(['id'=>$id]);
        $content = Articledetail::findOne(['article_id'=>$id]);

        //分配视图数据
        return $this->render('content',['title'=>$title,'content'=>$content]);

    }

}
