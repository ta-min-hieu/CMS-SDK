<?php
#die('HE THONG DANG BAO TRI. VUI LONG QUAY LAI SAU');
error_reporting(E_ALL);
ini_set('display_errors', 1);
#error_reporting(0);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
define('_APP_PATH_', dirname(__FILE__));
date_default_timezone_set('Asia/Ho_Chi_Minh');
ini_set('session.cookie_httponly', 1);
// ini_set('session.cookie_secure', 1);
// $_SERVER['HTTPS']= 'on';

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../../common/config/predefined.php');
require(__DIR__ . '/../../awesome/backend/autoload.php');
require(__DIR__ . '/../config/bootstrap.php');
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    @include (__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    @include (__DIR__ . '/../config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();
