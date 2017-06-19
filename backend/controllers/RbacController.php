<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    //建立权限的增删查改
    //权限添加
    public function actionAddPermission(){
        //实例化模型数据
        $model = new PermissionForm();
        //验证传送过来的方式是否符合要求
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //在验证在里面设置的添加方法时候符合要求
            if($model->addPermission()){

                //如果符合提示消息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转页面
                return $this->redirect(['rbac/index-permission']);
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //权限显示
    public function actionIndexPermission(){
        //获取模型数据
        $models  = \Yii::$app->authManager->getPermissions();
        //var_dump($models);exit;
        //var_dump($models);exit;
        //分配视图
        return $this->render('index-permission',['models'=>$models]);
    }
    //权限修改
    public function actionEditPermission($name){
        //获取要修改的数据表中的数据
        $permission =\Yii::$app->authManager->getPermission($name);
        if($permission==null){
            throw  new NotFoundHttpException('修改的权限名不存在');
        }
        //var_dump($permission);exit;
        //实例化模型字段
        $model  = new PermissionForm();

        //调用上面$permission来回显数据，所有要在模型中自定义一个类来加载回显的数据
        $model->loadData($permission);
        //var_dump($model);exit;
        //验证传送发送和模型的验证规则
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //如果符合修改的验证规则//要将修改的权限名传送到自定义修改验证类里
            if($model->editPermission($name)){
               // var_dump($model);exit;
                //就提示修改成并跳转页面
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['index-permission']);
            }
        }
        //视图显示
        return $this->render('add-permission',['model'=>$model]);
    }
    //权限删除
    public function actionDelPermission($name){
        //实例化数据库
        $authManager=\Yii::$app->authManager;
        //获取要修改的数据库数据
        $permission = \Yii::$app->authManager->getPermission($name);
        //做个bug验证，如果要修改的数据不存在就报出
        if($permission==null){
            throw new NotFoundHttpException($permission);
        }
        //var_dump($permission);exit;
        //移除或去的数据数据
        $authManager->remove($permission);
        //提示消息
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['index-permission']);
    }
    //创建角色的增删查改
    //角色的添加
    public function actionAddRole(){
        //实例化模型对象
        $model = new RoleForm();
        //加载传送规则和验证
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //设置自定义验证规则
            if($model->addRole()){
                //添加成功的提示消息
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index-role']);
            }
        }
        //显示视图
        return $this->render('add-role',['model'=>$model]);
    }
    //角色显示列表
    public function actionIndexRole(){
        //获取数据库所有角色数据
        $models = \Yii::$app->authManager->getRoles();
        //显示角色视图
        return $this->render('index-role',['models'=>$models]);
    }
    //角色的修改
    public function actionEditRole($name){
        //实例化rbac的数控库模型
        $authManager=\Yii::$app->authManager;
        //获取要修改的数据
        $role=$authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('你要删除的数据不存在');
        }
        //实例化表单模型
        $model = new RoleForm();
        //var_dump($role);exit;
        //需要回显要修改的数据
        $model->loadData($role);
        //加载修改和验证修改的判断
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            //自定义修改规则
            if($model->updateRole($name)){
                //如果符合就提示信息跳转页面
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['index-role']);
            }
        }
        //显示页面
        return $this->render('add-role',['model'=>$model]);
    }
    //角色的删除
    public function actionDelRole($name){
        //获取要数据库数据
        $authManager =\Yii::$app->authManager;
        //获取要删除数据的数据
            $role=$authManager->getRole($name);
        //var_dump($model);exit;
        if($role==null){
            throw new NotFoundHttpException('删除的数据不存在');
        }
        //删除所要删除的数据
        $authManager->remove($role);
        //提示信息回显页面
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['index-role']);

    }
}
