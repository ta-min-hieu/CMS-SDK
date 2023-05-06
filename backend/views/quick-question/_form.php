<?php

use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
use backend\models\BoxChat;
use \yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;

//use \backend\models\Branch;
//use \backend\models\Partner;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $title string */
/* @var $form AwsActiveForm */
$department = BoxChat::findOne($model->id_box_chat);
?>

<?php $form = ActiveForm::begin(); ?>

<div class="portlet light portlet-fit portlet-form bordered user-form">

    <div class="portlet-body">
        <div class="form-body row">
            <div class="col-md-6">
                <?= $form->field($model, 'question')->textInput(['maxlength' => 1000]) ?>
            </div>
            <div class="col-md-6">
                <?php echo
                $form->field($model, 'type_question')->dropDownList(
                    [
                        '1' => Yii::t('backend', 'questions with answers'),
                        '2' => Yii::t('backend', 'question has no answer'),
                    ],
                    [
                        'disabled' => !$model->isNewRecord ? true : false,
                    ]
                );

                ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
            <?= $form->field($model, 'id_box_chat')->widget(Select2::classname(), [
                'initValueText' => $department ? $department->type_box_chat : '',
                'options' => ['placeholder' => Yii::t('backend', 'Find box chat')],
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
                        'url' => \yii\helpers\Url::toRoute(['quick-question/ajax-search']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term }; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }')
                ]
            ])->label(Yii::t('backend', 'Box Chat')); ?>
        </div>
            <div class="col-md-6 answer">
                <?= $form->field($model, 'answer')->textInput(['maxlength' => 4000]) ?>
            </div>
        </div>
    </div>

    <div class="portlet-title">
        <a href="<?= \yii\helpers\Url::to(['index']); ?>" class="btn btn-default btn-sm">
            <i class="fa fa-angle-left"></i> <?= Yii::t('backend', 'Back') ?> </a>
        &nbsp;&nbsp;&nbsp;
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-transparent green  btn-sm']) ?>

    </div>
</div>

<?php ActiveForm::end(); ?>
<script>
    window.onload = function() {
        $('.answer').hide();
        if (document.getElementById("quickquestion-type_question").value == '1') {
            $('.answer').show();
        }
        else{
            $('.answer').hide();
        }
        $("#quickquestion-type_question").change(function() {
            if (document.getElementById("quickquestion-type_question").value == '1') {
                $('.answer').show();
            }
            else{
            $('.answer').hide();
            }
        })
    }
</script>