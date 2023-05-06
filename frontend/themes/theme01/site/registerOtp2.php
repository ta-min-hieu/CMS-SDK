<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\models\GetOtpForm;

?>
<div class="container">
    <br/>
    <div class="text-center" style="line-height: 28px;">
        Enter your OTP password to finish register Myzartar service!
        <br/>
        It is FREE 7 days for first registration time and 02 Lucky Code promotion from Myluck service
        <br/>
        You can also dialing <span style="color: gold">*599#</span> or texting <a style="color:gold" href="sms:599&body=ON7">ON7 to 599</a>.

        <br/>
        <?php Yii::t('frontend', '{package_name} (Fee: {pkg_fee} {money_unit}/{per_day} days)', [
            'package_name' => $package->NAME,
            'pkg_fee' => $package->PRICE,
            'per_day' => $package->DAY_ADD,
            'money_unit' => Yii::$app->params['money_unit'],
        ]); ?>
    </div>

    <br/>

    <?php $form = ActiveForm::begin(['id' => 'reg-form', 'options' => [
        'class' => 'lock-form'
    ]]); ?>
    <?= $form->field($model, 'username')->textInput([
        "class" => "form-control",
        "autocomplete" => "off",
        "placeholder" => Yii::t('frontend', 'Số điện thoại')
    ])->label(false); ?>


    <?= $form->field($model, 'password', [
        'options' => [
            'class' => 'form-group row',
        ],
        'template' => '<div class="col-12" id="col-pass">{input}{error}</div><div class="clearfix"></div>',
    ])->passwordInput([
        "class" => "form-control",
        "autocomplete" => "off",
        "placeholder" => Yii::t('frontend', 'Otp')
    ])->label(false); ?>

    <?= $form->field($model, 'captcha', [
        'options' => [
            'class' => 'form-group field-loginform-captcha row',
        ],

    ])->widget(\yii\captcha\Captcha::className(), [
        'template' => '<div class="col-6 ">{input}</div><div class="col-6">{image}<span class="fa fa-refresh captcha-refresh-icon" aria-hidden="true"></span></div><div class="clearfix"></div>',
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
    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Subscriber'), ['class' => 'btn btn-warning btn-lucky']) ?>

    </div>
    <?= $form->field($model, 'using_otp', [
        'inputOptions' => ['id' => 'using_otp'],
    ])->hiddenInput()->label(false); ?>
    <?php ActiveForm::end(); ?>

</div>