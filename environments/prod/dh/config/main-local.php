<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24 * 3600,
            'charset' => 'utf8',
            'tablePrefix' => 'dh_',
            'dsn' => 'mysql:host=106.14.172.65;dbname=wzgxpt',
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
                'from' => ['dh@gxgygl.com' => '网址共享平台']
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => 'fc2b9154f8e7e234b4ea',
                    'clientSecret' => '685f5c532095050092bb207b773a41a78aceb68f',
                ],
                'weibo' => [
                    'class' => 'common\widgets\WeiboClient',
                    'clientId' => '3299253752',
                    'clientSecret' => '36adfd987716cabd291cb20bec7d6e9b',
                ],
                'qq' => [
                    'class' => 'common\widgets\QQClient',
                    'clientId' => '101389884',
                    'clientSecret' => '0f7af7103526adaff8904219831b101f',
                ],
            ],
        ],
        //        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => '106.14.172.65',
//            'port' => 6379,
//            'database' => 0,
//        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'FA2r5FPZiJt6w7soiT3fbvxKYRtr7ztWs',
        ],
    ],
];
