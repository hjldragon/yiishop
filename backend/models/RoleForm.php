<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{
    //设置角色的字段名
    public $name;
    public $description;
    //角色可拥有的权限字段
    public $permissions=[];//因为存在多个字段所有用数组

    //设置验证规则功能
    public function rules()
    {
        return [
          [['name','description'],'required'],
            ['permissions','safe'],
        ];
    }

    //设置字段名
    public function attributeLabels()
    {
        return [
          'name'=>'角色名',
            'description'=>'角色描述',
            'permissions'=>'所拥有的权限选择 ',
        ];
    }
    //设置静态获取权限所有权限数据
   static public function getPermissionOptions(){
        $permission=\Yii::$app->authManager->getPermissions();
        //返回所有获取的权限数据
        return ArrayHelper::map($permission,'name','description');
    }
    //设置自动义添加规则
    public function addRole(){
       //获取实例化的数据表
        $authManager =\Yii::$app->authManager;
        //判断添加的名字是否有重复
        if($authManager->getRole($this->name)){
            //如果存在就提示错误信息
            $this->addError('name','添加的角色名有重复');
        }else{
            //如果不存在就创建字角色名
            $role=$authManager->createRole($this->name);
            //并获取描述内容
            $role->description=$this->description;
            //判断如果保存到数据到数据库
            //关联该角色的权限
           if($authManager->add($role)){
               //获取权限数据
               foreach ($this->permissions as $permissionName){
                   $permission=$authManager->getPermission($permissionName);
                   if($permission){
                       $authManager->addChild($role,$permission);
                   }

               }
               //保存数据返回的bool
               return true;
           }
        }
        return false;
    }
    //自动义回显数据类
    public function loadData(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        //权限属性赋值
        //获取该角色对应的权限回显
        $permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
        //回显数据，因为是多个数据所以要遍历
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }
    //自定义修改类
    public function updateRole($name){
        //实例化数据库
        $authManager=\Yii::$app->authManager;
        //获取角色数据信息
        $role=$authManager->getRole($name);
        //获取修改内容给角色赋值
        $role->name=$this->name;
        $role->description=$this->description;
        //如果要修改的角色名
        //需要判断要修改的角色名是否和回显明相同，不相同说明要修改，相同说明不修改
        //并判断是否跟数控的角色名重合//如果满足以上2个条件就提示错误信息
        if($name!=$this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名有重复,请重新修改');
        }else{
            //如果更新了数据就要去掉所有该角色关联的权限
            //这里update已经更新了数据
                if($authManager->update($name,$role)){
                    //去掉该角色的权限
                    $authManager->removeChildren($role);
                    //去了了权限后要重新获取更新的角色权限
                    //所以这里还要关联角色权限
                    foreach ($this->permissions as $permissionName) {
                        $permission =$authManager->getPermission($permissionName);
                        if($permission){
                            $authManager->addChild($role,$permission);
                        }

                    }
                    return true;
                }

        }
        return false;
    }
}