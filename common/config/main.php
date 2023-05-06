<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],    
    'components' => [

        'i18n' => [
            'translations' => [
                'api*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@api/messages',
                ],
                'backend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                ],
                'frontend' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
//            'class' => 'yii\redis\Cache',
//            'redis' => [
//                'hostname' => 'localhost',
//                'port' => 8002,
//                'database' => 1,
//            ]
        ],
        'session' => [
            #'class' => 'yii\redis\Session',
            #'redis' => [
            #    'hostname' => '103.143.206.63',
            #    'port' => 6379,
				//'password'=>'',
            #    'database' => 0,
            #],
//            'cookieParams' => [
//                //    'path' => '/',
//                //     'domain' => "localhost:9501",
//                'expire' => 0as beforeRequest
//            ],
        ],

        //if guest user access site so, redirect to login page.


        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => '.',
            'thousandSeparator' => ',',
        ],
//        'urlManagerFrontend' => [
//            'class' => 'yii\web\urlManager',
//            'baseUrl' => 'http://qltb.tbproduct.com/',
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'rules' => require(__DIR__ . '/../../frontend/config/routes.php'),
//
//        ],
    ],
];
