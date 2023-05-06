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
            <?= $form->field($model, 'type_box_chat')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Major::find()->all(), 'id_mission', 'mission_name'),
                'size' => Select2::MEDIUM,
                'options' => [
                    'placeholder' => Yii::t('backend', 'Choose a mission'),
                    'id' => 'id_mission'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'addon' => [
                    'prepend' => [
                        'content' => '<i class="glyphicon glyphicon-search"></i>'
                    ]
                ],
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'description') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'title_question') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'greeting') ?>
        </div>
        <?php // echo $form->field($model, 'created_at') 
        ?>

        <?php // echo $form->field($model, 'updated_at') 
        ?>
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