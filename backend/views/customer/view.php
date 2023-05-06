<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Department;
use backend\models\Queue;
/* @var $this yii\web\View */
/* @var $model backend\models\Queue */

?>
<div class="queue-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sUserID',
            'serviceID',
            'username',
            [
                'attribute' => 'avatar',
                'value' => function ($model) {
                    return $model->getAvatarUrl();
                },
                'format' => ['image', ['height' => '80', 'onerror' => "this.src='" . Yii::$app->params['no_image'] . "';"]],
                'filter' => false,
                'headerOptions' => ['style' => 'width:120px'],
            ],
            'phonenumber',
            'country',
            'birthday',
            'gender',
            'fullname',
            'pushid',
            'app_provision',
            'app_revision',
            'device_os_type',
            'device_os_version',
            'device_id',
            'app_id',
            'type_user',
            [
                'attribute' => 'id_province',
                'format' => 'raw',
                'value' => function ($object) {
                    return Yii::t('backend', Yii::$app->params['province'][$object->id_province]);
                }
            ],
            [
                'attribute' => 'state',
                'format' => 'raw',
                'value' => function ($object) {
                    return Yii::t('backend', Yii::$app->params['status2'][$object->state]);
                }
            ],
            'created_at',
        ],
    ]) ?>

</div>