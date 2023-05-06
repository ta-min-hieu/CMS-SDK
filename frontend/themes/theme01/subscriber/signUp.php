<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\models\GetOtpForm;

use frontend\assets\SubscriberAsset;

SubscriberAsset::register($this);
$this->title = Yii::t('frontend', 'Sign Up');
?>

<div class="box-title box-title2 text-center">
    <h2 class=""><?= Yii::t('frontend', 'Sign Up')?></h2>
</div>

<div class="tab-content-container">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [
            'class' => 'lock-form'
        ]]); ?>
        <?= $form->field($model, 'username')->textInput([
            "class" => "form-control",
            "autocomplete" => "off",
            "placeholder" => Yii::t('frontend', 'Số điện thoại'),
            "id" => 'loginform-username',
        ])->label(false); ?>

        <?= $form->field($model, 'password')->passwordInput([
            "class" => "form-control",
            "autocomplete" => "off",
            "placeholder" => Yii::t('frontend', 'Password'),
        ])->label(false); ?>

        <?= $form->field($model, 're_password')->passwordInput([
            "class" => "form-control",
            "autocomplete" => "off",
            "placeholder" => Yii::t('frontend', 'Retype password'),
        ])->label(false); ?>
        <?= $form->field($model, 'otp', [
            'options' => [
                'class' => 'form-group row',
            ],
            'template' => '<div id="col-otp-btn" class="col-6 " ><a class="btn btn-light btn-lucky ajax-btn"  data-target="#get-otp-modal" ahref="' . Url::to(['subscriber/get-otp']) . '" href="javascript:void(0);">' . Yii::t('frontend', 'Lấy mã OTP') . '</a></div><div class="col-6 " id="col-pass">{input}{error}</div><div class="clearfix"></div>',
        ])->passwordInput([
            "class" => "form-control",
            "autocomplete" => "off",
            "placeholder" => Yii::t('frontend', 'OTP'),
            "id" => 'otp'
        ])->label(false); ?>
        <?php /* $form->field($model, 'captcha', [
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

        ])->label(false); */?>
        <div class="clearfix"></div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('frontend', 'Sign Up'), ['class' => 'btn btn-warning btn-lucky']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <br />
    </div>
</div>
<!-- get otp Modal -->
<div class="modal fade" id="get-otp-modal" tabindex="-1" role="dialog" aria-labelledby="get-otp-modalLabel"
     aria-hidden="true">
    <div class="modal-dialog tb-modal-dialog" role="document">
        <div class="modal-content tb-modal-content">

            <div>

                <div id="modal-body" class="modal-body">

                    <?php
                    $getOtpModal = new GetOtpForm();
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
                        'template' => '<div class="col-6 ">{input}</div><div class="col-6">{image}<span  id="otp-refresh-captcha" class="fa fa-refresh captcha-refresh-icon" aria-hidden="true"></span></div><div class="clearfix"></div>',
                        'options' => [
                            "class" => 'form-control',
                            "autocomplete" => "off",
                            "placeholder" => Yii::t('frontend', 'Mã xác nhận'),
                            "style" => ""
                        ],

                    ])->label(false); ?>
                    <div class="clearfix"></div>
                    <div class="modal-footer" style="padding-bottom: 0;">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal"><?= Yii::t('frontend', 'Đóng'); ?></button>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <button type="button" class="btn btn-lucky"><?= Yii::t('frontend', 'Lấy OTP'); ?></button>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <div class="popup-footer"></div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<< EOT_JS_CODE
    $('#get-otp-modal').on('hidden.bs.modal', function () {
        $('#otp').focus();
    });
    
EOT_JS_CODE
);
?>
