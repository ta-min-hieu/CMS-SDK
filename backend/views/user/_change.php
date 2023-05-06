<?php

use awesome\backend\widgets\AwsBaseHtml;
use awesome\backend\form\AwsActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $title string */
/* @var $form AwsActiveForm */
?>

<?php $form = AwsActiveForm::begin(); ?>

<div class="portlet light portlet-fit portlet-form bordered user-form">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-paper-plane "></i>
                <span class="caption-subject  sbold uppercase">
                <?= $title ?>
                </span>
        </div>
        <div class="actions">
            <?= AwsBaseHtml::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-transparent  btn-sm']) ?>
            <button type="button" name="back" class="btn btn-transparent black  btn-sm"
                    onclick="history.back(-1)">
                <i class="fa fa-angle-left"></i> Back
            </button>
        </div>
    </div>
    <div class="portlet-body">
        <div class="form-body">
            <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'password_hash')->passwordInput() ?>

            <?= $form->field($model, 're_password')->passwordInput() ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'status')->checkbox() ?>
        </div>
    </div>
</div>

<?php AwsActiveForm::end(); ?>
