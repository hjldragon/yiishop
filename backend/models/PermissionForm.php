<?php
namespace backend\models;
use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{
    public $name;//权限名
    public $description;//描述

    //建立验证规则方法
    public function rules()
    {
       return [
           [['name','description'],'required'],
       ];
    }
    //设置字段显示名
    public function attributeLabels()
    {
        return [
            'name'=>'权限名',
            'description'=>'描述',
        ];
    }
    //自定义添加方法验证功能
    public function addPermission(){
        //实例化rbac的数据表
            $authManager= \Yii::$app->authManager;
            //判断新增的权限名是否存在
            if($authManager->getPermission($this->name)){
                //如果存在就提示错误
                $this->addError('name','权限已经存在');
            }else{
                //如果不存在就创建的权限名
                $permission = $authManager->createPermission($this->name);
                //获取描述名
                $permission->description=$this->description;
                //保存到数据库里数据表
                //var_dump($permission);exit;
                //因为add返回的是bool，所以要加return;
                return $authManager->add($permission);
            }
            return false;
    }
    //自定义回显数据的类
    public function loadData(Permission $permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
//        var_dump($this->name);
//        var_dump($this->description);exit;
    }
    //自动义修改的验证规则//因为要判断修改的权限名所以要在控制器中传送一个权限名的变量过来
    public function editPermission($name){
        //实例化rbac权限的数据表
        $authManager = \Yii::$app->authManager;
        //如果要修改的权限名有的话就提示错误,$name!=$this->name代表了现在要修改的数据
        //$name是回显的数据权限名，$this->name是要修改的权限名
        if($name!=$this->name && $authManager->getPermission($this->name)){
            //在权限哪行提示错
            $this->addError('name','修改的权限名已存');
        }else{
            //获取数据库中的数据
            $permission= \Yii::$app->authManager->getPermission($name);
            //如果不存在
          $permission->name=$this->name;
          $permission->description=$this->description;
          return $authManager->update($name,$permission);
        }
        return false;

    }
}