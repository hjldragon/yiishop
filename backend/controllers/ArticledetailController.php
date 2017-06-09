<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Articlecategory;
use backend\models\Articledetail;
use yii\data\Pagination;

class ArticledetailController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    //添加方法
    public  function actionAdd(){
        //获取数据库信息
        $model = new Articledetail();
        // var_dump($model);exit;
        //检测判断传送过来的数据
        if($model->load(\Yii::$app->request->post())){
            //是post传送过来了进行检查是否符合要求
            if($model->validate()){
                //保存数据
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                return $this->redirect(['articledetail/list']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }

        //视图显示
        $article =Article::find()->all();
        //var_dump($article);exit;
        return $this->render('add',['model'=>$model,'article'=>$article]);
    }
    public function actionList(){
        //获取所有数据
        $all= Articledetail::find();
        //获取总条数
        $total=$all->count();
       // var_dump($total);exit;
        //设置分页每条条数和总页数
        $page =new Pagination([
            //总条数
            'totalCount'=>$total,
            //每页显示条数
            'defaultPageSize'=>3,
        ]);
        //显示所有数据和条数
        $models=$all->offset($page->offset)->limit($page->limit)->all();
        //var_dump($models);exit;
        return $this->render('list',['models'=>$models,'page'=>$page]);
    }
    public function actionDel($id){
        $model=Article::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect(['articledetail/list']);
        //var_dump($model);exit;
    }
    //修改状态
    public function actionEdit($id){
        $model=Article::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('warning','修改成功');
                return $this->redirect(['articledetail/list']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        $article=Articlecategory::find()->all();

        //视图
        return $this->render('edit',['model'=>$model,'article'=>$article]);
    }
}
