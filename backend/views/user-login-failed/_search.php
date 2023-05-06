<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLoginFailedSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="panel panel-default user-login-failed-search">
    <div class="panel-body row">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="col-md-6">
            <?= $form->field($model, 'username') ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'ip') ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'created_at')->widget(\kartik\daterange\DateRangePicker::className(), [
                    'options' => ['class' => 'form-control daterange-field input-sm'],
                    'model' => $model,
                    'attribute' => 'created_at',
                    'convertFormat' => true,
                    'presetDropdown' => true,
                    'readonly' => true,
                    'pluginOptions' => [
                        'opens' => 'left',
                        'alwaysShowCalendars' => true,
                        'timePickerIncrement' => 30,
                        'locale' => [
                            'format' => 'd/m/Y',
                        ]
                    ],
                    'pluginEvents' => [
                        'cancel.daterangepicker' => "function(ev, picker) {
                                            $(this).val('');
                                        }",
                        'apply.daterangepicker' => 'function(ev, picker) {
                                            if($(this).val() == "") {
                                                $(this).val(picker.startDate.format(picker.locale.format) + picker.locale.separator +
                                                picker.endDate.format(picker.locale.format)).trigger("change");
                                            }
                                        }',
                        'show.daterangepicker' => 'function(ev, picker) {
                                            picker.container.find(".ranges").off("mouseenter.daterangepicker", "li");
                                            if($(this).val() == "") {
                                                picker.container.find(".ranges .active").removeClass("active");
                                            }
                                        }',
                    ]
                ]
            ) ?>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                &nbsp;&nbsp;&nbsp;
                <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
