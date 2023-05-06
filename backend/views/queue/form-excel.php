<?php
use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
use backend\models\OfficialAccount;
use \yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $title string */
/* @var $form AwsActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="portlet light portlet-fit portlet-form bordered user-form">
    <div class="portlet-body">
        <div class="form-body row">
            <div class="col-md-6 excel">
                <?= $form->field($model, 'excel')->fileInput() ?>
                <?php if ($model->excel) : ?>
                    <a>
                        <?= $model->getExcelPathUrl(); ?>
                        Your browser does not support the audio element.
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="portlet-title">
    <a href="<?= \yii\helpers\Url::to(['/queue/staff?id='.$model1->id]); ?>" class="btn btn-default btn-sm">
        <i class="fa fa-angle-left"></i> <?= Yii::t('backend', 'Back') ?> </a>
    &nbsp;&nbsp;&nbsp;
    <?= Html::submitButton(Yii::t('backend', 'OK'), ['class' => 'btn btn-transparent green btn-sm']) ?>
</div>
</div>
<?php ActiveForm::end(); ?>