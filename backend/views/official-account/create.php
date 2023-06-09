<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OfficialAccount */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Official Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="official-account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
