<?php
namespace frontend\controllers;


use backend\models\Article;
use backend\models\Articlecategory;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller{
        public $enableCsrfValidation=false;
    //构造函数返回JSON格式
    public function init()
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;
        parent::init();
    }
    //通过商品品牌接口来获取商品
    public function actionGetBrand(){
        if($brand_id=\Yii::$app->request->get('brand_id')){
                $goods =Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
                return['','msg'=>'',''];

        }
        return ['','msg'=>'没有正确参数',''];
    }
    //会员注册
    public function actionRegister()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $member = new Member();
            $member->username = $request->post('username');
            $member->password_hash =\Yii::$app->security->generatePasswordHash($request->post('pwd'));
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            if ($member->validate()) {
                $member->save();
                return ['status' =>'1', 'msg' =>'','data'=>$member->toArray()];
            }
                return['status'=>'-1','msg'=>$member->getErrors()];
        }
            return['status'=>'-1','msg'=>'请用post传送请求'];

    }
    //会员登录
    public function actionLogin(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $user=Member::findOne(['username'=>$request->post('username')]);
            if($user && \Yii::$app->security->validatePassword($request->post('pwd'),$user->password_hash)){
                        \Yii::$app->user->login($user);
                        return['status'=>'1','msg'=>'登录成功','data'=>$user->username];
            }return['status'=>'-1','msg'=>'账号或密码错误'];

        }return['status'=>'-1','msg'=>'请用post登录'];
    }
    //显示用户是否登录的信息
    public function actionGetMember(){
            if(\Yii::$app->user->isGuest){
                return ['status'=>'-1','msg'=>'请先登录'];
            }
            return['status'=>'1','msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
        }
        //用户修改密码
    public function actionForget()
        {
            $request = \Yii::$app->request;
            if (\Yii::$app->user->isGuest) {
                return ['status' => '-1', 'msg' => '请登录'];
            } else {
                $user = Member::findOne(['id' => \Yii::$app->user->getId()]);
                //var_dump($user);exit;
                if ($request->isPost) {
                    if ($user) {
                        if (\Yii::$app->security->validatePassword($request->post('old_pwd'), $user->password_hash)) {
                            $user->password_hash = \Yii::$app->security->generatePasswordHash($request->post('new_pwd'));
                            $user->save();
                            return ['status' => 1, 'msg' => '', 'data' => true];
                        }
                        return ['status' => '-1', 'msg' => '旧密码不正确'];

                    }
                }
            }
        }
        //收货地址添加
    public function actionAddAddress()
    {
        if (\Yii::$app->user->isGuest) {
            return ['status' => '-1', 'msg' => '请登录'];
        } else {
            //var_dump(\Yii::$app->user->getId());exit;
            $request = \Yii::$app->request;
            if ($request->isPost) {
                $address = new Address();
                $address->member_id = \Yii::$app->user->getId();
                $address->name = $request->post('name');
                $address->provice = $request->post('provice_id');
                $address->city = $request->post('city_id');
                $address->area = $request->post('area_id');
                $address->address = $request->post('address');
                $address->tel = $request->post('tel');
                $address->status = 0;
                if ($address->validate()) {
                    $address->save();
                    return ['status' => '1', 'msg' => '地址添加成功', 'data' => $address->toArray()];
                }
                return ['status' => '-1', 'msg' => $address->getErrors()];

            }
            return ['status' => '-1', '请用post传送方式'];
        }
    }
            //修改收货地址
    public function actionEditAddress(){
            $request =\Yii::$app->request;
            if(\Yii::$app->user->isGuest){
                return ['status'=>'-1','msg'=>'请登录'];
            }else{
                $address = Address::findOne(['id'=>$request->post('id')]);
                if($request->isPost){
                    if($address){
                        $address->name = $request->post('name');
                        $address->provice = $request->post('provice_id');
                        $address->city = $request->post('city_id');
                        $address->area = $request->post('area_id');
                        $address->address = $request->post('address');
                        $address->tel = $request->post('tel');
                        $address->member_id=\Yii::$app->user->getId();
                        if($address->validate()){
                            $address->save();
                            return ['status'=>'1','msg'=>'修改成功','data'=>$address->toArray()];
                        }
                    }return ['status'=>'-1','msg'=>'修改地址不存在'];
                }return ['status'=>'-1','msg'=>'请用post传送方式'];

            }
        }
        //收货地址删除
    public function actionDelAddress(){
            if(\Yii::$app->user->isGuest){
                return ['status'=>'-1','msg'=>'请登录'];
            }else{
                $id=\Yii::$app->request->get('id');
                        $address=Address::findOne(['id'=>$id]);
                        if($address){
                            $address->delete();
                            return['status'=>'1','msg'=>'删除成功'];
                        }return['status'=>'-1','msg'=>'删除地址不存在'];
            }
    }
    //地址列表
    public function actionListAddress(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请登录'];
        }else{
            $member_id=\Yii::$app->user->getId();
            $address=Address::find()->where(['member_id'=>$member_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$address];
        }
    }
    //获取商品分类
    public function actionGoodsCategory(){
        $goodscategory = GoodsCategory::find()->all();
            return ['status'=>1,'msg'=>'','data'=>$goodscategory];
    }
    //-获取商品分类的某分类的所有子分类
    public function actionChildren(){
        $request = \Yii::$app->request;
        if($category=GoodsCategory::findOne(['id'=>$request->get('id')])){
            $children = GoodsCategory::find()->where(['>=','lft',$category->lft])->
            andWhere(['<=','rgt',$category->rgt])->andWhere(['tree'=>$category->tree])->asArray()->all();
            if($children){
                return ['status'=>'1','msg'=>'','data'=>$children];
            }return['status'=>'-1','msg'=>'没有子分类'];


        }return ['status'=>'-1','查询的商品分类不存在'];
    }
    //获取商品分类的某分的所有父级分类
    public function actionGetParent(){
        $request=\Yii::$app->request;
            if($category=GoodsCategory::findOne(['id'=>$request->get('id')])){
                $parent = GoodsCategory::findOne(['id'=>$category->parent_id]);
                if($parent){
                    return['status'=>'1','msg'=>'','data'=>$parent];
                }return['status'=>'-1','该商品分类是顶级分类'];

            }return['status'=>'-1','msg'=>'商品分类不存在'];
    }
    //获取某分类下面的所有商品
    public function actionGetGoods(){
        $request = \Yii::$app->request;
        if($category=GoodsCategory::findOne(['id'=>$request->get('category_id')])){
            //获取category下面的所有子分类
            $categorys=GoodsCategory::find()->where(['>=','lft',$category->lft])->andWhere(
            ['<=','rgt',$category->rgt])->andWhere(['tree'=>$category->tree])->all();
            if($categorys){
                $cateIds=ArrayHelper::map($categorys,'id','id');
                $goods=Goods::find()->where(['good_category_id'=>$cateIds])->asArray()->all();
                return['status'=>'1','msg'=>'','data'=>$goods];
            }return['status'=>'-1','msg'=>'该分类下面没有子分类'];
        }return['status'=>'-1','msg'=>'该分类下面没有商品'];
    }
    //获取某品牌下面的所有商品
    public function actionBrandGoods(){
        $request=\Yii::$app->request;
        if($brand=Brand::findOne(['id'=>$request->get('brand_id')])){
                $goods=Goods::find()->where(['brand_id'=>$brand->id])->all();
                if($goods){
                    return ['status'=>'1','msg'=>'','data'=>$goods];
                }return ['status'=>'-1','msg'=>'该品牌下没有商品'];
        }return ['status'=>'-1','msg'=>'没有该品牌'];
    }
    //获取某文章所属分类
    public function actionGetAc(){
        $request = \Yii::$app->request;
        if($article = Article::findOne(['id'=>$request->get('id')])){
                          $ac=Articlecategory::findOne(['id'=>$article->articlecategory_id]);
                          if($ac){
                              return ['status'=>'1','msg'=>'','data'=>$ac];
                          }return['status'=>'-1','msg'=>'该文章没有分类'];
        }return ['status'=>'-1','msg'=>'没有该文章'];
    }
    //获取文章分类下面所有文章
    public function actionGetArticle(){
        $request =\Yii::$app->request;
        if($ac=Articlecategory::findOne(['id'=>$request->get('id')])){
            $article=Article::find()->where(['articlecategory_id'=>$ac->id])->asArray()->all();
                if($article){
                    return ['status'=>'1','msg'=>'','data'=>$article];
                }return['status'=>'-1','msg'=>'该分类下没有文章'];

        }return['status'=>'-1','msg'=>'没有该文章分类'];
    }
    //获取文章分类
    public function actionGetfenlei(){
        $ac=Articlecategory::find()->asArray()->all();
        return ['status'=>'1','msg'=>'','data'=>$ac];
    }
}