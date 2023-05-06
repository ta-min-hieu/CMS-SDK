<?php

/* @var $this yii\web\View */
/* @var $form awesome\backend\form\AwsActiveForm */
/* @var $model \backend\models\ResetPasswordForm */


use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

$this->title = Yii::t('backend', 'Change Password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <?php $form = ActiveForm::begin(['id' => 'change-password-form', 'options' => [
        'class' => 'lock-form'
    ]]); ?>
    <?= $form->field($model, 'username')->textInput([
        "class" => "form-control",
        "autocomplete" => "off",
        "disabled" => "disabled"
    ]); ?>
    <?= $form->field($model, 'password')->passwordInput([
        "class" => "form-control",
        "autocomplete" => "off",
    ]); ?>
    <?= $form->field($model, 're_password')->passwordInput([
        "class" => "form-control",
        "autocomplete" => "off",
    ]); ?>
    <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
        'template' => '{input} {image}',
        'options' => [
            "class" => 'form-control',
            "autocomplete" => "off",
            "style" => "float:left; width:150px;",
            'placeholder' => Yii::t('backend', 'Captcha'),
        ],
    ])->label(false); ?>
    <div class="form-actions noborder">
        <?= Html::submitButton(Yii::t('backend', 'Confirm'), ['class' => 'btn btn-primary uppercase', 'name' => 'login-button']) ?>
        &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;
        <?= Html::a(Yii::t('backend', 'Go to homepage'), ["/"]) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <br/>
</div>