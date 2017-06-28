<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;//密码
    public $password2;
    public $code;//验证码
    public $smsCode;//短信验证码
    const SCENARIO_REGISTER = 'register';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['username','password','code','password2'], 'required'],
            //用户名的验证方法
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\frontend\models\Member', 'message' => '该用户名已被使用！'],
            ['username', 'string', 'min' => 6, 'max' => 16],
            ['username', 'match','pattern'=>'/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})a-zA-Z_\d]*$/u','message'=>'用户名由字母，汉字，数字，下划线组成，且不能以数字和下划线开头。'],
        //邮箱的验证方法
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\frontend\models\Member', 'message' => '该邮箱已经被注册！'],
//2次密码的验证方法
            [['password','password2','smsCode'], 'required','on'=>self::SCENARIO_REGISTER],
            [['password','password2'], 'string', 'min' => 6],
            ['password2', 'compare', 'compareAttribute' => 'password','message'=>'两次输入的密码不一致！'],
//手机验证规则
            [['tel'], 'unique','message'=>'{attribute}已经被占用了'],
            //['tel','match','pattern'=>'/^1[34578]{10}$/','message'=>'{attribute}必须为1开头的11位纯数字'],
            [['tel'], 'string', 'max' => 11],


            [['last_login_time', 'last_login_ip', 'status', 'create_time', 'update_time'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            //短信验证码
            ['smsCode','validateSms','on'=>self::SCENARIO_REGISTER]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名：',
            'auth_key' => 'Auth Key：',
            'password_hash' => '密文：',
            'email' => '邮箱：',
            'tel' => '手机号码：',
            'last_login_time' => '最后登陆时间：',
            'last_login_ip' => '最后登录ip：',
            'status' => '状态：',
            'create_time' => '添加时间：',
            'update_time' => '修改时间：',
            'password'=>'密码：',
            'password2'=>'确定密码：',
            'code'=>'验证码：',
            'smsCode'=>'短信验证码：',
        ];
    }
    //验证短信验证码
    public function validateSms(){
            //缓存里面没有该电话号码
        $value= Yii::$app->cache->get('tel_'.$this->tel);
        if(!$value||$this->smsCode!=$value){
            $this->addError('smsCode','验证码不正确');
        }
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
        // TODO: Implement findIdentity() method.
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
        // TODO: Implement getId() method.
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
            return $this->auth_key=$authKey;
        // TODO: Implement validateAuthKey() method.
    }
}
