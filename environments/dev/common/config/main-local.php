<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.1.88:3306;dbname=sdkcms',
            'username' => 'admin',
            'password' => 'Ringme@2022',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
        'dbsdk' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.1.88:3306;dbname=ejabberd',
            'username' => 'admin',
            'password' => 'Ringme@2022',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ]
    ],
];
