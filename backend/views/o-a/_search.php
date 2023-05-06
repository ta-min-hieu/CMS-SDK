<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use backend\models\OfficialAccount;
/* @var $this yii\web\View */
/* @var $model backend\models\BoxChatSearch */
/* @var $form yii\widgets\ActiveForm */
$oa = OfficialAccount::findOne($model->id);
?>

<div class="panel panel-default o-a-search">
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'id') ?>
        </div>
        <div class="col-md-3">
        <?php echo
            $form->field($model, 'type')->dropDownList(
                ['image' => Yii::t('backend', 'Image'),
                'text' => Yii::t('backend', 'Text'),
                'video' => Yii::t('backend', 'Video')],
                ['prompt' => Yii::t('backend', 'All')]
            );;

            ?>
        </div>
        <div class="col-md-3">
        <?= $form->field($model, 'id_official_account')->widget(Select2::classname(), [
                    'initValueText' => $oa ? $oa->id : '',
                    'options' => ['placeholder' => Yii::t('backend', 'Find Official Account')],
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
                            'url' => \yii\helpers\Url::toRoute(['o-a/ajax-search']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term }; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(result) { return result.text; }'),
                        'templateSelection' => new JsExpression('function (result) { return result.text; }')
                    ]
                ])->label(Yii::t('backend', 'Official Account')); ?>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                &nbsp;&nbsp;&nbsp;
                <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>