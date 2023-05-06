<?php
use yii\web\Request;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'frontend',
    'name' => 'Horoscope',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'en',
    'modules' => [
        'api' => [
            'class' => 'app\modules\api',
        ],

        'debug' => [
            'class' => 'yii\debug\Module',
        ],
    ],


    //'vendorPath' => $_SERVER['DOCUMENT_ROOT'].'/../framework/yii2/vendor',
    'components' => [
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        // YII_ENV_DEV ? 'css/bootstrap.css':  'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ]
                ]
            ],
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/theme01',
                'baseUrl' => '@web/themes/theme01',
                'pathMap' => [
                    '@app/views' => '@app/themes/theme01',
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'httpClient' => [
                'transport' => 'yii\httpclient\CurlTransport',
            ],
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '94132477445-7dsbv2ug8gqmm18ijbvn02qn6vquep0t.apps.googleusercontent.com',
                    'clientSecret' => 'jPKjLizRSuaXQsKa6bCAmRsu',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '326635987800533',
                    'clientSecret' => '3f9af040bdc3af4f2c12b2bc1ec7ec39',
                    'attributeNames' => ['name', 'email', 'first_name', 'last_name', 'picture.type(large)', 'cover', 'gender'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'frontend' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],

            ],
        ],
//        'request' => [
//            'csrfParam' => '_csrf-frontend',
//        ],
        'user' => [
            'identityClass' => 'frontend\models\Subscriber',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'fe_sess',
        ],

//        'session' => [
//            'class' => 'yii\redis\Session',
//            'redis' => 'redis' // id of the connection application component
//            'redis' => [
//                'hostname' => 'localhost',
//                'port' => 7000,
//                'database' => 0,
//            ],
//        ],
//        'cache' => [
//            'class' => 'yii\redis\Cache',
//            'redis' => 'redis' // id of the connection application component
//        ],

        
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [

                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => '@logs/frontend/error.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['warning'],
                    'logFile' => '@logs/frontend/warning.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@logs/frontend/info.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['yii\db\Command*'],
                    'logVars' => [],
                    'logFile' => '@logs/frontend/queries.log',
                ],

            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],


        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // chi nhung rule trong list moi dc phep truy cap
            'enableStrictParsing' => false,
            'rules' => require(__DIR__ . '/routes.php')
        ],

    ],
    'params' => $params,
];
