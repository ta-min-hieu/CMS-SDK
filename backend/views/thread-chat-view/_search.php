<?php

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
            <?= $form->field($model, 'queue_name')->widget(Select2::classname(), [
                'initValueText' => '',
                'options' => ['placeholder' => Yii::t('backend', 'Tìm theo id hoặc tên queue')],
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
                        'url' => \yii\helpers\Url::toRoute(['thread-chat-view/ajax-search']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term }; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }')
                ]
            ])->label(Yii::t('backend', 'Queue')); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->field($model, 'quy')->label('Quý')->dropDownList(
                [
                    '01-01|03-31' => 'Quý 1',
                    '04-01|06-30' => 'Quý 2',
                    '07-01|09-30' => 'Quý 3',
                    '10-01|12-31' => 'Quý 4',
                ],
                ['prompt' => 'Chọn Quý']
            );
            ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->field($model, 'year')->widget(kartik\widgets\DatePicker::className(), [
                'options' => [
                    'class' => 'form-control input-sm',
                    'value' => (!isset($_GET['year']) || $_GET['year'] == '') ? date('Y') : $_GET['year'],
                    'readonly' => true,
                    'placeholder' => 'Chọn năm'
                ],
                'pluginOptions' => [
                    'format' => 'yyyy',
                    'startView' => 'decade',
                    'minViewMode' => 'years',
                    'endDate' => date('Y')
                ]
            ]); ?>
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