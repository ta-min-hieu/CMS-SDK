<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Staff'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update {name}', [
    'name' => $model->id_staff,
]);
?>
<div class="staff-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
