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
                <?= $form->field($model, 'id_department')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'department_name')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'address')->textInput(['maxlength' => 200]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'village')->textInput(['maxlength' => 200]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'district')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="col-md-6">
                <?php if(User::findOne(\Yii::$app->user->id)->id_province == null){?>
                <?php
                    foreach(Yii::$app->params['province'] as $key => $value){
                        $a[$key] = $key." - ".$value;
                    }
                ?>
                <?= $form->field($model, 'id_province')->dropDownList(
                    $a,
                ); ?>
                <?php } else{
                    foreach(Yii::$app->params['province'] as $key => $value){
                        if(strcmp(User::findOne(\Yii::$app->user->id)->id_province, $key) == false)
                            $a[$key] = $key." - ".$value;
                    }
                    // var_dump(Department::findOne(User::findOne(\Yii::$app->user->id)->id_province)."       ".trim(User::findOne(\Yii::$app->user->id)->id_province));
                    // die();
                    // $a[User::findOne(\Yii::$app->user->id)->id_province] = User::findOne(\Yii::$app->user->id)->id_province." - ".Department::findOne(User::findOne(\Yii::$app->user->id)->id_province)->province;
                ?>
                    <?= $form->field($model, 'id_province')->dropDownList(
                    $a,
                ); ?>
            <?php }?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'management_id')->textInput(['maxlength' => 100]) ?>
            </div>
            
        </div>
        <div class="portlet-title">
            <a href="<?= \yii\helpers\Url::to(['index']); ?>" class="btn btn-default btn-sm">
                <i class="fa fa-angle-left"></i> <?= Yii::t('backend', 'Back') ?> </a>
            &nbsp;&nbsp;&nbsp;
            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-transparent green  btn-sm']) ?>

        </div>
    </div>


</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(
    <<< EOT_JS_CODE
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