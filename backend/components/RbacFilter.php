<?php
namespace backend\components;

use yii\base\ActionFilter;
use yii\web\HttpException;


class RbacFilter extends ActionFilter{

    //定义过滤器
    public function beforeAction($action)
    {
        //找到登录的用户
        $user = \Yii::$app->user;
        //判断用户是否登录了
        if(!$user->can($action->uniqueId)){
            //如果用户没有登录就引导用户进行登录
            if($user->isGuest){
                return $action->controller->redirect($user->loginUrl);
            }
            //如果没有权限就提示消息
            throw new HttpException(403,'你没有该访问权限哦！');
            return false;
        }
        return parent::beforeAction($action);
    }
}