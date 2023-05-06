<?php

use awesome\backend\widgets\AwsBaseHtml;
use \yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SystemSetting */
/* @var $title string */
/* @var $form AwsActiveForm */
?>

<?php  $form = ActiveForm::begin(); ?>

    <div class="portlet light portlet-fit portlet-form bordered system-setting-form">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-paper-plane "></i>
                <span class="caption-subject  sbold uppercase">
                <?=  $title ?>
                </span>
            </div>

        </div>
        <div class="portlet-body">
            <div class="form-body">
                    <?= $form->field($model, 'config_key')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'config_value')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            </div>
        </div>
        <div class="portlet-title">
            
            <div class="actions">
                <?=  AwsBaseHtml::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-info   btn-sm']) ?>
                <button type="button" name="back" class="btn btn-transparent black  btn-sm"
                        onclick="history.back(-1)">
                    <i class="fa fa-angle-left"></i> Back
                </button>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>
