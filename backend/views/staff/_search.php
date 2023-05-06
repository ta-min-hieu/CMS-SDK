<?php

use backend\models\Customer;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;
use backend\models\Department;

/* @var $this yii\web\View */
/* @var $model backend\models\StaffSearch */
/* @var $form yii\widgets\ActiveForm */
$department = Department::findOne($model->id_department);
$department1 = Customer::findOne($model->sUserID);
?>

<div class="panel panel-default staff-search">
    <div class="panel-body row">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'id_staff') ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'username') ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'phone_number') ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'position') ?>

        </div>
        <div class="col-md-3">
            <?php echo
            $form->field($model, 'status')->dropDownList(
                \common\helpers\Helpers::commonStaffStatusArr(),
                ['prompt' => Yii::t('backend', 'All')]
            );;

            ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'id_department')->widget(Select2::classname(), [
                'initValueText' => $department ? $department->id_department : '',
                'options' => ['placeholder' => Yii::t('backend', 'Find department')],
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
                        'url' => \yii\helpers\Url::toRoute(['staff/ajax-search']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term }; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }')
                ]
            ])->label(Yii::t('backend', 'Department')); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'sUserID')->widget(Select2::classname(), [
                'initValueText' => $department1 ? $department1->sUserID : '',
                'options' => ['placeholder' => Yii::t('backend', 'Find SUser ID or Staff Name')],
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
                        'url' => \yii\helpers\Url::toRoute(['staff/ajax-search-a']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term }; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }')
                ]
            ])->label(Yii::t('backend', 'SUser ID - Staff Name')); ?>
        </div>
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