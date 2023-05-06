<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Department */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update {department_name}', [
    'department_name' => $model->department_name,
]);
?>
<div class="department-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
