<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Report Chat Traffic');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php echo $this->render('_search', ['model' => $searchModel]);?>
                <?php $form = ActiveForm::begin([
                    'action' => ['thread-chat-view/export-report-excel'],
                    'method' => 'post',
                ]); ?>
                <input type="hidden" name="export" value='<?php echo json_encode($arrActiveDevice) ?>'/>
                <?= Html::submitButton('Export', ['class' => 'btn btn-danger', 'data-pjax' => '0']) ?>
                <?php ActiveForm::end(); ?>
            </div>
            
            <div class="portlet-body">
                <div class="table-container">
                    <?= GridView::widget([
                        'dataProvider' => $arrActiveDevice,
                        'layout' => "{items}\n <div class='form-inline pagination page-size'>" . awesome\backend\grid\AwsPageSize::widget([
                            'options' => [
                                'class' => 'form-control  form-control-sm',
                                'style' => 'display:none;',
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
                                'attribute' => 'stt',
                                'label' => 'STT', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'month',
                                'label' => 'Tháng', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'date',
                                'label' => 'Ngày', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'total',
                                'label' => 'Tổng số lượng Thread', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'done',
                                'label' => 'Số lượng Done Thread', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'missing',
                                'label' => 'Số lượng Missing Thread', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'processing',
                                'label' => 'Số lượng Processing Thread', // Đặt tên cho cột này là "Trạng thái"
                            ],
                            [
                                'attribute' => 'agent',
                                'label' => 'Số lượng điện thoại viên tiếp nhận', // Đặt tên cho cột này là "Trạng thái"
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
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