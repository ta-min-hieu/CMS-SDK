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

$this->title = Yii::t('backend', 'Report Info Agent At Queue');
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
                                'attribute' => 'thumb',
                                'label' => 'Ảnh Queue',
                                'format' => ['image', ['width' => '60', 'height' => '60']],
                                'value' => function ($model) {
                                    return str_replace('http://', 'https://', $model['thumb']);
                                },
                            ],
                            [
                                'attribute' => 'disp_name',
                                'label' => 'Tên Queue', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'type',
                                'label' => 'Loại Queue', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'all',
                                'label' => 'Tổng số cuộc chat', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'new',
                                'label' => 'Số cuộc chat mới đang chờ hỗ trợ', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'opened',
                                'label' => 'Số cuộc chat đang tiếp nhận chưa kết thúc', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'closed',
                                'label' => 'Số cuộc chat đã kết thúc', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'acd',
                                'label' => 'Tổng thời gian chat (giây)', // Đặt tên cho cột này là "Trạng thái"
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