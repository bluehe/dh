<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-dh',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'dh\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24 * 3600,
            'charset' => 'utf8',
            'tablePrefix' => 'dh_',
            'dsn' => 'mysql:host=106.14.172.65;dbname=dh',
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
//            'class' => 'yii\redis\Cache',
//            'keyPrefix' => 'dms',
        ],
//        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => '106.14.172.65',
//            'port' => 6379,
//            'database' => 0,
//        ],
        'request' => [
            'csrfParam' => '_csrf-dh',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
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
    ],
    'params' => $params,
];
