<?php
namespace backend\models;
//设置一个登录界面的模型
class LoginForm extends \yii\base\Model {
    //设置登录的字段
    public $code;
    public $username;
    public $password_hash;
    public $status;
    public $remember;

    //设置登录的验证规则
    public function rules(){
        return [
          [['username','password_hash'],'required'],
            ['code','captcha','captchaAction'=>'user/captcha'],
                //添加一个自定义验证密码账号的方法
            ['remember','boolean'],
        ];
    }
    //设置字段名
    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'code'=>'验证码',
             'remember'=>'记住我'
        ];
    }
    //实现上面验证中的validateUsername方法
    public  function validateUsername(){
        //通过账号来寻找user数据库里的的账号是否相同
        $user=\backend\models\User::findOne(['username'=>$this->username]);

        //如果账号存在就对密码进行验证
        if($user){
            //验证输入密码和数据库加密的密码是否一样
                   if( \Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
                        //密码验证正确就改状态并保存
                       $user->status=1;
                        $user->save(false);
                        //var_dump($user);
                      // var_dump($user->status,$user->getErrors());exit;
                        //实现login的方法
                       //判断是否勾选了记住我
                           $bc=$this->remember?7*24*3600:0;
                       //自动登录
                       \Yii::$app->user->login($user,$bc);
                       return true;
                   }else{
                $this->addError('username','账号或者密码不正确');
            }
        }else{
            $this->addError('username','账号或密码错误');
        }
        return false;
    }
}