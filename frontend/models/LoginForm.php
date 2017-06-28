<?php
namespace frontend\models;

use yii\base\Model;
use yii\web\Cookie;

class LoginForm extends Model{
    public $username;
    public $password_hash;
    public $code;
    public $status;//用户登录状态
    public $remember;//记住我

    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['remember','boolean'],

        ];
    }

    public function attributeLabels()
    {
        return [
          'username'=>'用户名：',
            'password_hash'=>'密码：',
            'remember'=>'记住我：',
            'code'=>'验证码：',
        ];
    }
    //设置一个验证数据库规则的方法
    public function validateMember(){
        $user =Member::findOne(['username'=>$this->username]);
        //如果先符合就进行密码验证
        if($user){
            if(\Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
                    $remember =$this->remember ? 7*24*3600:0;
                    //var_dump($user);exit;
                    //保存到cookie中
                    \Yii::$app->user->login($user,$remember);
                //var_dump(\Yii::$app->user->identity);exit;
                return true;
            }else{
                $this->addError('password_hash','密码不正确');
            }
        }else{
            $this->addError('username','账号不正确');
        }
        return false;
    }
    //设置用户登录后自动读取cookie中购物车的数据
    public function addCart(){
        //获取购物车cart，cookie中的数据
        $cookies= \Yii::$app->request->cookies;
        $cookie=$cookies->get('cart');
        //判断cookie中时候存在数据
        if($cookie==null){
            //如果没有数据就保存一个空数组
            $cart =[];
        }else{
            //如果有数据就序列化cookie中的数据
            $cart=unserialize($cookie);
            //遍历出cookie中的商品数据
            foreach ($cart as $goods_id=>$amount){
                //获取登录用户的id
                $member_id=\Yii::$app->user->identity->getId();
                //实例化购物车模型
                $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
                //如果有该商品就将商品数量保存到数据库里
                if($cart){
                    $cart->amount=$amount;
                    $cart->save();
                }else{
                    //新增商品就重新添加进来
                    $cart=new Cart();
                    $cart->amount=$amount;
                    $cart->goods_id=$goods_id;
                    $cart->member_id=$member_id;
                    if($cart->validate()){
                        $cart->save();
                    }
                }

            }
            //重新获取cookie中数据//清空cookie中的数据
            $cookie=new Cookie([
               'name'=>'cart','value'=>''
            ]);
            $cookies=\Yii::$app->response->cookies;
            $cookies->remove($cookie);

        }

    }
}