<?php

use backend\models\OfficialAccount;
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
    <?php 
    if($model->type == "text"){
        ?>
       <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'text',
            'id_official_account',
            [
                'attribute' => 'excel',
                'format' => 'raw',
                'value' => function ($object) {
                    $contract = null;
                    for ($i = 0; $i < count(explode("|", $object->excel)); $i++) {
                        return $contract .= Html::a('<span class="glyphicon glyphicon-download"></span>' . 'Dowload', explode("|", $object->excel)[$i], ['data-pjax' => 0, 'target' => '_blank', 'rel' => "noreferrer noopener", 'download' => true]);
                    }
                    return $contract;
                }
            ],
            'time',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($object) {
                    return Yii::t('backend',Yii::$app->params['status5'][$object->status]);
                }
            ],
            'created_at',
        ],
    ]) ?>
        <?php 
    }
    ?>
    <?php 
    if($model->type == "video"){
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'type',
                [
                    'attribute' => 'video',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return "<video width='320' height='240' controls>
                        <source src='" . $model->getMediaPathUrl() . "' type='video/mp4'>
                        Your browser does not support the audio element.
                    </video>";
                    },
                ],
                [
                    'attribute' => 'id_official_account',
                    'format' => 'raw',
                    'value' => function ($object) {
                        $deparment=OfficialAccount::findOne($object->id_official_account);
                        return ($deparment)?$deparment->appname:null;
                    }
                ],
                [
                    'attribute' => 'excel',
                    'format' => 'raw',
                    'value' => function ($object) {
                        $contract = null;
                        for ($i = 0; $i < count(explode("|", $object->excel)); $i++) {
                            return $contract .= Html::a('<span class="glyphicon glyphicon-download"></span>' . 'Dowload', explode("|", $object->excel)[$i], ['data-pjax' => 0, 'target' => '_blank', 'rel' => "noreferrer noopener", 'download' => true]);
                        }
                        return $contract;
                    }
                ],
                'time',
                'created_at',
            ],
        ]);
    }
    ?>
    <?php 
    if($model->type == "image"){
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'type',
                [
                    'attribute' => 'image',
                    'value' => function ($model) {
                        return $model->getAvatarUrl();
                    },
                    'format' => ['image', ['height' => '80', 'onerror' => "this.src='" . Yii::$app->params['no_image'] . "';"]],
                    'filter' => false,
                    'headerOptions' => ['style' => 'width:120px'],
                ],
                'id_official_account',
                [
                    'attribute' => 'excel',
                    'format' => 'raw',
                    'value' => function ($object) {
                        $contract = null;
                        for ($i = 0; $i < count(explode("|", $object->excel)); $i++) {
                            return $contract .= Html::a('<span class="glyphicon glyphicon-download"></span>' . 'Dowload', explode("|", $object->excel)[$i], ['data-pjax' => 0, 'target' => '_blank', 'rel' => "noreferrer noopener", 'download' => true]);
                        }
                        return $contract;
                    }
                ],
                'time',
                'created_at',
            ],
        ]);
    }
    ?>
    

</div>