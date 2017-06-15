<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'loginUrl'=>['user/login'],//如果用户没登录就自动跳转到这里
            //设置实现认证接口的类
            'identityClass' => backend\models\User::className(),
            'enableAutoLogin' => true,//基于cookie的自动登录这里，需要打开
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [//地址管理
            'enablePrettyUrl' => true,//开启html伪静态地址
            'showScriptName' => false,//显示脚本文件
            //'suffix'=>'.html',//伪静态后缀
            'rules' => [
            ],
        ],
        'qiniu'=>[
            'class'=>\backend\components\Qiniu::className(),
            'up_host'=>'http://up.qiniu.com',
            //密钥的ak
            'accessKey'=>'aYL8nG9EvFs5d8uuIrS0NJ7vg4n_gvV5ajyF3KnQ',
            //密钥的sk
            'secretKey'=>'ZW5BIxwKwsMKoB-fUd1ecuBn8XhL-GCMfsXFQbMn',
            //7牛上面的保存名
            'bucket'=>'hjlwork',
            //保存名里的域名
            'domain'=>'http://or9szvrjj.bkt.clouddn.com/',
        ]

    ],
    'params' => $params,
];
