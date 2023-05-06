<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DepartmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default department-search">
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="col-md-3">
            <?= $form->field($model, 'id_department') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'department_name') ?>
        </div>


        <?php // echo $form->field($model, 'updated_at') 
        ?>

        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>