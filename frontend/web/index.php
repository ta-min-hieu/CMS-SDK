<?php
#die('HE THONG DANG BAO TRI. VUI LONG QUAY LAI SAU');
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV_DEV') or define('YII_ENV_DEV', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
define('_APP_PATH_', dirname(__FILE__));

ini_set('session.cookie_httponly', 1);
error_reporting(0);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../../common/config/predefined.php');
require(__DIR__ . '/../../awesome/backend/autoload.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    include(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    include(__DIR__ . '/../config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();
