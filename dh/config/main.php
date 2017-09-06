<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-dh',
    'name' => '网址收藏夹',
    'version' => '3.0 Beta',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'controllerNamespace' => 'dh\controllers',
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
                'username' => 'dh@gxgygl.com',
                'password' => 'Dh19881006',
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
            //'class' => 'yii\redis\Cache',
            'keyPrefix' => 'dh',
        ],
//        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => '106.14.172.65',
//            'port' => 6379,
//            'database' => 0,
//        ],
        'assetManager' => [
            'appendTimestamp' => true,
//            'linkAssets' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', //这里记得用单引号而不是双引号
            'defaultRoles' => ['guest'],
        ],
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
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => '441b767ffa92ee879471',
                    'clientSecret' => 'b16da9ef4cf8d97a12e035dd4dff413c6198ad0d',
                ],
                'weibo' => [
                    'class' => 'common\widgets\WeiboClient',
                    'clientId' => '1982328261',
                    'clientSecret' => 'd9cb383e6c9dc564800a8009a96f9720',
                ],
                'qq' => [
                    'class' => 'common\widgets\QQClient',
                    'clientId' => '101389884',
                    'clientSecret' => '0f7af7103526adaff8904219831b101f',
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
    'on beforeAction' => ['dh\events\initSiteConfig', 'assign'],
    'params' => $params,
];
