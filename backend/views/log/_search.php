<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use \kartik\daterange\DateRangePicker;
use backend\models\User;
/* @var $this yii\web\View */
/* @var $model backend\models\BoxChatSearch */
/* @var $form yii\widgets\ActiveForm */
$oa = User::findOne($model->id);
?>

<div class="panel panel-default box-chat-search">
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
        <?= $form->field($model, 'id_user')->widget(Select2::classname(), [
                    'initValueText' => $oa ? $oa->id : '',
                    'options' => ['placeholder' => Yii::t('backend', 'Find Account ID or Account')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return '" . Yii::t('backend', 'Loading') . " ...'; }"),
                            'inputTooShort' => new JsExpression("function () { return '" . Yii::t('backend', 'Input at least 1 characters') . " ...'; }"),
                            'inputTooLong' => new JsExpression("function () { return '" . Yii::t('backend', 'Input maximum 255 characters') . "'; }"),
                            'noResults' => new JsExpression("function () { return '" . Yii::t('backend', 'No result found') . "'; }"),
                            'searching' => new JsExpression("function () { return '" . Yii::t('backend', 'Searching') . " ...'; }"),
                        ],
                        'ajax' => [
                            'url' => \yii\helpers\Url::toRoute(['log/ajax-search']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term }; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(result) { return result.text; }'),
                        'templateSelection' => new JsExpression('function (result) { return result.text; }')
                    ]
                ])->label(Yii::t('backend', 'Account ID - Account')); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'action') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'id_object') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'name_object') ?>
        </div>
        <div class="col-md-3">
            <?php echo

            $form->field($model, 'created_at')->widget(\kartik\daterange\DateRangePicker::className(), [
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
            ]) ?>

        </div>
        <?php // echo $form->field($model, 'created_at') 
        ?>

        <?php // echo $form->field($model, 'updated_at') 
        ?>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                &nbsp;&nbsp;&nbsp;
                <?= Html::a(Yii::t('backend', 'Reset'), ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>