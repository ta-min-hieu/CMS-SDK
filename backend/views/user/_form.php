<?php

use yii\bootstrap\Html;
use awesome\backend\form\AwsActiveForm;
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
                <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'new_password')->passwordInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 're_password')->passwordInput() ?>
            </div>
            
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'fullname')->textInput(['maxlength' => 255]);
                echo $form->field($model, 'msisdn')->textInput(['maxlength' => 15]);
                ?>
                <br/>
                <?php echo $form->field($model, 'status')->checkbox([
                    'label' => Yii::t('backend', 'Active/Inactive')
                ]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'address')->textarea(['maxlength' => 255, 'rows' => 4]); ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'id_province')->textInput(['maxlength' => 45]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'user_type')->radioList(\backend\models\User::getUserTypeArr(), [
                    'id' => 'user-type'
                ]) ?>
<!--            </div>-->
<!--            <div class="col-md-6" id="user-branch">-->
                <?php
//                if (Yii::$app->user->identity->user_type == 'branch') {
//                    $branchList = Branch::find()->where(['id' => Yii::$app->user->identity->branch_id])->all();
//                } else {
//                    $branchList = Branch::find()->all();
//                }
                ?>

<!--            </div>-->
<!--            <div class="col-md-6" id="user-partner">-->
                <?php
//                if (($model->branch_id)) {
//                    $partners = Partner::getPartnersByBranch($model->branch_id);
//                } else if (Yii::$app->user->identity->user_type == 'branch') {
//                    $partners = Partner::getPartnersByBranch(Yii::$app->user->identity->branch_id);
//                } else {
//                    $partners = Partner::find()->all();
//                }
                ?>
                <?php
//                echo $form->field($model, 'partner_id')->widget(DepDrop::classname(), [
//                    'type' => DepDrop::TYPE_SELECT2,
//                    'select2Options' => ['pluginOptions' => [
//                        'allowClear' => false,
//                        'multiple' => false,
//                        'placeholder' => Yii::t('backend', 'Choose a partner'),
//                    ]],
//                    'data' => ArrayHelper::map($partners, 'id', 'name'),
//                    'options'=>['id'=>'partner-id'],
//                    'pluginOptions'=>[
//                        'depends'=>['branch-id'],
//
//                        'url' => \yii\helpers\Url::to(['partner/get-partners-by-branch', 'selected' => $model->partner_id]),
//                    ]
//                ]);
                ?>
<!--            </div>-->
            <div class="clearfix"></div>

        </div>
    </div>

    <div class="portlet-title">
        <a href="<?= \yii\helpers\Url::to(['index']); ?>" class="btn btn-default btn-sm">
            <i class="fa fa-angle-left"></i> <?= Yii::t('backend', 'Back') ?>                </a>
        &nbsp;&nbsp;&nbsp;
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-transparent green  btn-sm']) ?>

    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<< EOT_JS_CODE
//$(document).ready(function() {
//    function showPartnerBranch(val) {
//        if(val == 'branch') {
//           $('#user-branch').show();
//           $('#user-partner').hide();
//        } else if (val == 'partner') {
//           $('#user-partner').show();
//           $('#user-branch').hide();
//        } else {
//            $('#user-branch').hide();
//            $('#user-partner').hide();
//        }
//    }
//
//    var radios = $('input:radio[name="User[user_type]"]');
//    if(radios.is(':checked') === false) {
//        $('#user-branch').hide();
//        $('#user-partner').hide();
//    } else {
//        showPartnerBranch($('input:radio[name="User[user_type]"]:checked').val());
//    }
//
//    radios.change(function(){
//        showPartnerBranch($(this).val());
//
//    });
//
//});

EOT_JS_CODE
);
?>