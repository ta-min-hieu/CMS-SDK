<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Queue */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Queues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update {name}', [
    'name' => $model->id,
]);
?>
<div class="queue-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
