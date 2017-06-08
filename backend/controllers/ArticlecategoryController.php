<?php

namespace backend\controllers;

use backend\models\Articlecategory;
use yii\data\Pagination;

class ArticlecategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
        //添加方法
    public  function actionAdd(){
        //获取数据库信息
        $model = new Articlecategory();
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
                return $this->redirect(['articlecategory/list']);
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }

        //视图显示
        return $this->render('add',['model'=>$model]);
    }
    public function actionList(){
            //获取所有数据
        $all= Articlecategory::find();
        //获取总条数
        $total=$all->count();
        //设置分页每条条数和总页数
        $page =new Pagination([
            //总条数
            'totalCount'=>$total,
            //每页显示条数
            'defaultPageSize'=>3,
        ]);
        //显示所有数据和条数
        $models=$all->offset($page->offset)->limit($page->limit)->all();
        return $this->render('list',['models'=>$models,'page'=>$page]);
    }
    //建立删除的方法，只修该状态
    public function actionDel($id){
        $model=Articlecategory::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect(['articlecategory/list']);

    }
    //建立修改的方法
    public  function actionEdit($id){
        //通过id来找到数据库的数据
        $model=Articlecategory::findOne(['id'=>$id]);
        //判断是否是正常传送方式过来
        if($model->load(\Yii::$app->request->post())){
            //检查是否符合自动
            if($model->validate()){
                //符合就保存数据
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['articlecategory/list']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //显示视图
        return $this->render('add',['model'=>$model]);
    }
}
