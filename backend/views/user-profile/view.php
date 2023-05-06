<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\VtUser */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Tài khoản'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vt-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'fullname',
            'email:email',
            'address:ntext',
            [
                'label' => 'status',
                'value' => ($model->status == \backend\models\User::STATUS_ACTIVE) ? Yii::t('backend', 'Actived') : Yii::t('backend', 'Inactive'),
            ],
            [
                'attribute' => 'created_at',
                'value' => date('H:i:s d/m/Y', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('H:i:s d/m/Y', $model->updated_at),
            ],
        ],
    ])
    ?>

</div>
