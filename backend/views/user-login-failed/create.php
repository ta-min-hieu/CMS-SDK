<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLoginFailed */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'User Login Failed',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User Login Faileds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-login-failed-create">
    <div class="col-md-12">
        <?= $this->render('_form', [
            'model' => $model,
            'title' => $this->title
        ]) ?>
    </div>
</div>
