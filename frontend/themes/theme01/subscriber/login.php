<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\models\GetOtpForm;

use frontend\assets\SubscriberAsset;

SubscriberAsset::register($this);
$this->title = Yii::t('frontend', 'Đăng nhập hệ thống');
?>

<div class="box-title box-title2 text-center">
    <h2 class=""><?= Yii::t('frontend', 'Đăng nhập');?></h2>
</div>

<?php
/*<div class="alert alert-info" role="alert">
    <strong>!</strong> <?= Yii::t('frontend', 'Bạn đang không dùng 3G/4G. Vui lòng bật 3G/4G sau đó refresh lại trang hoặc đăng nhập bằng mật khẩu trước đó hoặc dùng OTP(One Time Password)'); ?>
</div>*/
?>

<?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [
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
    'template' => '<div class="col-6" id="col-pass">{input}{error}</div><div id="col-otp-btn" class="col-6" ><a class="btn btn-light btn-lucky ajax-btn"  href="' . Url::to(['subscriber/get-new-pass']) . '" >' . Yii::t('frontend', 'Get new password') . '</a></div><div class="clearfix"></div>',
])->passwordInput([


    "class" => "form-control",
    "autocomplete" => "off",
    "placeholder" => Yii::t('frontend', 'Password')
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
    <?= Html::submitButton(Yii::t('frontend', 'Đăng nhập'), ['class' => 'btn btn-warning btn-lucky']) ?>

</div>
<?= $form->field($model, 'using_otp', [
    'inputOptions' => ['id' => 'using_otp'],
])->hiddenInput()->label(false); ?>
<?php ActiveForm::end(); ?>


<?php if ($model->using_otp): ?>
    <?php
    $this->registerJs(<<< EOT_JS_CODE

    $(document).ready(function () {
        $('#use-otp-btn').click();
    });
    
EOT_JS_CODE
    );
    ?>
<?php endif; ?>
