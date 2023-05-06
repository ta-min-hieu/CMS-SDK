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

$this->title = Yii::t('backend', 'Daily Report');
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
            
            <?php if (isset($arrayheader)) { ?>
                <img src="<?= $arrayheader['thumb'] ?>" alt="ảnh Queue" width="50" height="50"><br>
                <p style="font-size: 20px;"><b><?= $arrayheader['disp_name'] ?><br>
                    <?= $arrayheader['type'] ?>
            </b></p><br>
            <?php }?>
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
                                'attribute' => 'date',
                                'label' => 'Ngày thống kê', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'missing_thread',
                                'label' => 'Số cuộc chat không có ai tiếp nhận tại queue (chat miss)', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'done_thread',
                                'label' => 'Số cuộc chat đã hoàn thành', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'total',
                                'label' => 'Tổng cuộc chat theo ngày (done + chat miss)', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'total_thread_agent',
                                'label' => 'Tổng điện thoại viên nhận chat trong ngày (có check trùng)', // Đặt tên cho cột này là "Trạng thái"
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