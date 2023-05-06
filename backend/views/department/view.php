<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Department */

?>
<div class="department-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_department',
            'department_name',
            'address',
            'village',
            'district',
            'province',
            'management_id',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($object) {
                    return Yii::t('backend',Yii::$app->params['statusQueue'][$object->status]);
                }
            ],
            'created_at',
        ],
    ]) ?>

</div>
