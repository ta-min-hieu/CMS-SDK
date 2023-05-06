<?php
use yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use backend\models\Customer;
use backend\models\Department;
use backend\models\Queue;
use yii\helpers\Html;
use yii\widgets\Pjax;
use backend\models\Staff;
use backend\models\StaffQueue;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Staff');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <div class="col-md-12">
                    <?= Html::a(Yii::t('backend', 'Create {modelClass}', [
                        'modelClass' => 'Staff',
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
                            'id_staff',
                            // [
                            //     'attribute' => 'thumb',
                            //     'format' => 'raw',
                            //     'value' => function ($object) {
                            //         $deparment=Queue::find($object->getAvatarUrl());
                            //         return ($deparment)?$deparment->department_name:null;
                            //     }
                            // ],
                            [
                                'attribute'
                                => 'thumb',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $staffqueue = StaffQueue::find()
                                        ->where(['agent_name' => $object->username])
                                        ->one();
                                    if (isset($staffqueue)) {
                                        $queue = Queue::find()
                                            ->where(['queue_name' => $staffqueue['queue_name']])
                                            ->one();
                                        return ($queue) ? $queue->thumb : null;
                                    } else return null;
                                },
                                'format' => ['image', ['height' => '80', 'onerror' => "this.src='" . Yii::$app->params['no_image'] . "';"]],
                                'filter' => false,
                                'headerOptions' => ['style' => 'width:120px'],
                            ],
                            'username',
                            'staff_name',
                            'phone_number',
                            [
                                'attribute' => 'id_department',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $deparment=Department::findOne($object->id_department);
                                    return ($deparment)?$deparment->department_name:null;
                                }
                            ],
                            [
                                'attribute' => 'sUserID',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $deparment=Customer::find($object->username)
                                    ->where(['username' => $object->username])
                                    ->one();
                                    return ($deparment)?$deparment->sUserID:null;
                                }
                            ],
                            [
                                'attribute' => 'state',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $deparment=Customer::find($object->username)
                                    ->where(['username' => $object->username])
                                    ->one();
                                    return ($deparment)?Yii::t('backend',Yii::$app->params['status2'][$deparment->state]):null;
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['style' => 'width: 110px;', 'class' => 'head-actions'],
                                'contentOptions' => ['class' => 'row-actions'],
                                'template' => '{view} {update} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open" title="View"></span>', ['view', 'id' => $model->id_staff], [
                                            'class' => '',
                                            'data-target' => '#detail-modal',
                                            'data-toggle' => "modal"
                                        ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        if ($model->getDispnamequeuestaff($model->id_staff)) {
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-remove icon-is_active" style="font-size: 15px" title="Inactive"></span>',
                                                ['delete', 'id' => $model->id_staff],
                                                [
                                                    'class' => 'btn_action',
                                                    'data' => [
                                                        'confirm' => Yii::t('backend', 'Bạn có muốn inactive và xóa nhân viên này khỏi queue của phòng ban: '.$model->getDispnamequeuestaff($model->id_staff)["id_department"].' - '.$model->getDispnamequeuestaff($model->id_staff)["department_name"].'?'),
                                                        'method' => 'post',
                                                    ],
                                                    'style' => 'margin-left : 5%'
                                                ]
                                            );
                                        }
                                        else{
                                            // var_dump($model->id_staff);die();
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-remove icon-is_active" style="font-size: 15px" title="Inactive"></span>',
                                                ['delete', 'id' => $model->id_staff],
                                                [
                                                    'class' => 'btn_action',
                                                    'data' => [
                                                        'confirm' => Yii::t('backend', 'Are you sure you want to change the status ?'),
                                                        'method' => 'post',
                                                    ],
                                                    'style' => 'margin-left : 5%'
                                                ]
                                            );
                                        }
                                    }
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