<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $last_time
 * @property string $last_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;//保存密码的明文
    public $roles=[];
//    public $name;//设置角色的字段名
    static public $sexOption=[1=>'在线',2=>'离线',3=>'正常'];
    //设置角色的静态属性获取所有角色数据
    static public function getRoles(){
        $roles =\Yii::$app->authManager->getRoles();
        return ArrayHelper::map($roles,'name','description');
}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at', 'last_time', 'last_ip'], 'required'],
            [['username', 'email'], 'required'],
            ['password','string','skipOnEmpty'=>false,'length'=>[4,10]],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'last_time', 'last_ip'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],//唯一
            [['email'], 'email'],//邮箱格式
            [['password_reset_token'], 'unique'],
           ['roles','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password' => '密码',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_time' => 'Last Time',
            'last_ip' => 'Last Ip',
            'roles'=>'角色',
        ];
    }
    public  function beforeSave($insert)
    {
            if($insert){
                $this->created_at=time();
                $this->status=3;
                //生成随机字符串
                $this->auth_key=Yii::$app->security->generateRandomString();
            }
        if($this->password){
            //自动保存数据库密文
            $this->password_hash=Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
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
    //设置添加角色的类
    public function addRoles($id){
        //实例化权限数据表
        //根据传送过来的用户id给他添加角色
       $authManger =  \Yii::$app->authManager;
       //$roles = \Yii::$app->authManager->getRole($this->role);
        //遍历所有选择的角色
            foreach ($this->roles as $roleName){
                $role = $authManger->getRole($roleName);
                //分配给角色用户，保存到角色用户的表中了
                $authManger->assign($role,$id);
            }
            return true;
    }
    //设置添加角色类的修改
    public function EditRoles($id){
        //实例化权限表
        $authManger=\Yii::$app->authManager;
        //获取原有的所有角色数据
        //$roles=$authManger->getRolesByUser($id);
        //移除所有角色名
        $authManger->revokeAll($id);
        //遍历所有修改的角色
        //$roles =$this->role;
//        if($authManger->update($roles,$id)){
//            $authManger->removeChildren($id);
            foreach ($this->roles as $roleName){
                $role = $authManger->getRole($roleName);
                $authManger->assign($role,$id);
            }
            return true;
    }
    //自动义回显角色数据类
    public function loadData($roles){
        //回显数据，因为是多个数据所以要遍历
        foreach ($roles as $role){
            $this->roles[]=$role->name;
        }
    }
}
