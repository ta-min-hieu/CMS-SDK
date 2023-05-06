<?php

use \yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<div class="container">
    <br/>
    <div class="text-center" style="line-height: 28px;">
        Do you want to know your future every day with MyZartar?
        <br/>
        It is FREE 7 days for first registration time and 02 Lucky Code promotion from Myluck service
        <br/>
        Subscribe NOW to find out about your future. You can also dialing <span style="color: gold">*599#</span> or texting <a style="color:gold" href="sms:599&body=ON7">ON7 to 599</a>.

        <br/>
        <?php Yii::t('frontend', '{package_name} (Fee: {pkg_fee} {money_unit}/{per_day} days)', [
            'package_name' => $package->NAME,
            'pkg_fee' => $package->PRICE,
            'per_day' => $package->DAY_ADD,
            'money_unit' => Yii::$app->params['money_unit'],
        ]); ?>
    </div>

    <br/>
    <?php

    $form = ActiveForm::begin(['id' => 'get-otp-form', 'options' => [
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
    <div class="modal-footer" style="padding-bottom: 0;">
        <button type="submit" class="btn btn-lucky"><?= Yii::t('frontend', 'Next step'); ?> <i class="fa fa-arrow-right"></i></button>
    </div>
    <?php ActiveForm::end(); ?>

</div>