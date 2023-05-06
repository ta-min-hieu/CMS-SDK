<?php
use yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use backend\models\Customer;
use backend\models\Department;
use backend\models\Queue;
use yii\helpers\Html;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'List Staff');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Admin'), 'url' => '#'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php echo $this->render('_searchList', ['model' => $searchModel]); ?>

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
                            [
                                'attribute' => 'name_list',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $deparment=Department::findOne($object->id_department);
                                    return ($deparment)?"List staff: ".$deparment->department_name:null;
                                }
                            ],
                            [
                                'attribute' => 'don_vi',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    $deparment=Department::findOne($object->id_department);
                                    return ($deparment)?$deparment->department_name:null;
                                }
                            ],
                            [
                                'attribute' => 'chinhanh',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    return $object = "Tất cả";
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
                                'attribute' => 'list',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    return Html::a('View List', ['staff/index', 'id_department' => $object->id_department], [

                                        'data-pjax' => 0,
                                    ]);
                                }
                            ],
                            'created_at',
                            [
                                'attribute' => 'created_by',
                                'format' => 'raw',
                                'value' => function ($object) {
                                    return $object = "ctv1";
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['style' => 'width: 130px;', 'class' => 'head-actions'],
                                'contentOptions' => ['class' => 'row-actions'],
                                'template' => '{update} {delete}',
                                'buttons' => [
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