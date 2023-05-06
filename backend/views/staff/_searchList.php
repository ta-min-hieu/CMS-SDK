<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\StaffSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default staff-search">
    <div class="panel-body row">

        <?php $form = ActiveForm::begin([
            'action' => ['index-list-staff'],
            'method' => 'get',
        ]); ?>

        <div class="col-md-3">
            <?php echo
            $form->field($model, 'status')->dropDownList(
                \common\helpers\Helpers::commonStaffStatusArr(),
                ['prompt' => Yii::t('backend', 'All')]
            );;

            ?>

        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                &nbsp;&nbsp;&nbsp;
                <?= Html::a(Yii::t('backend', 'Reset'), ['index-list-staff'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>