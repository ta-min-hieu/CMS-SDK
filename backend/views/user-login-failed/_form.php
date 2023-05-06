<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLoginFailed */
/* @var $title string */
/* @var $form ActiveForm */
?>

<?php  $form = \kartik\form\ActiveForm::begin(); ?>

    <div class="portlet light portlet-fit portlet-form bordered user-login-failed-form">

        <div class="portlet-body">
            <div class="form-body row">
                    <div class="col-md-6">
        <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'user_id')->textInput(['maxlength' => 20]) ?>

    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'ip')->textInput(['maxlength' => 50]) ?>

    </div>

            </div>
        </div>
        <div class="portlet-title row">
            
            <div class="form-actions">
                <?=  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-info']) ?>
                &nbsp;
                <a href="<?= \yii\helpers\Url::to(['index']); ?>" class="btn btn-default">
                    <i class="fa fa-angle-left"></i> <?=  Yii::t('backend', 'Back') ?>                </a>
            </div>
        </div>
    </div>

<?php \kartik\form\ActiveForm::end(); ?>
