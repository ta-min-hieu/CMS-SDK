<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OfficialAccount */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Official Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update {name}', [
    'name' => $model->id,
]);
?>
<div class="official-account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
