<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24 * 3600,
            'charset' => 'utf8',
            'tablePrefix' => 'nn_',
            'dsn' => 'mysql:host=106.14.172.65;dbname=nn',
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
                'username' => 'nn@gxgygl.com',
                'password' => 'YotZ3SPaEtY3De2Q',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['nn@gxgygl.com' => '管理系统']
            ],
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
//                'weibo' => [
//                    'class' => 'common\widgets\WeiboClient',
//                    'clientId' => '3230780338',
//                    'clientSecret' => '4c79685858dafe77342476612c5e7190',
//                ],
//                'qq' => [
//                    'class' => 'common\widgets\QQClient',
//                    'clientId' => '101404858',
//                    'clientSecret' => '39f2a0ea2801309c778e2126c5bc7cad',
//                ],
//                'weixin' => [
//                    'class' => 'common\widgets\WeixinClient',
//                    'clientId' => 'wx5f06fff8635a37d4',
//                    'clientSecret' => '911a61b191219f8b024219f3ec675f39',
//                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];
