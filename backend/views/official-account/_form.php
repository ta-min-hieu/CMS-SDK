<?php

use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
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
?>

<?php $form = ActiveForm::begin(); ?>

<div class="portlet light portlet-fit portlet-form bordered user-form">

    <div class="portlet-body">
        <div class="form-body row">

            <div class="col-md-6">
                <?= $form->field($model, 'appname')->textInput(['maxlength' => 200]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->render('/common/_slim_image_field', [
                    'fieldName' => 'thumb',
                    'itemName' => 'thumbnail', // Vi du: adv, post, post-category de luu rieng tung folder
                    'fieldLabel' => Yii::t('backend', 'Thumbnail'),
                    'dataMinSize' => '100,100',
                    'dataSize' => '',
                    'dataForceSize' => '',
                    'dataRatio' => '100:100',
                    'model' => $model,
                    'dataWillRemove' => 'avatarWillChange',
                    'dataWillSave' => 'avatarWillChange',
                    'helpBlock' => Yii::t('backend', 'Ratio 100 x 100px(.jpg|png|jpeg)'),
                    'accept' => 'image/jpeg,image/jpg,image/png',
                ]) ?>

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
