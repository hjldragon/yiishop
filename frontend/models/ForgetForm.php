<?php
namespace frontend\models;

use yii\base\Model;

class ForgetForm extends Model{

    //设置字段名来验证
    public $username;
    public $email;
    public $tel;
    public $newpassword;
    public $newpassword2;
    public $code;

    //设置验证规则
    public function  rules()
    {
        return [
            [['email','username','tel'],'required'],
            //设置密码的验证规则
            //2次密码的验证方法
            [['newpassword','newpassword2'], 'required'],
            [['newpassword','newpassword2'], 'string', 'min' => 6],
            ['newpassword2', 'compare', 'compareAttribute' => 'newpassword','message'=>'两次输入的密码不一致！'],
        ];
    }
    public function attributeLabels()
    {
        return [
          'email'=>'邮箱：',
            'username'=>'用户名：',
            'tel'=>'手机号码：',
            'newpassword'=>'新密码：',
            'newpassword2'=>'再次输入密码：',
            'code'=>'验证码：',
        ];
    }
    //设置忘记密码修改方法
    public function validatePs(){
        $user =Member::findOne(['username'=>$this->username]);
        if($user){
        if($this->email!=$user->email && $this->tel!=$user->tel){
            $this->addError('email','邮箱不正确');
            $this->addError('tel','手机号不正确');
        }else{
            $user->password_hash=\Yii::$app->security->generatePasswordHash($this->newpassword);
//            $user->password_hash=$this->newpassword;
            $user->save(false);
        }
        }else{
            $this->addError('username','用户名不存在');
        }

    }
}