<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Queue */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Queues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="queue-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
