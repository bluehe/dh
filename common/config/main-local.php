<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24 * 3600,
            'charset' => 'utf8',
            'tablePrefix' => 'dms_',
            'dsn' => 'mysql:host=localhost;dbname=dms',
            'username' => 'easyscm',
            'password' => 'easyscm1123',
//            // 主库通用的配置
//            'masterConfig' => [
//                'username' => 'root',
//                'password' => '',
//                'attributes' => [
//                    // use a smaller connection timeout
//                    PDO::ATTR_TIMEOUT => 10,
//                ],
//            ],
//            // 主库配置列表
//            'masters' => [
//                ['dsn' => 'mysql:host=localhost;dbname=dms'],
//                ['dsn' => 'mysql:host=localhost;dbname=dms1'],
//            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
// 'useFileTransport' to false and configure a transport
// for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
