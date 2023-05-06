<?php

use backend\models\Staff;
use backend\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\ReportSDKSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default total-stat-search">
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'phone_number')->widget(Select2::classname(), [
                'value' => isset($_GET['phone_number']) ? $_GET['phone_number'] : '',
                // 'initValueText' => (isset($_GET['phone_number']) && $_GET['phone_number']!=null) ? $_GET['phone_number'] . ' - ' . Staff::findOne(['phone_number' => $_GET['phone_number']])->staff_name : '',
                'options' => ['placeholder' => Yii::t('backend', 'Tìm theo số điện thoại hoặc tên nhân viên')],
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
                        'url' => \yii\helpers\Url::toRoute(['report-info-agent-at-queue/ajax-search']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term }; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }')
                ]
            ])->label(Yii::t('backend', 'Số điện thoại - Tên nhân viên')); ?>
        </div>
        <div class="col-md-3">
            <?php echo
            $form->field($model, 'date')->widget(\kartik\daterange\DateRangePicker::className(), [
                'options' => [
                    'class' => 'form-control daterange-field input-sm',
                    // 'value' => (isset($_GET['date'])) ? $_GET['date'] : date('Y-m-d') . " - " . date("Y-m-d"),
                ],
                'model' => $model,
                'attribute' => 'date',
                'convertFormat' => true,
                'presetDropdown' => true,
                'readonly' => true,
                // 'value' => (isset($_GET['date'])) ? $_GET['date'] : date('Y-m-d') . " - " . date("Y-m-d"),
                'presetDropdown' => true,
                'pluginOptions' => [
                    'opens' => 'left',
                    'alwaysShowCalendars' => true,
                    'timePickerIncrement' => 30,
                    'locale' => [
                        'format' => 'd/m/Y',
                    ],
                    'maxDate' => date('d/m/Y'),
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
        <?php if (isset($_GET['havingOrder'])) {
            $model->havingOrder = $_GET['havingOrder'];
        } ?>
        <div class="col-md-3">
            <?php echo $form->field($model, 'havingOrder')->dropDownList(
                [
                    'true' => 'Có đơn hàng',
                    'false' => 'Không đơn hàng',
                    null => 'Tất cả',
                ]
                // , ['prompt' => 'Chọn loại']
            );
            ?>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                &nbsp;&nbsp;&nbsp;
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>