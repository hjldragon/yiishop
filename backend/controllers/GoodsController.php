<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    //设置商品的添加方法
    public function actionAdd(){
        //获取模型数据
        $model =new Goods();
        //获取商品详情的模型数据
        $model1=new GoodsIntro();
        //获取商品添加日的模型数据
        $model2=new GoodsDayCount();
        //设置添加的传送发送
        if($model->load(\Yii::$app->request->post())&&$model1->load(\Yii::$app->request->post())){
            //实例化图片地址
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证是否符合Model里的验证规则
            //var_dump($model->good_category_id);exit;
            if($model->validate() && $model1->validate()){
                //判断是否有图片传送过来
                if($model->imgFile){
                    //如果有就实例化图片
                    $fileName='/images/goods/'.uniqid().'.'.$model->imgFile->extension;
                    //保存图片地址
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //将实例化字符串保存到数据库里
                    $model->logo=$fileName;
                }
                //获取商品添加日的添加商品数量
                //设置一个变量来找到当日的数量
                $dayAdd=GoodsDayCount::findOne(['day'=>date('Y-m-d')]);
                //var_dump($dayAdd);exit;
                //如果当日有数据
                if($dayAdd){
                    $dayAdd->count=$dayAdd->count+1;
                    //var_dump($model2->count);exit;
                    //就保存到添加日的数据中
                    $dayAdd->save();
                    //如果当日天商品中没有数据
                }else{
                    //每天第一次添加的所执行的步骤
                    $model2->count=1;
                    //获取商品添加日的日期
                    $model2->day=date('Y-m-d');
                   // var_dump($model2->count);exit;
                    $model2->save();
                }
                //获取该商品的添加时间
                $model->create_time=date('Y/m/d G:i:s');
                //获取该商品添加的货号
                $model->sn=date('Ymd').str_pad($dayAdd->count,4,"0",STR_PAD_LEFT);
//                var_dump($model->sn);exit;
                //保存商品天数据
                $model->save();
                //获取商品详情的goods_id
                $model1->goods_id=$model->id;
                //保存商品详情数据
                $model1->save();
                    //提示保存添加成功的消息
                \Yii::$app->session->setFlash('success','添加商品成功');
                //跳转页面
                $this->redirect(['list']);
            }else{
                var_dump($model->getErrors());
                var_dump($model1->getErrors());
                var_dump($model2->getErrors());exit;
            }
        }
//获取商品品牌的所有数据
        $brand=Brand::find()->all();
        //获取所有商品分类的数据
        $category=GoodsCategory::find()->all();

        //显示视图
        return $this->render('add',['model'=>$model,'model1'=>$model1,'brand'=>$brand,'category'=>$category]);
    }
//建立所有商品列表的显示页面
public function actionList(){
        //获取所有商品数据模型
    $all=Goods::find();
    //设置总条数
    $total=$all->count();
    //设置每页页数和总条数
    $page = new Pagination([
        'totalCount'=>$total,
    'defaultPageSize'=>4,
    ]);
    //设置变量来进行分页数据显示
    $models=$all->offset($page->offset)->orderBy('sort desc')->limit($page->limit)->all();
    //显示视图
    return $this->render('list',['models'=>$models,'page'=>$page]);
}

public function actionDel($id){
    $model = Goods::findOne(['id'=>$id]);
    $model->status=2;
    $model->save();
    //提示回收成功
    \Yii::$app->session->setFlash('danger','回收成功');
    return $this->redirect(['goods/list']);
}

    public function actionEdit($id){
        //获取商品数据模型数据
        $model =Goods::findOne(['id'=>$id]);
        //获取商品详情的模型数据
        $model1=GoodsIntro::findOne(['goods_id'=>$id]);
        //设置添加的传送发送
        if($model->load(\Yii::$app->request->post())&&$model1->load(\Yii::$app->request->post())){
            //实例化图片地址
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证是否符合Model里的验证规则
            //var_dump($model->good_category_id);exit;
            if($model->validate() && $model1->validate()){
                //判断是否有图片传送过来
                if($model->imgFile){
                    //如果有就实例化图片
                    $fileName='/images/goods/'.uniqid().'.'.$model->imgFile->extension;
                    //保存图片地址
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //将实例化字符串保存到数据库里
                    $model->logo=$fileName;
                }
                //获取该商品的修改时间
                $model->create_time=date('Y/m/d G:i:s');
//                var_dump($model->sn);exit;
                //保存商品天数据
                $model->save();
                //保存商品详情数据
                $model1->save();
                //提示保存添加成功的消息
                \Yii::$app->session->setFlash('success','商品修改成功');
                //跳转页面
                $this->redirect(['list']);
            }else{
                var_dump($model->getErrors());
                var_dump($model1->getErrors());exit;
            }
        }
//获取商品品牌的所有数据
        $brand=Brand::find()->all();
        //获取所有商品分类的数据
        $category=GoodsCategory::find()->all();

        //显示视图
        return $this->render('add',['model'=>$model,'model1'=>$model1,'brand'=>$brand,'category'=>$category]);

    }
    //获取商品详情情况
    public function actionContent($id){
            $model=GoodsIntro::findOne(['goods_id'=>$id]);
            $id=Goods::findOne(['id'=>$id]);
            //跳转页面
        return $this->render('content',['model'=>$model,'id'=>$id]);
    }

}
