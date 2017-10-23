<?php

$config = [
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

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [
            'crud' => [//生成器名称
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [//设置我们自己的模板
                    //模板名 => 模板路径
                    'myCrud' => '@dh/../giitemplate/crud/default',
                ]
            ]
        ],
    ];
}

return $config;
