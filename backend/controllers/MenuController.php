<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Menu;
use yii\data\Pagination;

class MenuController extends \yii\web\Controller
{
//    public function behaviors()
//    {
//        return [
//            'rbac'=>[
//                'class'=>RbacFilter::className(),
//            ]
//        ];
//    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    //设置菜单栏的增删查改
    //菜单的添加
    public function actionAdd(){
        $model =new Menu();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return  $this->redirect(['list']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //菜单的查看
    public function actionList(){
     $all =Menu::find();
     $total=$all->count();
     $page = new Pagination([
        'totalCount'=>$total,
         'defaultPageSize'=>6,
     ]);
     $models = $all->offset($page->offset)->orderBy('sort desc')->limit($page->limit)->all();
     return $this->render('list',['models'=>$models,'page'=>$page]);
    }
    //菜单的修改
    public function actionEdit($id){
        $model =Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['list']);
        }

        return $this->render('add',['model'=>$model]);
    }
    //菜单的删除
    public function actionDel($id){
        $model = Menu::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['list']);
    }

}
