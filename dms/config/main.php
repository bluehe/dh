<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'dms',
    'name' => '管理系统',
    'version' => '3.0 Beta',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'controllerNamespace' => 'dms\controllers',
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            "layout" => "left-menu",
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module'
        ]
    ],
    'aliases' => [
        '@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
    ],
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //false：非测试状态，发送真实邮件而非存储为文件
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.ym.163.com',
                'username' => 'admin@ds9b.com',
                'password' => 'he19881006',
                'port' => '25',
                'encryption' => 'tls',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['admin@ds9b.com' => '管理系统']
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'dms',
        ],
//        'wechat' => [
//            'class' => 'callmez\wechat\sdk\MpWechat',
//            'appId' => 'wx5f06fff8635a37d4',
//            'appSecret' => '911a61b191219f8b024219f3ec675f39',
//            'token' => 'weixin'
//        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => 'ee994356d9e2453508bc',
                    'clientSecret' => '62038bff76991c3f21c37ba110c5e7f69b11d40e',
                ],
                'weibo' => [
                    'class' => 'common\widgets\WeiboClient',
                    'clientId' => '69078040',
                    'clientSecret' => '2c021b94f29e78fd8f7055958b1c329d',
                ],
                'qq' => [
                    'class' => 'common\widgets\QQClient',
                    'clientId' => '101389884',
                    'clientSecret' => '0f7af7103526adaff8904219831b101f',
                ],
//                'weixin' => [
//                    'class' => 'yujiandong\authclient\Weixin',
//                    'clientId' => 'weixin_appid',
//                    'clientSecret' => 'weixin_appkey',
//                ],
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
//            'linkAssets' => true,
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-blue',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', //这里记得用单引号而不是双引号
            'defaultRoles' => ['guest'],
        ],
        'request' => [
            'csrfParam' => '_csrf-dms',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-dms', 'httpOnly' => true],
        ],
        'session' => [
// this is the name of the session cookie used for login on the dms
            'name' => 'advanced-dms',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
    ],
    'as access' => [
//ACF肯定是要加的，因为粗心导致该配置漏掉了，很是抱歉
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
//这里是允许访问的action
            'admin/*',
            'site/*',
            'wechat/*',
            'debug/*',
            'gii/*',
        //'*'
        ]
    ],
    'on beforeAction' => ['dms\events\initSiteConfig', 'assign'],
    'params' => $params,
];
