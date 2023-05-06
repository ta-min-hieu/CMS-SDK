<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\QuickQuestion */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Quick Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update {name}', [
    'name' => $model->id_question,
]);
?>
<div class="quick-question-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
