<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\BoxChatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default box-chat-search">
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'phonenumber') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'fullname') ?>
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