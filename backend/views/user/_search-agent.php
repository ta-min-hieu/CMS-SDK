<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default user-search">
    <div class="panel-body row">

        <?php $form = ActiveForm::begin([
            'action' => ['manage-agent'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'username') ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'support_status')->dropDownList(
                \backend\models\User::getAllSupportStatus(),
                ['prompt' => Yii::t('backend', 'All')]
            );;

            ?>

        </div>


        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                &nbsp;&nbsp;&nbsp;
                <?= Html::a(Yii::t('backend', 'Reset'), ['manage-agent'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
