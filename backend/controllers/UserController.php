<?php

namespace backend\controllers;

use backend\models\ForgerForm;
use backend\models\LoginForm;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use backend\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;


class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
        //设置添加用户信息
    public function actionAdd(){
        //实例化模型对象
        //实例化权限模型
//        $role=User::getRoles();
//     var_dump($role);exit;
        //var_dump($permission);exit;
        $model = new User();
        //var_dump($model);exit;
        //查看传送方式
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //var_dump($model);exit;
                //将状态设置为10，也可以不设置，数据中空默认为10，在模型里设置了sexoption的
               // $model->status=12;
            //var_dump($model->roles);exit;
                $model->save(false);
                $id=$model->id;
                if($model->addRoles($id)){
                    //提示添加成功功能
                    \Yii::$app->session->setFlash('success','注册成功');
                    return $this->redirect(['user/list']);
                }
            }else{
                //var_dump($model->getErrors());exit;
        }
        //视图显示
        return $this->render('add',['model'=>$model]);
    }
    public function actionList(){
        //实例化模型对象
        $all=User::find();
        //获取总条数
       $total= $all->count();
       //设置分页的总条数和每页显示数
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>3,
        ]);
        //显示出分页
        $models=$all->offset($page->offset)->limit($page->limit)->all();
        return $this->render('list',['models'=>$models,'page'=>$page]);
    }
    //设置删除用户信息的方法
    public function actionDel($id){
        $model = User::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['user/list']);
    }
    public function actionEdit($id){
        //移除所有角色，重新进行选择
        $model = User::findOne(['id'=>$id]);
        //var_dump($model);exit;
        //实例化rbac的数控库模型
        $authManager=\Yii::$app->authManager;
        //获取要修改角色的数据
        $roles=$authManager->getRolesByUser($id);
        //var_dump($role);exit;
       // \Yii::$app->authManager->removeAllRoles();
        if($model==null){
            throw new NotFoundHttpException('账号不存在');
        }
            $model->loadData($roles);
        //var_dump($model);exit;
        if($model->load(\Yii::$app->request->post())){
//            var_dump($model);exit;
            if($model->validate()){
                //加密加盐
                $model->save();
                $model->id=$id;

                if($model->EditRoles($id))
                    \Yii::$app->session->setFlash('warning','修改成功');
                    return $this->redirect(['user/list']);

            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //设置登录界面的验证码
    public function actions(){
        return [
            'captcha'=> [
                'class'=>'yii\captcha\CaptchaAction',
                'fixedVerifyCode'=>YII_ENV_TEST ?'testme':null,
                'minLength'=>4,
                'maxLength'=>4,
                'foreColor'=>'#00FF00',]
        ];
    }
    //设置登录方法
    public function actionLogin(){
        //实例化验证模型对象
        $model = new LoginForm();
        //var_dump($model);exit;
        //验证传送方式

            if($model->load(\Yii::$app->request->post())){
                if($model->validate()){
                    if($model->validateUsername()){
                        //var_dump($model->validateUsername());exit;
//                        $status=new User();
//                        $status->status=1;
//                        $status->save();
                        \Yii::$app->session->setFlash('success','恭喜登录成功');
                        return $this->redirect(['user/list']);
                    }
                }else{
                    var_dump($model->getErrors());exit;
                }
            }
        return $this->render('login',['model'=>$model]);
    }
    //设置注销状态
    public function actionLogout(){
//
//        $model1=User::find()->all();
//        //var_dump($model1);exit;
//       if($model1!=1){
            //通过用户的在线状态来找到用户信息
        $model1=\Yii::$app->user->identity->username;
        //var_dump($model);exit;
            $model =User::findOne(['username'=>$model1]);
            //var_dump($model);exit;
            //var_dump($model);exit;
            $model->status=2;
            $model->last_time=date('Y/m/d G:i:s');
            $model->last_ip=\Yii::$app->request->userIP;
            $model->save(false);
            //退出登录
            \Yii::$app->user->logout();
            \Yii::$app->session->setFlash('success','用户'.$model->username.'注销成功');
           return $this->redirect(['user/login']);
//       }else{
//
//            \Yii::$app->session->setFlash('danger','你没有登录请登录');
//            return $this->redirect(['user/login']);
//        }

    }

//    //定义过滤器，查看用户是否可以操作
    public function behaviors(){
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                'only'=>['add','del','index','edit','list'],//这里是指该过滤器的操作，默认是所有操作
                'rules'=>[
                    [
                        //已认证用户可以执行add操作
                        'allow'=>true,//让用户是否容许执行
                        'actions'=>['add','del','edit','list'],//允许执行的操作
                        'roles'=>['@'],//角色？表示未认证用户 @表示已认证用户
                    ],
                    [
                        //未认证用户可以执行index操作
                        'allow'=>true,//让用户是否容许执行
                        'actions'=>['add'],//允许执行的操作
                        'roles'=>['?'],//角色？表示未认证用户 @表示已认证用户
                    ],
                    //其他未定义的都是禁止执行
                ],
            ],
        ];
    }
    //忘记密码的的并修改密码的方法
    public function actionForget(){
        //实例化模型对象
        $model= new ForgerForm();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['user/list']);
            }
        }

        //显示视图
        return $this->render('forget',['model'=>$model]);
    }
}
