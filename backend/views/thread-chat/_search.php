<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use \kartik\daterange\DateRangePicker;
use backend\models\User;
/* @var $this yii\web\View */
/* @var $model backend\models\BoxChatSearch */
/* @var $form yii\widgets\ActiveForm */
$oa = User::findOne($model->id);
?>

<div class="panel panel-default thread-chat-search">
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'thread_id') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'queue_name') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'agent') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'ojid') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'default_queue') ?>
        </div>
        <?php // echo $form->field($model, 'created_at') 
        ?>

        <?php // echo $form->field($model, 'updated_at') 
        ?>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                &nbsp;&nbsp;&nbsp;
                <?= Html::a(Yii::t('backend', 'Reset'), ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>