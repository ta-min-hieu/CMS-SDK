<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\models\OA */
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'List Staffs'), 'url' => ['/queue/staff?id='.$model1->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="queue-add-excel-list-staff-to-queue">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('form-excel', [
        'model' => $model,
        'model1' => $model1,
    ]) ?>
</div>