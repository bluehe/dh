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
    ],
];
