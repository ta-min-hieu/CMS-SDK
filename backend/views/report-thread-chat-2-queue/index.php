<?php


use yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use backend\models\Department;
use backend\models\BoxChat;
use backend\models\Major;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Report Thread Chat To Queue');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php
                echo $this->render('_search', ['model' => $searchModel]); 
                ?>
            </div>
            <?php if (isset($arrHeader['thumb'])) { ?>
                <img src="<?= $arrHeader['thumb'] ?>" alt="ảnh Queue" width="50" height="50"><br>
                <p style="font-size: 20px;"><b><?= $arrHeader['disp_name'] ?><br>
                    <?= $arrHeader['type'] ?>
            </b></p><br>
            <?php } else if (isset($arrHeader['total'])) { ?>
                <p style="font-size: 20px;"><b><?= $arrHeader['type'] ?><br>
                    <?= $arrHeader['total'] ?>
            </b></p><br>
            <?php } ?>
            <div class="portlet-body">
                <div class="table-container">
                    <?php
                    // Pjax::begin(['formSelector' => 'form', 'enablePushState' => false, 'id' => 'mainGridPjax']);
                    ?>
                    <?= GridView::widget([
                        'dataProvider' => $arrActiveDevice,
                        // 'showHeader' => false,
                        'layout' => "{items}\n <div class='form-inline pagination page-size'>" . awesome\backend\grid\AwsPageSize::widget([
                            'options' => [
                                'class' => 'form-control  form-control-sm',
                                'style' => 'display:none;',
                                // 'headerOptions' => ['style' => 'display: none;']
                            ]
                        ]),
                        'pager' => [
                            'hideOnSinglePage' => true,
                            'options' => ['class' => 'pagination pagination-sm'],
                            'linkOptions' => ['class' => 'page-link'],
                            'activePageCssClass' => 'active',
                            'disabledPageCssClass' => 'disabled',
                            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                            'maxButtonCount' => 10,
                            'prevPageCssClass' => 'page-item',
                            'nextPageCssClass' => 'page-item',
                            'pageCssClass' => 'page-item',
                        ],
                        'columns' => [
                            [
                                'attribute' => 'missing_thread',
                                'label' => 'Tổng cuộc chat bị miss', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'missing_thread_time',
                                'label' => 'Tổng thời gian chờ cuộc chat bị miss', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'waiting_thread',
                                'label' => 'Số lượng cuộc chat đang chờ, chưa được tiếp nhận', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'processing_thread',
                                'label' => 'Số lượng cuộc chat đã được tiếp nhận, đang xử lý', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'done_thread',
                                'label' => 'Số lượng cuộc chat đã được tiếp nhận, đã xử lý xong', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'waiting_time_accepted',
                                'label' => 'Tổng thời gian chờ các cuộc chat đã được tiếp nhận (giây)', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'waiting_time',
                                'label' => 'Tổng thời gian chờ các cuộc chat chưa được tiếp nhận (giây)', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'done_chattime',
                                'label' => 'Tổng thời gian chat của các cuộc chat đã xử lý xong (giây)', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'processing_chattime',
                                'label' => 'Tổng thời gian chat của các cuộc chat vẫn đang xử lý (giây)', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'online_agent',
                                'label' => 'Số lượng nhân viên đang online', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'processing_thread_agent',
                                'label' => 'Số agent online đang hỗ trợ', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'thread_chat_count_5min',
                                'label' => 'Số lượng cuộc chat > 5 phút', // Đặt tên cho cột này là "Trạng thái"
                            ],
                        ],
                    ]); ?>

                    <?php
                    // Pjax::end();
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

<style>
    label[for="w2"] {
        display: none;
    }

    label[for="w0"] {
        display: none;
    }
</style>