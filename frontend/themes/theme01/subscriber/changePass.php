<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

frontend\assets\SubscriberAsset::register($this);
?>
<?php $this->title = Yii::t('frontend', 'Đổi mật khẩu'); ?>
<div class="box-title box-title2 container-fluid">
    <h2 class=""><?= Yii::t('frontend', 'Đổi mật khẩu');?></h2>
</div>

<?php //yii\widgets\Pjax::begin(['id' => 'change-pass-form']) ?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>
    <div class="row">
        <div class="col-12">
            <?= Yii::t('frontend', 'Để đổi mật khẩu, vui lòng nhập đúng mật khẩu cũ trước đó hoặc sử dụng mã OTP (one time password)'); ?>
            <br />
            <br />
            <div class="text-center tab1">
                <div class="btn-group text-center" role="group" aria-label="">
                    <button id="use-pass-btn" type="button"
                            class="btn btn-lucky active-tab"><?= Yii::t('frontend', 'Sử dụng mật khẩu'); ?></button>
                    <button id="use-otp-btn" type="button"
                            class="btn btn-secondary "><?= Yii::t('frontend', 'Sử dụng OTP'); ?></button>
                </div>
            </div>
            <br/>
            <br/>

            <?= $form->field($model, 'old_password', [
                'options' => [
                    'class' => 'form-group row',
                ],
                'template' => '<div class="col-6 col-12" id="col-pass">{input}{error}</div><div id="col-otp-btn" class="col-6 d-none" ><a class="btn btn-light ajax-btn"  data-target="#get-otp-modal" ahref="' . Url::to(['subscriber/get-otp']) . '" href="javascript:void(0);">' . Yii::t('frontend', 'Lấy mã OTP') . '</a></div><div class="clearfix"></div>',
            ])->passwordInput([


                "class" => "form-control",
                "autocomplete" => "off",
                "placeholder" => Yii::t('frontend', 'Mật khẩu cũ / OTP')
            ])->label(false); ?>
        </div>


        <div class="col-12">
            <?= $form->field($model, 'new_password')->passwordInput([
                'maxlength' => 255,
                'placeholder' => Yii::t('frontend','Mật khẩu mới'),
            ])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput([
                'maxlength' => 255,
                "autocomplete" => "off",
                'placeholder' => Yii::t('frontend','Nhập lại mật khẩu mới'),
            ])->label(false) ?>

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

        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('frontend', "Đổi mật khẩu"), ['class' => 'btn btn-lucky']) ?>

                <?= $form->field($model, 'using_otp', [
                    'inputOptions' => ['id' => 'using_otp'],
                ])->hiddenInput()->label(false); ?>
            </div>
        </div>


    </div>
<?php ActiveForm::end(); ?>


<!-- get otp Modal -->
<div class="modal fade" id="get-otp-modal" tabindex="-1" role="dialog" aria-labelledby="get-otp-modalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id=""><?= Yii::t('frontend', 'Lấy mật khẩu OTP'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div>

                <div id="modal-body" class="modal-body">
                    <?php
                    $getOtpModal = new \frontend\models\GetOtpForm();
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

                </div>

            </div>
        </div>
    </div>
</div>
<br />
<br />
<?= $this->render('@app/views/subscriber/_personalLink', [])?>

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
