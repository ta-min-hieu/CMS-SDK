<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\QuickQuestionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default quick-question-search">
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'id_question') ?>
        </div>
        <div class="col-md-3">
        <?= $form->field($model, 'id_box_chat')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Major::find()->all(), 'id_mission', 'mission_name'),
                    'size' => Select2::MEDIUM,
                    'options' => [
                        'placeholder' => Yii::t('backend', 'Choose a mission'),
                        'id_box_chat' => 'id_mission'
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
            <?= $form->field($model, 'question') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'answer') ?>
        </div>
        <div class="col-md-3">
        <?php echo
            $form->field($model, 'type_question')->dropDownList(
                \common\helpers\Helpers::commonQuickQuestionStatusArr(),
                ['prompt' => Yii::t('backend', 'All')]
            );;

            ?>
        </div>
        <?php // echo $form->field($model, 'created_at') 
        ?>

        <?php // echo $form->field($model, 'updated_at') 
        ?>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>