<?php

use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
use \yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
//use \backend\models\Branch;
//use \backend\models\Partner;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $title string */
/* @var $form AwsActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="portlet light portlet-fit portlet-form bordered user-form">

    <div class="portlet-body">
        <div class="form-body row">
            <div class="col-md-6">
                <?= $form->field($model, 'id')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'disp_name')->textInput(['maxlength' => 500]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'birth_day')->textInput(['maxlength' => 500]) ?>
            </div>
            <div class="col-md-6">
            <?= $form->field($model, 'phone_number')->input('number', ['min' => 0, 'max' => 99999999999, 'step' => 1, 'pattern' => '0[0-9]{8,10}']) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'type')->textInput(['maxlength' => 500]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'agent')->textInput(['maxlength' => 500]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => 500]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'avatar')->textInput(['maxlength' => 500]) ?>
            </div>
        </div>
    </div>

    <div class="portlet-title">
        <a href="<?= \yii\helpers\Url::to(['index']); ?>" class="btn btn-default btn-sm">
            <i class="fa fa-angle-left"></i> <?= Yii::t('backend', 'Back') ?> </a>
        &nbsp;&nbsp;&nbsp;
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-transparent green  btn-sm']) ?>

    </div>
</div>

<?php ActiveForm::end(); ?>