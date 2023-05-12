<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1:3306;dbname=sdkcms',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
        'dbsdk' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1:3306;dbname=sdkchat',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ]
    ],
];
