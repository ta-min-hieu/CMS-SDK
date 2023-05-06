<?php

use frontend\models\SystemSetting;
use \yii\helpers\Url;
use yii\helpers\Html;
use \common\helpers\Helpers;

?>
<?= \frontend\widgets\AlertToast::widget([]); ?>

<div id="footer">
    <div class="footer-adv text-center">
        <?php
        //$footerBanners = \frontend\models\Advertisment::getActiveAdvByPosition('footer_banner', 1);
//        if (count($footerBanners)) {
//            foreach ($footerBanners as $banner) {
//                echo '<a href="'.$banner->LINK.'" target="_blank"><img src="'.$banner->IMAGE_PATH.'" class=""/></a>';
//            }
//        }
        ?>
    </div>

    <?php
    $footerAdvs = \frontend\models\Advertisment::getActiveAdvByPosition('footer_link', 6);

    if (count($footerAdvs)):
        ?>
        <div id="footer1">
            <div class="container-fluid">
                <ul class="list-1 row">
                    <?php foreach ($footerAdvs as $adv): ?>
                        <li class="col-6 col-sm-4 blink_me">
                            <a href="<?= $adv->LINK; ?>" target="_blank"><?= Html::encode($adv->NAME) ?></a>
                        </li>
                    <?php endforeach; ?>

                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php endif; ?>

</div>

<div class="overlay"></div>

<?php if (YII_ENV_PROD): ?>
    <?php $gaId = SystemSetting::getConfigByKey('GA_ID') ?>
    <?php if($gaId):?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $gaId?>"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', '<?= $gaId?>');
        </script>
    <?php endif;?>

    <?php if (!Yii::$app->user->isGuest): ?>
        <?php $gaSubId = SystemSetting::getConfigByKey('GA_SUB_ID') ?>
        <?php if($gaSubId):?>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $gaSubId;?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?= $gaSubId?>');
            </script>
        <?php endif;?>
    <?php endif; ?>
<?php endif; ?>
<img style="width:1px;height:1px;position:absolute;left:-100px;" src="/images/ajax-loader.gif"/>
<div id="scrollButton"></div>
<div id="fb-root"></div>

