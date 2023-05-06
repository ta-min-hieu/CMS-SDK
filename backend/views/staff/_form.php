<?php

use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
use \yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;
use backend\models\Department;
//use \backend\models\Branch;
//use \backend\models\Partner;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $title string */
/* @var $form AwsActiveForm */

$department = Department::findOne($model->id_department);
?>

<?php $form = ActiveForm::begin(); ?>

<div class="portlet light portlet-fit portlet-form bordered user-form">

    <div class="portlet-body">
        <div class="form-body row">
            <div class="col-md-6">
                <?= $form->field($model, 'sUserID')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'staff_name')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="clearfix"></div>
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
            <div class="col-md-6">
                <?php echo
                $form->field($model, 'position')->dropDownList(
                    Yii::$app->params['position'],
                );;

                ?>

            </div>
            <div class="clearfix"></div>
            <?php if (!$model->isNewRecord) { ?>
                <div class="col-md-6">
                    <?= $form->field($model, 'phone_number')->hiddenInput(['class' => 'form-control', 'maxlength' => 20,])->label(false) ?>
                </div>
            <?php } else { ?>
                <div class="col-md-6">
                    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => 20]) ?>
                </div>
            <?php } ?>
            <div class="col-md-6">
                <?= $form->field($model, 'status')->checkbox() ?>
            </div>
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
<?php \common\components\slim\SlimAsset::register($this); ?>