<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Khôi phục mật khẩu';
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="box-title"><?= $this->title; ?></h3>
        <br />
        <br />
    </div>
    <?php //yii\widgets\Pjax::begin(['id' => 'change-pass-form']) ?>
    <?php $form = ActiveForm::begin(['id' => 'reset-password-form', 'options' => ['data-pjax' => true ]]); ?>

    <div class="col-md-12 col-sm-12 box">
        <?= $form->field($model, 'password')->passwordInput([
            'maxlength' => 255,
            'placeholder' => 'Mật khẩu mới',
            'autofocus' => true,
        ])->label(false) ?>
        <?= $form->field($model, 're_password')->passwordInput([
            'maxlength' => 255,
            "autocomplete" => "off",
            'placeholder' => 'Nhập lại mật khẩu mới',
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

    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton("Khôi phục mật khẩu", ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php //yii\widgets\Pjax::end() ?>
</div>
