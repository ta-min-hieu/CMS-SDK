<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use backend\models\Department;
$department = Department::findOne($model->id_department);
/* @var $this yii\web\View */
/* @var $model backend\models\SongSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="panel panel-default staff-search">
    <div class="panel-body row">

        <?php $form = ActiveForm::begin([
            'action' => ['/staff/list-ajax', 'ctype' => $ctype, 'ctype_id' => $ctypeId],
            'method' => 'get',
            'id' => 'staff-search-ajax',
        ]); ?>



        <!--        <div class="col-md-3">-->
        <p>
        <div class="col-md-6">
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
        <p>
        <div class="col-md-6"><?= $form->field($model, 'search_string') ?></div>

        </p>
        <!--        </div>-->


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