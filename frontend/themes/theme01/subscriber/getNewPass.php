<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
\frontend\assets\AppAsset::register($this);
?>
    <br/>
    <div class="box-title box-title2 container-fluid">
        <h2 class=""><?= Yii::t('frontend', 'Get password');?></h2>
    </div>

    <div class="tab-content-container">
        <div class="col-md-12">
            <br/>
            <?php
            $form = ActiveForm::begin(['id' => 'login-form', 'options' => [
                'class' => 'ajax-modal-form',
                'action' => Url::to(['subscriber/get-otp']),
            ]]);
            ?>
            <?= $form->field($getOtpModal, 'msisdn', [
                'options' => [
                    'class' => 'form-group row',
                ],
                'template' => '<div class="col-12" >{input}{error}</div>',
            ])->textInput([


                "class" => "form-control",
                "autocomplete" => "off",
                "placeholder" => Yii::t('frontend', 'Số điện thoại')
            ])->label(false); ?>

            <?= $form->field($getOtpModal, 'captcha', [
                'options' => [
                    'class' => 'form-group field-loginform-captcha row',
                ],

            ])->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="col-6 ">{input}</div><div class="col-6">{image}<span id="otp-refresh-captcha" class="fa fa-refresh captcha-refresh-icon" aria-hidden="true"></span></div><div class="clearfix"></div>',
                'imageOptions' => [
                    'class' => 'captcha-img'
                ],
                'options' => [
                    "class" => 'form-control',
                    "autocomplete" => "off",
                    "placeholder" => Yii::t('frontend', 'Mã xác nhận'),
                    "style" => ""
                ],

            ])->label(false); ?>
            <div class="clearfix"></div>
            <div class="" style="padding-bottom: 0;">
                <button type="submit" class="btn btn-lucky"><?= Yii::t('frontend', 'Get new password'); ?></button>
                <br />
                <br />
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
$this->registerJs(<<< EOT_JS_CODE
    if ($('#getotpform-msisdn').val()) {
        $('#getotpform-captcha').focus();
    } else {
        $('#getotpform-msisdn').focus();
    }
EOT_JS_CODE
);
?>