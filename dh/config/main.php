<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'db',
    'name' => '导航',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
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
        'db' => [
            'class' => 'yii\db\Connection',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24 * 3600,
            'charset' => 'utf8',
            'tablePrefix' => 'dh_',
            'dsn' => 'mysql:host=localhost;dbname=dh',
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
                'username' => 'admin@gxgygl.com',
                'password' => 'He19881006',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['admin@gxgygl.com' => '管理系统']
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'dms',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', //这里记得用单引号而不是双引号
            'defaultRoles' => ['guest'],
        ],
        'request' => [
            'csrfParam' => '_csrf-db',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-dms', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-db',
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
        'as access' => [
//ACF肯定是要加的，因为粗心导致该配置漏掉了，很是抱歉
            'class' => 'mdm\admin\components\AccessControl',
            'allowActions' => [
//这里是允许访问的action
                'admin/*',
                'site/*',
                'debug/*',
                'gii/*',
            //'*'
            ]
        ],
    ],
    'params' => $params,
];
