<?php


use yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use backend\models\Department;
use backend\models\User;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Admin'), 'url' => '#'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <div class="col-md-12">
                    <?= Html::a(Yii::t('backend', 'Create {modelClass}', [
                        'modelClass' => 'User',
                    ]),
                        ['create'], ['class' => 'btn btn-info   btn-sm']) ?>
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-container">
                    <?php
                    Pjax::begin(['formSelector' => 'form', 'enablePushState' => false, 'id' => 'mainGridPjax']);
                    ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'filterSelector' => 'select[name="per-page"]',
                        'layout' => "{items}\n <div class='form-inline pagination page-size'>" . awesome\backend\grid\AwsPageSize::widget([
                                'options' => [
                                    'class' => 'form-control  form-control-sm',
                                ]]) . '</div> <div class="col-md-6">{pager}</div> <div class="pagination col-md-3 text-right total-count">' . Yii::t('backend', 'Tổng số') . ': <b>' . number_format($dataProvider->getTotalCount()) . '</b> ' . Yii::t('backend', 'bản ghi') . '</div>',

                        'columns' => [
                            'id',
                            'username',
                            [
                                'attribute' => 'user_type',
                                'content' => function($model) {
                                    return $model->getUserTypeName();
                                }
                            ],
//                            [
//                                'attribute' => 'branch_id',
//                                'value' => function($model) {
//                                    return ($model->branch)? $model->branch->name: null;
//                                }
//                            ],
//                            [
//                                'attribute' => 'partner_id',
//                                'value' => function($model) {
//                                    return ($model->partner)? $model->partner->name: null;
//                                }
//                            ],
                            'msisdn',
                            'email:email',
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $class = ($object->status == 1) ? 'glyphicon-ok' : 'glyphicon-remove';
                                    return '<span class="glyphicon ' . $class . ' icon-is_active"></span>';
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'raw', //raw, html
                                'content' => function($data) {
                                    return date('H:i:s d/m/Y', $data->created_at);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['style' => 'width: 110px;', 'class' => 'head-actions'],
                                'contentOptions' => ['class' => 'row-actions'],
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id], [
                                            'class' => '',
                                            'data-target' => '#detail-modal',
                                            'data-toggle' => "modal"
                                        ]);
                                    },
                                ]
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
<!-- Modal -->
<div id="detail-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

        </div>

    </div>
</div>