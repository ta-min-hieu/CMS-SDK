<?php
/* @var $this View */

use yii\web\View;
use backend\models\User;
use backend\models\TelesaleStorage;
use common\helpers\Helpers;
use backend\components\common\TelesaleSoapCaller;
use yii\helpers\Url;
use \backend\models\BookingHistory;

$this->title = Yii::$app->params['system_name'];
?>
    <div class="site-index">
        <?php
        $user = Yii::$app->user;

        ?>
        <br />
        <?php // var_dump(Yii::$app->language)?>


    </div>

<?php //\backend\assets\HomepageAsset::register($this); ?>