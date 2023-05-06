<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BoxChat */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Box Chats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update {name}', [
    'name' => $model->id_box_chat,
]);
?>
<div class="box-chat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
