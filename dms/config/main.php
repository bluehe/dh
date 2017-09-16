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
        'helper' => [
            'class' => 'dms\components\Helper',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24 * 3600,
            'charset' => 'utf8',
            'tablePrefix' => 'dms_',
            'dsn' => 'mysql:host=106.14.172.65;dbname=dms',
            'username' => 'easyscm',
            'password' => 'easyscm1123',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //false：非测试状态，发送真实邮件而非存储为文件
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.exmail.qq.com',
                'username' => 'gy@gxgygl.com',
                'password' => 'YotZ3SPaEtY3De2Q',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['gy@gxgygl.com' => '管理系统']
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            //'class' => 'yii\redis\Cache',
            'keyPrefix' => 'dms',
        ],
//        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => '106.14.172.65',
//            'port' => 6379,
//            'database' => 0,
//        ],
        'wechat' => [
            'class' => 'callmez\wechat\sdk\MpWechat',
            'appId' => 'wx5f06fff8635a37d4', //'wx0b6eaf137bc335ab',
            'appSecret' => '911a61b191219f8b024219f3ec675f39', //'671fbde452f7e879816f217204b7f684',
            'token' => 'weixin',
            'encodingAesKey' => '19Rqpyp6n5vrmgFzAVjv5xpPOtGALsNxI26MGtlQu6C'
        ],
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
                    'clientId' => '3230780338',
                    'clientSecret' => '4c79685858dafe77342476612c5e7190',
                ],
                'qq' => [
                    'class' => 'common\widgets\QQClient',
                    'clientId' => '101404858',
                    'clientSecret' => '39f2a0ea2801309c778e2126c5bc7cad',
                ],
//                'weixin' => [
//                    'class' => 'common\widgets\WeixinClient',
//                    'clientId' => 'wx5f06fff8635a37d4',
//                    'clientSecret' => '911a61b191219f8b024219f3ec675f39',
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
//            'parsers' => [// 因为模块中有使用angular.js  所以该设置是为正常解析angular提交post数据
//                'application/json' => 'yii\web\JsonParser'
//            ]
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
            'decimalSeparator' => '.',
            'thousandSeparator' => ',',
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
            'gridview/*',
            'weixin/*'
//'*'
        ]
    ],
    'on beforeAction' => ['dms\events\initSiteConfig', 'assign'],
    'params' => $params,
];
