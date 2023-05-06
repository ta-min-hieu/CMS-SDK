<?php use yii\helpers\Url;?>
<?php if (!Yii::$app->user->isGuest): ?>
<ul class="list-1 row">
    <li class="col-6 col-sm-4">
        <a href="<?= Url::to(['subscriber/change-pass']);?>"><?= Yii::t('frontend', 'Đổi mật khẩu');?></a>
    </li>
    <li class="col-6 col-sm-4">
        <a href="<?= Url::to(['subscriber/transaction-history']);?>" ><?= Yii::t('frontend', 'Lịch sử giao dịch');?></a>
    </li>
    <li class="col-6 col-sm-4">
        <a href="<?= Url::to(['subscriber/logout']);?>" ><?= Yii::t('frontend', 'Thoát');?></a>
    </li>
</ul>
<?php endif;?>