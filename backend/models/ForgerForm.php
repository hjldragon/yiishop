<?php
namespace backend\models;

use yii\base\Model;

class ForgerForm extends Model{
    public $email;
    public $oldpassword;
    public $newpassword;
    public $username;
    public $code;

    //设置验证方式
    public function rules()
    {
        return [
          [['email','username','oldpassword','newpassword'],'required'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            ['email','validateEmail']
        ];
    }
    //设置字段名
    public function attributeLabels()
    {
        return [
            'email'=>'邮箱地址',
            'oldpassword'=>'旧密码',
            'newpassword'=>'新密码',
            'username'=>'用户名',
        ];
    }
    //设置修改密码的自定义方法
    public function validateEmail(){
        $user = User::findOne(['email'=>$this->email]);
        if($user){
            if($this->oldpassword!=$user->password_hash && $this->username!=$this->username){
                $this->addError('username','邮箱用户名不正确');
            }else{
                $user->password_hash=$this->newpassword;
                $user->save(false);
            }
        }else{
            $user->addError('username','邮箱或用户名不正确');
        }

    }
}