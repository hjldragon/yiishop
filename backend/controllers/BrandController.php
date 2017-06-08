<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    //显示所有品牌列表
    public function actionList()
    {
        //找到所有数据
        $all= Brand::find();
        //var_dump($all->all());exit;
        //获取可显示的条数
        $total=$all->count();
        //设置分页每条条数和总页数
        $page = new Pagination([
            //总条数
            'totalCount'=>$total,
            //每页显示条数
            'defaultPageSize'=>3,
        ]);
        //显示所有数据和条数
        $modls=$all->offset($page->offset)->limit($page->limit)->all();
        return $this->render('list',['models'=>$modls,'page'=>$page]);
    }
        //建立brand品牌的添加功能
    public  function actionAdd(){
        //加载数据库模型
        $model= new Brand();
        if($model->load(\Yii::$app->request->post())){
            //实例化图片名称
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
//            var_dump($model->name);exit;
            //验证传送过来的是否符合验证
            //var_dump($model->getErrors());exit;
            if($model->validate()){
                //判断时候有图片传送过来
                if($model->imgFile){
                    //如果有就是实例化图片地址
                    $fileName='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
                    //并将是实例化的图片地址保存到相对应的文件中
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                        //再保存图片到数据库
                    $model->logo=$fileName;
                }
                //保存所有数据到数据库
                //var_dump($model);exit;
                $model->save();
                //提示添加成功信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转显示页面
                return $this->redirect(['brand/list']);

            }else{
                var_dump($model->getErrors());exit;
            }
        }

        //分配视图
        return $this->render('add',['model'=>$model]);
    }
    //建立品牌的删除
    public function actionDel($id){
        $model = Brand::findOne(['id'=>$id]);
        //var_dump($model);exit;
        $model->status=-1;
        $model->save();
        return $this->redirect(['brand/list']);
    }
    //设置修改的方法
    public function actionEdit($id){
//        if($model=Brand::findOne('status'==-1)){
//            \Yii::$app->session->setFlash('danger','该数据无法进行修改');
//        }else{
            $model = Brand::findOne(['id'=>$id]);
//            var_dump($model->status);exit;
        if($model->status!=-1){
            //var_dump($model);exit;
            //判断是否通过requeset的传送方式传送过来
            if($model->load(\Yii::$app->request->post())){
                //实例化图片名称
                $model->imgFile=UploadedFile::getInstance($model,'imgFile');
                //对传送过来数据进行验证
                if($model->validate()){
                    //判断时候有图片上传
                    if($model->imgFile){
                        //如果有就实例化图片地址并给名称
                        $fileName='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
                        //保存实例化图片的地址
                        $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                        //将实例化图片地址的名称保存到数据库中
                        $model->logo=$fileName;
                    }
                    //保存所有传送过来的修改数据
                    //var_dump($model->name);exit;
                    $model->save();
                    //提示修改成功
                    \Yii::$app->session->setFlash('warning','已修改成功');
                    //跳转页面
                    return $this->redirect(['brand/list']);
                }else{
                    var_dump($model->getErrors());exit;
                }
            }
        }else{
            \Yii::$app->session->setFlash('danger','对不起该数据不能进行修改');
            return $this->redirect(['brand/list']);
        }
        //显示修改视图
        return $this->render('add',['model'=>$model]);
    }
}
