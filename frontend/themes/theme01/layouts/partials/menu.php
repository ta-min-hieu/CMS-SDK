<?php

use \yii\helpers\Url;
use \frontend\models\SystemSetting;

?>
<div class="container text-center">
    <?php if (!Yii::$app->user->isGuest): ?>
        <br />
        <br />
        <a href="<?= Url::to(['subscriber/index']); ?>" class="hi-msisdn"><?= Yii::t('frontend', 'Hi {user}', ['user' => Yii::$app->user->identity->getDisplayMsisdn() ]); ?></a>
    <?php endif; ?>


</div>
<div id="menu" class="container">
    <div class="row text-center" >
        <div class="col-3">
            <a href="<?= Url::to(['site/index', ]); ?>"
               title="<?= SystemSetting::getConfigByKey('SEO_HOME_TITLE'); ?>"><?= Yii::t('frontend', 'Trang chủ'); ?></a>
        </div>
        <div class="col-3">
            <a class="" href="<?= Url::to(['site/terms', ]); ?>"
               title="<?= Yii::t('frontend', 'Guide'); ?>"><?= Yii::t('frontend', 'Guide'); ?></a>
        </div>
        <div class="col-3">
            <?php if (Yii::$app->user->isGuest): ?>
                    <a class="" title="<?= Yii::t('frontend', 'Đăng nhập'); ?>"
                       href="<?= Url::to(['subscriber/login']); ?>"><?= Yii::t('frontend', 'Đăng nhập'); ?></a>
            <?php else: ?>
                <a href="<?= Url::to(['subscriber/logout']) ?>">
                    <?= Yii::t('frontend', 'Thoát'); ?>
                </a>
            <?php endif; ?>
        </div>
        <div class="col-3">
            <div class="dropdown">
                <div style="background: transparent;border: none;outline: none;" type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                    <img src="<?= (Yii::$app->language == 'en')? '/images/united-kingdom-flag-icon-32.png': '/images/myanmar-flag-icon-32.png'; ?>" />
                </div>
                <div class="dropdown-menu" style="min-width: auto;">
                    <a class="dropdown-item" href="<?= Url::to(['site/change-language', 'lang' => 'es']); ?>"><img
                                src="/images/myanmar-flag-icon-32.png"/></a>
                    <a class="dropdown-item" href="<?= Url::to(['site/change-language', 'lang' => 'en']); ?>"><img
                                src="/images/united-kingdom-flag-icon-32.png"/></a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>