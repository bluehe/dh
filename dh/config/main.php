<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'dh',
    'name' => '网址分享平台',
    'version' => '1.0 Beta',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'controllerNamespace' => 'dh\controllers',
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            "layout" => "left-menu",
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module'
        ],
    ],
    'aliases' => [
        '@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            //'class' => 'yii\redis\Cache',
            'keyPrefix' => 'dh',
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
            'csrfParam' => '_csrf-dh',
        ],
        'user' => [
            'identityClass' => 'dh\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-dh', 'httpOnly' => true],
        ],
        'session' => [
// this is the name of the session cookie used for login on the dh
            'name' => 'advanced-dh',
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
            'site/*',
            'ajax/*',
            'api/*',
            'debug/*'
        ]
    ],
    'on beforeAction' => ['dh\events\initSiteConfig', 'assign'],
    'params' => $params,
];
