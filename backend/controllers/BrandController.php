<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
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
        $modls=$all->offset($page->offset)->orderBy(['sort'=>SORT_DESC])->limit($page->limit)->all();
        return $this->render('list',['models'=>$modls,'page'=>$page]);
    }
        //建立brand品牌的添加功能
    public  function actionAdd(){
        //加载数据库模型
        $model= new Brand();
        if($model->load(\Yii::$app->request->post())){
            //实例化图片名称
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
//            var_dump($model->name);exit;
            //验证传送过来的是否符合验证
            //var_dump($model->getErrors());exit;
            if($model->validate()){
                //判断时候有图片传送过来
//                if($model->imgFile){
//                    //如果有就是实例化图片地址
//                    $fileName='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                    //并将是实例化的图片地址保存到相对应的文件中
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                        //再保存图片到数据库
//                    $model->logo=$fileName;
//                }
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
        //通过该Id找到该对象的所有数据资料
            $model = Brand::findOne(['id'=>$id]);
        //var_dump($model);exit;
            //判断是否通过requeset的传送方式传送过来
            if($model->load(\Yii::$app->request->post())){
                if($model->validate()){
                    $model->save();
                    //提示修改成功
                    \Yii::$app->session->setFlash('warning','已修改成功');
                    //跳转页面
                    return $this->redirect(['brand/list']);
                }else{
                    var_dump($model->getErrors());exit;
                }
            }
        //显示修改视图
        return $this->render('add',['model'=>$model]);
    }

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
          /*      'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //上传图片的保存路径
                    $imgUrl= $action->getWebUrl();
                   // $action->output['fileUrl'] = $action->getWebUrl();
                    //调用七牛用组件，将图上传到七牛云上面
                    $qiniu=\Yii::$app->qiniu;
                    $qiniu->uploadFile($action->getSavePath(),$action->getWebUrl());
                   // $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    //获取该图片在七牛云的地址
                    //$url=$qiniu->getLink($imgUrl);
                    $url=$qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $url;
                    //这是保存到当地文件的upload中
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }


}
