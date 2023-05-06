<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLocked */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'User Locked',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User Lockeds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-locked-create">
    <div class="col-md-12">
        <?= $this->render('_form', [
            'model' => $model,
            'title' => $this->title
        ]) ?>
    </div>
</div>
