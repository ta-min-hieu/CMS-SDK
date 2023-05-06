<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\VtUser */
/* @var $form backend\widgets\form\AwsActiveForm */
?>
<br>
<div class="user-form ">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'password_old')->passwordInput([
            'tabindex' => 1,
        ]) ?>
        <?= $form->field($model, 'new_password')->passwordInput([
                'tabindex' => 1,
        ])?>
        <?= $form->field($model, 're_password')->passwordInput([
            'tabindex' => 2,
        ]) ?>


    </div>
    <div class="col-md-6">

        <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'tabindex' => 3]) ?>
        <?= $form->field($model, 'fullname')->textInput(['maxlength' => 255, 'tabindex' => 4]) ?>
        <?= $form->field($model, 'address')->textArea(['maxlength' => 500, 'tabindex' => 5, 'rows' => 4]) ?>

    </div>


    <div class="col-md-12 form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
