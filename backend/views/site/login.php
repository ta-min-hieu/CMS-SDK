<?php

/* @var $this yii\web\View */
/* @var $form awesome\backend\form\AwsActiveForm */
/* @var $model \backend\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('backend', Yii::$app->params['system_name']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12" style="padding-left: 30px;padding-right: 30px;">
    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [
        'class' => 'lock-form'
    ]]); ?>
    <?= $form->field($model, 'username')->textInput([
        "class" => "form-control",
        "autocomplete" => "off",
    ]); ?>
    <?= $form->field($model, 'password')->passwordInput([
        "class" => "form-control",
        "autocomplete" => "off",
    ]); ?>

    <div class="form-group">
        <?php echo $form->field($model, 'reCaptcha')->widget(\yii\captcha\Captcha::className(), [
        'template' => '{input} {image}',
        'options' => [
            "class" => 'form-control form-control-solid placeholder-no-fix',
            "autocomplete" => "off",
            "placeholder" => Html::encode(Yii::t('backend', "Captcha")),
            "style" => "float:left; width: 50%;"
        ],
    ])->label(false); ?>
        <?php
        // Google captcha
        /*
        echo $form->field($model, 'reCaptcha')->widget(
            \himiklab\yii2\recaptcha\ReCaptcha::className(),
            [
                'siteKey' => Yii::$app->params['recaptcha_site_key'],
            ]
        )->label(false) */ ?>
    </div>

    <div class="form-actions noborder text-center">
        <?= Html::submitButton(Yii::t('backend','Đăng nhập'), ['class' => 'btn btn- purple uppercase', 'name' => 'login-button', "style" => "width:200px;"]) ?>
        <?php
        /*
         <label class="rememberme check ">


            <?= $form->field($model, 'rememberMe')->checkbox([
                'label' => Html::encode(Yii::t('backend', "Remember Me"))
            ]) ?>
        </label>
         */
        ?>
    </div>

    <?php ActiveForm::end(); ?>
    <br/>
</div>