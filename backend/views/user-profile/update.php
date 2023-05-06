<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VtUser */

$this->title = Yii::t('backend', 'Chỉnh sửa {modelClass}: ', [
        'modelClass' => 'Tài khoản',
    ]) . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Tài khoản'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update information');
?>
<div class="user-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
