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
            'id',
            'queue_name',
            'hostname',
            [
                'attribute' => 'thumb',
                'value' => function ($model) {
                    return $model->getAvatarUrl();
                },
                'format' => ['image', ['height' => '80', 'onerror' => "this.src='" . Yii::$app->params['no_image'] . "';"]],
                'filter' => false,
                'headerOptions' => ['style' => 'width:120px'],
            ],
            'disp_name',
            [
                'attribute' => 'next_queue_id',
                'format' => 'raw',
                'value' => function ($object) {
                    $deparment=Queue::findOne($object->next_queue_id);
                    return ($deparment)?$deparment->disp_name:null;
                }
            ],
            'waiting_time',
            'id_department',
            [
                'attribute' => 'department_name',
                'format' => 'raw',
                'value' => function ($object) {
                    $deparment=Department::findOne($object->id_department);
                    return ($deparment)?$deparment->department_name:null;
                }
            ],
            'des',
            'start_time',
            'end_time',
            'created_at',
        ],
    ]) ?>

</div>
