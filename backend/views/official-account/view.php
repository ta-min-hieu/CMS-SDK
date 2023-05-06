<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model backend\models\Queue */

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Official Account'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="queue-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'appname',
            'hostname',
            'serviceID',
            [
                'attribute' => 'thumb',
                'value' => function ($model) {
                    return $model->getAvatarUrl();
                },
                'format' => ['image', ['height' => '80', 'onerror' => "this.src='" . Yii::$app->params['no_image'] . "';"]],
                'filter' => false,
                'headerOptions' => ['style' => 'width:120px'],
            ],
            'created_at',
        ],
    ]) ?>

</div>

