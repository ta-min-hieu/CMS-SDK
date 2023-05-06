<?php


use \yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SystemSettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'System Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row system-setting-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <div class="caption">
                    <i class="icon-layers "></i>
                    <span class="caption-subject  sbold uppercase">
                        <?= AwsBaseHtml::encode($this->title) ?>
                    </span>
                </div>
                <div class="actions">
                    <?= Html::a(Yii::t('backend', 'Create {modelClass}', [
                        'modelClass' => Yii::t('backend', 'System Setting'),
                    ]),
                        ['create'], ['class' => 'btn btn-info  btn-sm']) ?>
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-container">
                    <?php
                    Pjax::begin(['formSelector' => 'form', 'enablePushState' => false, 'id' => 'mainGridPjax']);
                    ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            //['class' => 'yii\grid\SerialColumn'],

                            [
                                'attribute' => 'id',
                                'filter' => '',
                            ],
                            'config_key',
                            'config_value:ntext',
                            'description:ntext',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['style' => 'width: 100px;'],
                                'contentOptions' => ['class' => 'row-actions'],
                                'template' => '{view} {update}',
                            ],
                        ],
                    ]); ?>

                    <?php
                    Pjax::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
