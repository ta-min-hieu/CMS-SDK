<?php

use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
use backend\models\Department;
use backend\models\User;
use \yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
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
                <?= $form->field($model, 'sUserID')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'phonenumber')->textInput(['maxlength' => 20]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'country')->textInput(['maxlength' => 50]) ?>
            </div>
            <div class="col-md-6">
            <?=
                $form->field($model, 'birthday', [
                    'template' => '{label}{input}{error}{hint}',
                ])->widget(\kartik\widgets\DatePicker::classname(), [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);
                ?>
                </div>
                <div class="clearfix"></div>
            <div class="col-md-6">
                <?php echo
                $form->field($model, 'gender')->dropDownList(
                    [
                        '0' => Yii::t('backend', 'Male'),
                        '1' => Yii::t('backend', 'Female'),
                        '2' => Yii::t('backend', 'Other'),
                    ]
                );

                ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'fullname')->textInput(['maxlength' => 200]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'app_id')->textInput(['maxlength' => 50]) ?>
            </div>
            <?php if(User::findOne(\Yii::$app->user->id)->id_province == null){?>
            <div class="col-md-6">
                <?php echo
                $form->field($model, 'id_province')->dropDownList(
                    Yii::$app->params['province'],
                );
                ?>
            </div>
            <?php } else {
                $a[User::findOne(\Yii::$app->user->id)->id_province] = User::findOne(\Yii::$app->user->id)->id_province." - ".Yii::$app->params['province'][User::findOne(\Yii::$app->user->id)->id_province];
                ?>
                <div class="col-md-6">
                <?php echo
                $form->field($model, 'id_province')->dropDownList(
                    $a,
                );
                ?>
                </div>
            <?php }?>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $this->render('/common/_slim_image_field', [
                    'fieldName' => 'avatar',
                    'itemName' => 'avatar', // Vi du: adv, post, post-category de luu rieng tung folder
                    'fieldLabel' => Yii::t('backend', 'Avatar'),
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
            <div class="col-md-6">
                <?= $form->field($model, 'state')->checkbox() ?>
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