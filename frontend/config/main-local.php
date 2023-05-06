<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hUCV-OpSAhkPHWdJwe9l39T3gQ2nhOkh',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['::1', '127.0.0.1'],
    ];

    
}

return $config;
