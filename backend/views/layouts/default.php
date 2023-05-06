<?php
/* @var $this \yii\web\View */
/* @var $content string */

use common\components\toast\AwsAlertToast;
use backend\assets\DefaultAsset;
use yii\helpers\Html;

DefaultAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <!--[if IE 8]>
    <html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
    <!--[if IE 9]>
    <html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
    <!--[if !IE]><!-->
    <html lang="<?= Yii::$app->language ?>" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
    </head>

    <?php
    $bgArr = Yii::$app->params['login_background_img'];
    $bgUrl = isset($bgArr[rand(0, (count($bgArr) - 1))])? $bgArr[rand(0, (count($bgArr) - 1))]: '';
    ?>
    <body class="" style="background-image: url(<?= $bgUrl; ?>);">
        <div id="page-loading">
            <img src="/img/ajax-loader2.gif" />
        </div>
    <?php $this->beginBody() ?>
    <div class="page-lock">
        <div class="page-logo">
            <a class="brand" href="index.php">
                 </a>
        </div>
        <div class="page-body">
            <div class="lock-head"><?= Html::encode($this->title) ?></div>
            <div class="lock-body">
                <?= $content ?>
            </div>

            <?=
            AwsAlertToast::widget([
                'position' => AwsAlertToast::POS_TOP_CENTER,
                'timeOut' => 20000,

            ]);
            ?>
        </div>
        <div class="page-footer-custom"></div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>