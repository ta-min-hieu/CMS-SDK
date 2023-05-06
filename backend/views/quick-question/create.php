<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\QuickQuestion */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Quick Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quick-question-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
