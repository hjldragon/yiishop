<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use backend\models\Images;
use backend\models\Imgs;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
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
                //var_dump($model);exit;
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
               // $model->sn=date('Ymd').str_pad($dayAdd->count,4,"0",STR_PAD_LEFT);
                $model->sn= date('Ymd').sprintf("%04d",$dayAdd->count+1);
                //var_dump($model->sn);exit;
//                var_dump($model->sn);exit;
                //保存商品天数据
                $model->save();
                //获取商品详情的goods_id
                //var_dump($model->id);exit;
                $model1->goods_id=$model->id;
                //保存商品详情数据
                $model1->save();
                    //提示保存添加成功的消息
                \Yii::$app->session->setFlash('success','添加商品成功');
                //跳转页面
                $this->redirect(['goods/list']);
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
        //实例化查询对象
    $all=Goods::find();
    //获取GOODSEARCHFORM的实例模型对象
    $model = new GoodsSearchForm();
    //var_dump($model);exit;
//    if($keyword=\Yii::$app->request->get('keyword')){
//       $all ->andWhere(['like','name',$keyword]);
//    }
//    if($sn=\Yii::$app->request->get('sn')){
//        $all->andWhere(['like','sn',$sn]);
//    }
    //调用GOODsearchForm中的搜索方法
    $model->search($all);//$all是传到模型方法里好，识别搜索参数
    //var_dump($all);
//    //设置总条数
//    $total=$all->count();
    //设置每页页数和总条数
    $page = new Pagination([
        'totalCount'=>$all->count(),
    'defaultPageSize'=>4,
    ]);
    //设置变量来进行分页数据显示
    $models=$all->offset($page->offset)->orderBy('sort desc')->limit($page->limit)->all();
    //显示视图
    return $this->render('list',['models'=>$models,'page'=>$page,'model'=>$model]);
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
                $this->redirect(['goods/list']);
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
        //建立相册的添加方法
    public function actionImgs($id){
        //找到要创建相册的商品号
        $goods=Goods::findOne(['id'=>$id]);

        $images =$goods->getImages()->all();
//        var_dump($images);
//        exit;

//        if($model==null){
//            throw new NotFoundHttpException('商品不存在');
//        }
        return $this->render('imgs',['goods'=>$goods,'images'=>$images]);

    }
    /*
 * AJAX删除图片
 */
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
       //var_dump($id);exit;
        $model = Images::findOne(['id'=>$id]);
       //var_dump($model);exit;
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
            //var_dump($model);exit;
        }

    }
    //图片上传插件
    //百度ud插件
    public function actions()
    {
        return [
            'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]

            ],
            //这是用来保存上面ueditor编辑器的图片地址
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "",//图片访问路径前缀
                    "imagePathFormat" => "/upload/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ],

            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/images/goodsimgs',
                'baseUrl' => '@web/images/goodsimgs',
                'enableCsrf' => true,// default
                'postFieldName' => 'Filedata',// default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],//如果不注释下面的format是加载的组件的
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                //这段好像也没用
                /*'format' => function (UploadAction $action) {
                 $fileext = $action->uploadfile->getExtension();
                 $filename = sha1_file($action->uploadfile->tempName);
                 return "{$filename}.{$fileext}";
             },*/

               'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],  //上传文件的格式设置
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
      /*              //上传图片的保存路径
                    $imgUrl= $action->getWebUrl();
                    // $action->output['fileUrl'] = $action->getWebUrl();
                    //调用七牛用组件，将图上传到七牛云上面
                    $qiniu=\Yii::$app->qiniu;
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    //获取该图片在七牛云的地址
                    $url=$qiniu->getLink($imgUrl);
                    $action->output['fileUrl'] = $url;*/
      //上传文件成功的同事，将图片和商品列表关联起来，并文件保存到相册的imgs数据库同上的保存到七牛上一样
                  //这是保存到本地路径，而上面是保存到网上的硬盘中
                   //实例化保存相册图片模型对象
                     $model = new Images();
                    //相册的传送方式
                    $model->goods_id=\Yii::$app->request->post('goods_id');
                    $model->path=$action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl']=$model->path;
                    $action->output['id']=$model->id;//回调一个id
                    //$action->output['goods_id']=$model->goods_id;
                    //这是保存到当地文件的upload中
                   //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                  // $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                  // $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
}
