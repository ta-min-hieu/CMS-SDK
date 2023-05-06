<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => [
        'log',
//        'queue'
    ],
    'language' => 'en',
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ],
    ],

    'on beforeAction' => function ($event) {

        if(isset($_GET['lang']) && in_array($_GET['lang'], array_keys(Yii::$app->params['content_languages'])))
        {
            Yii::$app->language = $_GET['lang'];
            Yii::$app->session->set('language', $_GET['lang']);
        }
        else
        {
            Yii::$app->language = Yii::$app->session->get('language', Yii::$app->params['default_content_lang']);
        }
        // Luon set lang cua backend la vi

        //Yii::$app->language = 'en';

    },
    'container' => [
        'definitions' => [
            yii\grid\GridView::class => [
                'pager' => [
                    'firstPageLabel' => '&laquo;',
                    'nextPageLabel' => '&rsaquo;',
                    'prevPageLabel' => '&lsaquo;',
                    'lastPageLabel'  => '&raquo;'
                ],
            ],
        ],
    ],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [YII_DEBUG ? '/js/jquery-3.6.0.js' : '/js/jquery-3.6.0.min.js'],
                    'jsOptions' => ['type' => 'text/javascript'],
                ],
            ],
        ],
//        'queue' => [
//            'class' => \yii\queue\amqp_interop\Queue::class,
//            'port' => 5672,
//            'user' => 'notificator',
//            'password' => 'notificator',
//            'queueName' => 'queue',
//            'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
//
//            // or
//            //'dsn' => 'amqp://guest:guest@localhost:5672/%2F',
//
//            // or, same as above
//            //'dsn' => 'amqp:',
//        ],

        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_core_cms',
                'httpOnly' => true,
                'expire' => 3600,
                'secure' => true,
            ],
            'authTimeout'=> 3600, //ONE MINUTE.
        ],
        'session' => [
            'cookieParams' => ['lifetime' => 3600],
            'timeout' => 3600, //session expire
        ],
        'request' => [
            'enableCsrfCookie' => false,
        ],

//        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => '192.168.146.252',
//            'port' => 9600,
//            'database' => 5,
//        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => '/site/index',
                'login' => '/site/login',
                'logout' => '/site/logout',
            ],
            // 'baseUrl' => 'https://sdkcms.ringme.vn'
            // 'baseUrl' => 'https://uatsdkcms.ringme.vn'
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [

                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => '@logs/cms/error.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['warning'],
                    'logFile' => '@logs/cms/warning.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@logs/cms/info.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['yii\db\Command*'],
                    'logVars' => [],
                    'logFile' => '@logs/cms/queries.log',
                ],
                [
                    'categories' => ['app_api'],
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@logs/app_api/info.log',
                ],

            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
		'gridview' => 'kartik\grid\Module',
        'i18n' => [
            'translations' => [
                'kvdrp*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                ],
                'roxy' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                ],

            ],
        ],
    ],
	'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ],
        'gii1' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                
            ],
        ],
        'gridview' => 'kartik\grid\Module',
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
//            '*',
            'site/*',
        ]
    ],
    'params' => $params,
];
