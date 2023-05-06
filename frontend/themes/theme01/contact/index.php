<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;
use \frontend\models\SystemSetting;

/* @var $this yii\web\View */
/* @var $model backend\models\Contact */
/* @var $title string */
/* @var $form ActiveForm */
?>

<?= $this->render('@app/views/layouts/partials/_seo', [
    'title' => SystemSetting::getConfigByKey('SEO_CONTACT_TITLE'),
    'description' => SystemSetting::getConfigByKey('SEO_CONTACT_DESC'),
    'keywords' => SystemSetting::getConfigByKey('SEO_CONTACT_KEYWORDS'),
    'image' => 'http:///images/logo-retina.png',
    'url' => Yii::$app->request->getUrl(),
]); ?>

<?php $form = \kartik\form\ActiveForm::begin(); ?>

<div class="box box3 portlet light portlet-fit portlet-form bordered contact-form">
    <h1 class="box-title">Liên hệ</h1>
    <br />
    <div class="portlet-body">
        <div class="form-body row">
            <div class="col-md-6">
                <?= $form->field($model, 'fullname')->textInput(['maxlength' => 255, 'tabindex' => 1]) ?>
                <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'tabindex' => 3]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'phonenumber')->textInput(['maxlength' => 20, 'tabindex' => 2]) ?>

            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'address')->textInput(['maxlength' => 255, 'tabindex' => 4]) ?>
                <?= $form->field($model, 'body')->textarea(['rows' => 4, 'tabindex' => 5]) ?>

                <div class="form-group">
                    <?= $form->field($model, 'reCaptcha')->widget(\yii\captcha\Captcha::className(), [
                        'template' => '{input} {image} <a onclick="javascript:$(\'#contact-recaptcha-image\').click()" href="#refresh-captcha" class="glyphicon glyphicon-refresh refresh-captcha"></a>',
                        'options' => [
                            "class" => 'form-control form-control-solid placeholder-no-fix',
                            "autocomplete" => "off",
                            "placeholder" => Html::encode(Yii::t('frontend', "Mã xác nhận")),
                            "style" => "float:left; width: 150px;"
                        ],
                    ])->label(false); ?>
                </div>
            </div>

        </div>
    </div>
    <div class="portlet-title">

        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Gửi liên hệ') : Yii::t('backend', 'Update'), ['class' => 'btn btn-info']) ?>
        &nbsp;&nbsp;
        <input type="reset" class="btn btn-warning" value="Làm lại"/>
        &nbsp;
    </div>
    <br />
    <b>Xin trân trọng cảm ơn! </b>
    <br>
</div>

<?php \kartik\form\ActiveForm::end(); ?>

<?php
//$this->registerJs( <<< EOT_JS_CODE
//    $( document ).ready(function() {
//      $( "#contact-fullname" ).focus();
//    });
//
//EOT_JS_CODE
//);
?>
