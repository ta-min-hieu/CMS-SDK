<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use backend\models\Department;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\QueueSearch */
/* @var $form yii\widgets\ActiveForm */
$department = Department::findOne($model->id_department);
?>

<div class="panel panel-default queue-search">
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'id') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'queue_name') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'hostname') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'disp_name') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'id_department')->widget(Select2::classname(), [
                'initValueText' => $department ? $department->id_department : '',
                'options' => ['placeholder' => Yii::t('backend', 'Tìm theo id hoặc tên department')],
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
                        'url' => \yii\helpers\Url::toRoute(['queue/ajax-search']),
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
            <?php echo
            $form->field($model, 'type_queue')->dropDownList(
                \common\helpers\Helpers::commonQueueStatusArr(),
                ['prompt' => Yii::t('backend', 'All')]
            );;

            ?>

        </div>
        <div class="col-md-3">
                <?= $form->field($model, 'id_mission')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Major::find()->all(), 'id_mission', 'mission_name'),
                    'size' => Select2::MEDIUM,
                    'options' => [
                        'placeholder' => Yii::t('backend', 'Choose a mission'),
                        'id_mission' => 'id_mission'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'addon' => [
                        'prepend' => [
                            'content' => '<i class="glyphicon glyphicon-search"></i>'
                        ]
                    ],
                ]); ?>
            </div>
            <?php if(User::findOne(\Yii::$app->user->id)->id_province == null){ ?>
            <div class="col-md-3">
            <?= $form->field($model, 'id_province')->widget(Select2::classname(), [
                'initValueText' => $department ? $department->id_department : '',
                'options' => ['placeholder' => Yii::t('backend', 'Find province')],
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
                        'url' => \yii\helpers\Url::toRoute(['queue/ajax-search-province']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term }; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }')
                ]
            ])->label(Yii::t('backend', 'Province')); ?>
        </div>
        <?php }?>
        <?php // echo $form->field($model, 'next_queue_id') 
        ?>

        <?php // echo $form->field($model, 'waiting_time') 
        ?>

        <?php // echo $form->field($model, 'id_department') 
        ?>

        <?php // echo $form->field($model, 'des') 
        ?>

        <?php // echo $form->field($model, 'created_at') 
        ?>

        <?php // echo $form->field($model, 'updated_at') 
        ?>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>