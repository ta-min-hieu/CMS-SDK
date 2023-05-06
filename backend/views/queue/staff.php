<?php

use backend\models\Customer;
use backend\models\Department;
use backend\models\Major;
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = $model->queue_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Queue'), 'url' => ['queue/index']];
$this->params['breadcrumbs'][] = $model->queue_name;
$deparment = Major::findOne($model->id_mission);
?>
<div class="row staff-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <h3><?= Yii::t('backend', 'Queue') ?>: <?= $model->disp_name . ' - Mission: ' . $deparment['mission_name'] ?></h3>
                <h3><?= Yii::t('backend', 'Department') ?>: <?= $model->id_department ?></h3>
                <br />
                <div class="col-md-2">
                    <?= Html::button(
                        Yii::t('backend', 'Add Staff', []),
                        [
                            'class' => 'btn btn-info btn-sm',
                            'data-target' => '#search-staff-modal',
                            'data-toggle' => "modal"
                        ]
                    ) ?>
                </div>
                <div>
                    <a class="btn btn-info btn-sm" href="/queue/add-excel-list-staff-to-queue?id=<?= $model->id ?>"><?= Yii::t('backend', 'Add List Staff') ?></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <?php
                    Pjax::begin(['formSelector' => 'form', 'enablePushState' => false, 'id' => 'mainGridPjax']);
                    ?>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <?= Yii::t('backend', 'Staff name'); ?>
                                </th>
                                <th>
                                    <?= Yii::t('backend', 'Phone number'); ?>
                                </th>
                                <th>
                                    <?= Yii::t('backend', 'Name department'); ?>
                                </th>
                                <th>
                                    <?= Yii::t('backend', 'Status'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($staffs)) : ?>
                                <?php foreach ($staffs as $staff) : ?>
                                    <tr>
                                        <td><?= $staff['staff_name']; ?></td>
                                        <td>
                                            <?= $staff['phone_number']; ?>
                                        </td>
                                        <td>
                                            <?php $deparment = Department::findOne($staff['id_department']);
                                            if ($deparment != null) {
                                                echo $deparment->department_name;
                                            } else {
                                                echo "null";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php $deparment = Customer::find($staff['username'])->where(['username' => $staff['username']])
                                    ->one();
                                            if ($deparment != null) {
                                                echo Yii::t('backend',Yii::$app->params['status2'][$deparment->state]);
                                            } else {
                                                echo "null";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-staff-remove" ahref="<?= \yii\helpers\Url::to([
                                                                                                                            'staff/remove-to-collection',
                                                                                                                            'ctype' => 'queue',
                                                                                                                            'ctype_id' => $model->queue_name,
                                                                                                                            'id_staff' => $staff['username'],
                                                                                                                        ]); ?>" id_staff="<?= $staff['id_staff'] ?>"><?= Yii::t('backend', 'Remove') ?></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?php
                    Pjax::end();
                    ?>
                </div>
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

<!-- Modal -->
<div id="search-staff-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <?php echo $this->render('/staff/_searchAjax', [
                'model' => $searchModel,
                'ctype' => 'queue',
                'ctypeId' => $model->queue_name,
            ]); ?>
            <?php
            Pjax::begin(['timeout' => 0, 'formSelector' => 'form#staff-search-ajax', 'enablePushState' => false, 'id' => 'staffGridPjax']);
            ?>

            <?php Pjax::end(); ?>

            <div class="clearfix"></div>
        </div>

    </div>
</div>
<?php
$this->registerJs(
    <<< EOT_JS_CODE
    $(document).ready(function() {
        $('#search-staff-modal').on('click', '.btn-staff-add', function() {
            var url = $(this).attr('ahref');
            var songId = $(this).attr('id_staff');

            $.get(url, function(resp) {

                if (resp.error_code == 0) {
                    $.pjax.reload({container:"#mainGridPjax",timeout:0})
                    toastr.info(resp.message);
                } else {

                }
            });
        });

        $('#mainGridPjax').on('click', '.btn-staff-remove', function() {
            var url = $(this).attr('ahref');
            var songId = $(this).attr('id_staff');

            $.get(url, function(resp) {

                if (resp.error_code == 0) {
                    $.pjax.reload({container:"#mainGridPjax",timeout:0})
                    toastr.info(resp.message);
                } else {

                }
            });
        })
    });

EOT_JS_CODE
);
?>