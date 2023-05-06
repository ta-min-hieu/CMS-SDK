<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\models\GetOtpForm;

use frontend\assets\SubscriberAsset;

SubscriberAsset::register($this);
$this->title = Yii::t('frontend', 'Trang cá nhân');
?>

<br/>
<div class="box-title box-title2 container-fluid">
    <h2 class=""><?= Yii::t('frontend', 'Trang cá nhân'); ?></h2>
</div>

<?php
$modalTitle = Yii::t('frontend', 'Payback');
$btnName = 'payback';
?>
<?php if ($loan): ?>
    <div class="">
        <br/>
        <?= Yii::t('backend', 'Thank you for using iCredit service!'); ?>
        <br/>
        <?= Yii::t('frontend', 'You have debit of {service_name}: {loan_value} {loan_unit} on {loan_date}.', [
            'service_name' => Yii::$app->params['service_name'],
            'loan_value' => $loan->loan_value,
            'loan_unit' => $loan->loanType->loan_type_unit,
            'loan_date' => date('d/m/Y', strtotime($loan->loan_at)),
        ]); ?>
        <br/>
        <?= Yii::t('backend', 'Total paid money'); ?>: <?= $loan->paid_money ?>
        <br/>
        <?php $remainMoney = $loan->getRemainLoanMoney(); ?>
        <?= Yii::t('backend', 'Remain loan money'); ?>: <?= $remainMoney ?>
        <br/>
        <?= Yii::t('backend', 'Please do refill card for clear your debit and loan more. Help: 966. Thank you!') ?>

        <br/>
        <br/>
        <?php if ($remainMoney): ?>
            <button class="btn btn-primary" data-toggle="modal"
                    data-target="#payback-modal"><?= Yii::t('frontend', 'Payback'); ?></button>
        <?php endif; ?>
    </div>
    <!-- get otp Modal -->
    <div class="modal fade" id="payback-modal" tabindex="-1" role="dialog" aria-labelledby="reg-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content ">

                <div id="modal-body" class="modal-body " style="color: #000;">
                    <?php $mpsUrl = Url::to(['icredit/payback']); ?>
                    <?= Yii::t('frontend', 'Are you sure to payback {loan_money}ks for your loaning?', [
                        'loan_money' => $remainMoney
                    ]); ?>
                    <br/>
                    <div class="clearfix"></div>
                </div>
                <div class="text-center">
                    <form method="post" action="<?= Url::to(['icredit/payback']); ?>">

                        <a href="<?= $mpsUrl; ?>" class="btn btn-primary"><?= Yii::t('frontend', 'Đồng ý'); ?></a>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal"><?= Yii::t('frontend', 'Đóng'); ?></button>
                        <br/>
                        <br/>
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                               value="<?= Yii::$app->request->csrfToken; ?>"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="">
        <br/>
        <?= Yii::t('backend', 'Thank you for using iCredit service!'); ?>
        <br/>
        <?php
        $lang = Yii::$app->language;
        $lang = ($lang != 'en')? 'mu': 'en';
        $content = \frontend\models\SystemSetting::getConfigByKey('MT_CHECK_NO_DEBIT_'. strtoupper($lang));
        ?>
        <?= $content; ?>

        <br/>
        <br/>

    </div>
<?php endif; ?>

<br/>
<hr/>
<?= $this->render('@app/views/subscriber/_personalLink', []) ?>
<br/>

