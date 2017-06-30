<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\ForgetForm;
use frontend\models\Locations;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\Json;
use yii\web\Controller;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class UserController extends Controller{
    //定义布局文件
    public $layout = 'login';
    //用户注册
    public function actionRegister(){
        $model =new Member();
       // var_dump($model);exit;
        //验证数据是否符合要求是否符合验证规则
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->status=1;
            $model->create_time=time();
            //var_dump($model->password);exit;
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
           // $model->password_hash=
            $model->save();
            \Yii::$app->session->setFlash('success','注册成功');
            return $this->redirect(['user/login']);
        }
        return $this->render('register',['model'=>$model]);
    }
    //用户登录
    public function actionLogin(){
        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->validateMember()){
                $model->addCart();
                return $this->goBack();//直接返回到上次进入页面
                //return $this->redirect(['goodscategory/index']);
            }
        }
        \Yii::$app->user->setReturnUrl(\Yii::$app->request->referrer);//设    置登录前页面
            return $this->render('login',['model'=>$model]);
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
    //设置用户的收货地址和显示收货地址
    public function actionAddress(){
        $this->layout='goods';
        $model = new Address();
        $id=\Yii::$app->user->identity->getId();
        $model->member_id=$id;
        //var_dump($model->member_id);exit;
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                 //var_dump($model->provice);exit;
                    $model->save();
                  return  $this->redirect(['user/address']);
            }else{
               var_dump($model->getErrors());exit;
            }
        }
        $models =Address::find()->where(['member_id'=>$model->member_id])->orderBy('status desc')->all();
//        var_dump($models);exit;
        return $this->render('address',['model'=>$model,'models'=>$models]);
    }
    //设置获取三级联动的方法
    public function actionLocations($parent_id,$level){
        $name=Locations::find()->where(['parent_id'=>$parent_id])->andWhere(['level'=>$level])->all();
        return Json::encode($name);
    }
    //设置用户的地址的删除
    public function actionAdddel($id){
        $model = Address::findOne(['id'=>$id]);
        //var_dump($model);exit;
        $model->delete();
        return $this->redirect(['user/address']);
    }
    //设置用修改
    public function actionAddedit($id){
        $this->layout='index';
        $model=Address::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            return $this->redirect(['user/address']);
        }

        $models =Address::find()->where(['member_id'=>$model->member_id])->orderBy('status desc')->all();
//        var_dump($models);exit;
        return $this->render('address',['model'=>$model,'models'=>$models]);
    }
    //设置默认地址
    public function actionStatus($id){

        $model=Address::findOne(['id'=>$id]);
        $status= Address::findOne(['status'=>1,'member_id'=>\Yii::$app->user->id]);
        if($status){
            $status->status=0;
            $status->save();
        }
        $model->status=1;
        $model->save();
        return $this->redirect(['user/address']);
    }
    //设置用户忘记密码
    public function actionForget(){
        //设置字段名来验证
        $model = new ForgetForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->validatePs()){
                return $this->redirect(['user/login']);
            }
        }
        return $this->render('forget',['model'=>$model]);
    }
//测试手机验证短信
public function  actionSms(){
//
//// 配置信息
//$config = [
//    'app_key'    => '24493589',
//    'app_secret' => '71f080699a57dab32d3d2a037b13c2ba',
//    // 'sandbox'    => true,  // 是否为沙箱环境，默认false
//];
//
//// 使用方法一
//$client = new Client(new App($config));
//$req    = new AlibabaAliqinFcSmsNumSend;
//
//$code =rand(1000,9999);
//$req->setRecNum('18780200651')//设置发给谁
//    ->setSmsParam([
//        'code' =>$code//在网上设置的code
//    ])
//    ->setSmsFreeSignName('俊龙网站')//设置短信签名，必须是已审核的签名
//    ->setSmsTemplateCode('SMS_71605149');//设置短信模板也必须是审核通过的
//
//$resp = $client->execute($req);
//var_dump($resp);

}
//设置短信的发送方式
public function actionSendSms(){

    $tel =\Yii::$app->request->post('tel');
    if(!preg_match('/^1[34578]\d{9}$/',$tel)){
        echo '电话号码不正确';
        exit;
    }
    $code =rand(1000,9999);
    $result=1;
   //$result=\Yii::$app->sms->setNum($tel)->setParam(['code'=>$code])->send();

    if($result){
        //保存当前验证码
        //保存当前验证码 session  mysql  redis  不能保存到cookie
//            \Yii::$app->session->set('code',$code);
//            \Yii::$app->session->set('tel_'.$tel,$code);
        //用哪个的缓存,最后一个是过期时间
        \Yii::$app->cache->set('tel_'.$tel,$code,2*60);

        echo 'success';
    }else{
        echo '发送失败';
    }
}
        public function actionDitu(){
                        return $this->render('ditu');
        }
}