<?php
use yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use backend\models\Department;
use backend\models\Major;
use backend\models\Queue;
use backend\models\User;
use yii\helpers\Html;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Queue');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <div class="col-md-12">
                    <?= Html::a(Yii::t('backend', 'Create {modelClass}', [
                        'modelClass' => 'Queue',
                    ]),
                        ['create'], ['class' => 'btn btn-info   btn-sm']) ?>

                    <?= Html::a(Yii::t('backend', 'Edit Working Time', [
                        'modelClass' => 'Queue',
                    ]),
                        ['edit-working-time'], ['class' => 'btn btn-info   btn-sm']) ?>
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
                            [
                                'attribute' => 'thumb',
                                'value' => function ($model) {
                                    return $model->getAvatarUrl();
                                },
                                'format' => ['image', ['height' => '80', 'onerror' => "this.src='" . Yii::$app->params['no_image'] . "';"]],
                                'filter' => false,
                                'headerOptions' => ['style' => 'width:120px'],
                            ],
                            'id_department',
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
                            [
                                'attribute' => 'department_name',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $deparment=Department::findOne($object->id_department);
                                    return ($deparment)?$deparment->department_name:null;
                                }
                            ],
                            [
                                'attribute' => 'id_mission',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $deparment=Major::findOne($object->id_mission);
                                    return ($deparment)?$deparment->mission_name:null;
                                }
                            ],
                            [
                                'attribute' => 'province',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $deparment=Department::findOne($object->id_department);
                                    return ($deparment)?$deparment->province:null;
                                }
                            ],
                            'type_queue',
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    return Yii::t('backend',Yii::$app->params['statusQueue'][$object->status]);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['style' => 'width: 110px;', 'class' => 'head-actions'],
                                'contentOptions' => ['class' => 'row-actions'],
                                'template' => '{staffs} {view} {update} {delete}',
                                // 'visibleButtons'=>[
                                //     'staffs'=> function ($object) {
                                //         $deparment=Department::findOne($object->id_department);
                                //         $deparment1 = ($deparment)?$deparment->id_province:null;
                                //         // var_dump($object->userProvince);
                                //         // die();
                                //         return ($object->userProvince == null || $deparment1 == $object->userProvince) ? 1 : 0;
                                //     },
                                //     'view'=> function ($object) {
                                //         $deparment=Department::findOne($object->id_department);
                                //         $deparment1 = ($deparment)?$deparment->id_province:null;
                                //         // var_dump($deparment1);
                                //         // die();
                                //         return ($object->userProvince == null || $deparment1 == $object->userProvince) ? 1 : 0;
                                //     },
                                //     'update' => function ($object) {
                                //         $deparment=Department::findOne($object->id_department);
                                //         $deparment1 = ($deparment)?$deparment->id_province:null;
                                //         // var_dump($deparment1);
                                //         // die();
                                //         return ($object->userProvince == null || $deparment1 == $object->userProvince) ? 1 : 0;
                                //     },
                                //      'delete' => function ($object) {
                                //         $deparment=Department::findOne($object->id_department);
                                //         $deparment1 = ($deparment)?$deparment->id_province:null;
                                //         // var_dump($deparment1);
                                //         // die();
                                //         return ($object->userProvince == null || $deparment1 == $object->userProvince) ? 1 : 0;
                                //     },
                                // ],
                                'buttons' => [
                                    'staffs' => function ($url, $model) {
                                        return Html::a('<i class="icon-user-following" aria-hidden="true" title="List Staff"></i>',
                                            ['staff', 'id' => $model->id], [
                                            'class' => '',
                                                'data-pjax' => 0,
                                        ]);
                                    },
                                    'view' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>', ['view', 'id' => $model->id], [
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