<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class GoodsAsset extends AssetBundle{

//用来加载js效果的代码，也可以直接显示页面直接用Js加载
//    public $jsOptions = array(
//        'position' => \yii\web\View::POS_HEAD
//    );

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [//css样式
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/home.css',
        'style/address.css',
        'style/bottomnav.css',
        'style/footer.css',
        'style/order.css',
        'style/user.css',
        'style/index.css',
        'style/goods.css',
        'style/common.css',
        'style/list.css',
        'style/jqzoom.css'
    ];

    public $js = [
        'js/jquery-1.8.3.min.js',
        'js/header.js',
        'js/home.js',
        'js/index.js',
        'js/goods.js',
        'js/jqzoom-core.js'

    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
