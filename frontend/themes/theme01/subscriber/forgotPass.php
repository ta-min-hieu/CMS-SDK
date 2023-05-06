<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Quên mật khẩu';
?>


<div class="row">
    <div class="col-md-12">
        <h3 class="box-title"><?= $this->title; ?></h3>

        <?php if ($sendResult === null): ?>
            <p class="text" style="color: #40e0d0;">Vui lòng nhập địa chỉ email đã đăng ký trên hệ thống! Hệ thống sẽ gửi link khôi phục mật khẩu vào email!</p>
            <br />
            <?php //yii\widgets\Pjax::begin(['id' => 'change-pass-form']) ?>
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>


            <?= $form->field($model, 'email')->textInput([
                'maxlength' => 255,
                'placeholder' => 'Email',
                'autofocus' => true,
            ])->label(false) ?>


            <?= $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="col-md-6 col-sm-6 col-xs-6">{input}</div><div class="col-md-6 col-sm-6 col-xs-6">{image}<span style="color:#7fff00; cursor: pointer;" class="glyphicon glyphicon-refresh captcha-refresh-icon"></span></div>',
                'options' => [
                    "class" => 'form-control row',
                    "autocomplete" => "off",
                    "placeholder" => 'Mã xác nhận',
                    "style" => ""
                ],

            ])->label(false); ?>

            <div class="clearfix"></div>
            <br />
            <div class="form-group ">
                <?= Html::submitButton("Khôi phục mật khẩu", ['class' => 'btn btn-primary']) ?>
            </div>


            <?php ActiveForm::end(); ?>
            <?php //yii\widgets\Pjax::end() ?>
        <?php elseif ($sendResult === true): ?>
            <br />
            <p class="text" style="color: #40e0d0;">Vui lòng kiểm tra email để khôi phục mật khẩu của bạn!</p>
            <br />
        <?php else: ?>
            <br />
            <p class="text" style="color: #40e0d0;">Hệ thống đang bận, Vui lòng thử lại sau!</p>
            <br />
        <?php endif; ?>
    </div>

</div>
