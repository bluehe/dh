<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
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
    ],
];
