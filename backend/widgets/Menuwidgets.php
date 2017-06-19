<?php
namespace backend\widgets;

use backend\models\Menu;
use yii\base\Widget;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use Yii;
use yii\bootstrap\Html;

class Menuwidgets extends Widget{

    public function init()
    {
        parent::init();
    }
    public function run()
    {

    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $menuItems = [
        ['label' => '首页', 'url' => ['site/index']],
//        ['label' => '品牌列表', 'url' => ['/brand/list']],
    // ['label' => '文章分类列表', 'url' => ['/articlecategory/list']],
//        ['label' => '文章列表', 'url' => ['/article/list']],
   //     ['label' => '商品分类列表', 'url' => ['/goods-category/list']],
//        ['label' => '商品列表', 'url' => ['/goods/list']],
//       ['label' => '权限列表', 'url' => ['/rbac/index-permission']],
//        ['label' => '角色列表', 'url' => ['/rbac/index-role']],
//       ['label' => '用户列表', 'url' => ['/user/list']],

    ];
    if (Yii::$app->user->isGuest) {
        //如果没有登录就提示登录
        $menuItems[] = ['label' => '登录', 'url' => ['user/login']];
    } else {
        //如果有登录就先来个注销功能
        $menuItems[] = ['label'=>'用户' . Yii::$app->user->identity->username . '注销','url'=>['user/logout']];
        //根据用户的权限显示菜单
        /*$menuItems[] = ['label'=>'用户管理','items'=>[
            ['label'=>'添加用户','url'=>['admin/add']],
            ['label'=>'用户列表','url'=>['admin/index']]
        ]];*/
       //在头部上获取所有顶级菜单的数据
        $menus = Menu::findAll(['parent_id'=>0]);
       // var_dump($menus);exit;
        //因为顶级菜单是多个所以此次要遍历
        foreach ($menus as $menu){
            $item=['label'=>$menu->label,'items'=>[]];
            //如果用户有权限就显示顶级菜单下面的子类菜单
            //var_dump($menu->children);exit;//通过在menu模型里的getchildren来获取子类菜单，并遍历出来
            foreach ($menu->children as $child){
                //判断该用户是否有权限显示这些菜单操作功能
                //这里是判断的权限名称，$child->url的名字和权限的名字一样
                if(\Yii::$app->user->can($child->url)){
                    $item['items'][]=['label'=>$child->label,'url'=>[$child->url]];
                }
            }
            //如果该菜单没有子菜单就不显示出来
                if(!empty($item['items'])){
                        $menuItems[]=$item;
                }
        }
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    }
}