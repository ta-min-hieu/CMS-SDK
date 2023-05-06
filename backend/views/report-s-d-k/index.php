<?php


use yii\grid\GridView;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Report SDK');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                <?php $form = ActiveForm::begin([
                    'action' => ['report-s-d-k/export-report-excel'],
                    'method' => 'post',
                ]); ?>
                <input type="hidden" name="export" value='<?php echo json_encode($dataProviderr) ?>'/>
                <?= Html::submitButton('Export', ['class' => 'btn btn-danger', 'data-pjax' => '0']) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'showHeader' => false,
                        'caption' => '
                        <tr>
                                                <th rowspan=\'3\'>Mã BĐT</td>
                                                <th rowspan=\'3\'>Tên BĐT</td>
                                                <th rowspan=\'3\'>Mã Bưu Cục</td>
                                                <th colspan=\'27\' style="text-align: center;">Số liệu đầu KỲ báo cáo</th>
                                                <th colspan=\'27\' style="text-align: center;">SỐ LIỆU PHÁT SINH MỚI TRONG KỲ BÁO CÁO</th>
                                                <th colspan=\'27\' style="text-align: center;">SỐ LIỆU LUỸ KẾ CUỐI KỲ BÁO CÁO</th>
                                            </tr>
                                            <tr>
                                                <th colspan=\'6\' style="text-align: center;">Số lượng account đã mở</th>
                                                <th colspan=\'7\' style="text-align: center;">Số lượng account phát sinh chat</th>
                                                <th colspan=\'2\' style="text-align: center;">Chat KH vs Bưu tá (DD)</th>
                                                <th colspan=\'6\' style="text-align: center;">SL phiên chat KH với bưu cục (PNS)</th>
                                                <th colspan=\'3\' style="text-align: center;">SL phiên Chat KH với đầu mối CSKH (One CX)</th>
                                                <th colspan=\'3\' style="text-align: center;">SL phiên Chat KH với đầu mối bán hàng (CCP)</th>
                                                <th colspan=\'6\' style="text-align: center;">Số lượng account đã mở</th>
                                                <th colspan=\'7\' style="text-align: center;">Số lượng account phát sinh chat</th>
                                                <th colspan=\'2\' style="text-align: center;">Chat KH vs Bưu tá (DD)</th>
                                                <th colspan=\'6\' style="text-align: center;">SL phiên chat KH với bưu cục (PNS)</th>
                                                <th colspan=\'3\' style="text-align: center;">SL phiên Chat KH với đầu mối CSKH (One CX)</th>
                                                <th colspan=\'3\' style="text-align: center;">SL phiên Chat KH với đầu mối bán hàng (CCP)</th>
                                                <th colspan=\'6\' style="text-align: center;">Số lượng account đã mở</th>
                                                <th colspan=\'7\' style="text-align: center;">Số lượng account phát sinh chat</th>
                                                <th colspan=\'2\' style="text-align: center;">Chat KH vs Bưu tá (DD)</th>
                                                <th colspan=\'6\' style="text-align: center;">SL phiên chat KH với bưu cục (PNS)</th>
                                                <th colspan=\'3\' style="text-align: center;">SL phiên Chat KH với đầu mối CSKH (One CX)</th>
                                                <th colspan=\'3\' style="text-align: center;">SL phiên Chat KH với đầu mối bán hàng (CCP)</th>
                                            </tr>
                                            <tr>
                                                <td>Khách hàng</td>
                                                <td>Bưu tá</td>
                                                <td>Nhân viên nghiệp vụ</td>
                                                <td>Nhân viên CSKH</td>
                                                <td>Nhân viên bán hàng</td>
                                                <td>Tổng số lượng Account thử nghiệm</td>
                                                <td>Khách hàng</td>
                                                <td>Bưu tá</td>
                                                <td>Nhân viên nghiệp vụ</td>
                                                <td>Nhân viên CSKH</td>
                                                <td>Nhân viên bán hàng</td>
                                                <td>Tổng số lượng Account</td>
                                                <td>Tỷ lệ % số Account có phát sinh phiên Chat</td>
                                                <td>SL tin nhắn của KH đến bưu tá</td>
                                                <td>SL tin nhắn của bưu tá đến KH</td>
                                                <td>SL phiên Chat KH với BC Thu gom - Có trả lời</td>
                                                <td>SL phiên Chat KH với BC Thu gom - Không trả lời</td>
                                                <td>Tỷ lệ % phiên chat của KH đến BC Thu gom được trả lời</td>
                                                <td>SL phiên Chat KH với BC Phát - Có trả lời</td>
                                                <td>SL phiên Chat KH với BC Phát - Không trả lời</td>
                                                <td>Tỷ lệ % phiên chat của KH đến BC Phát được trả lời</td>
                                                <td>SL phiên Chat KH với CSKH - Có trả lời</td>
                                                <td>SL phiên Chat KH với CSKH - Không trả lời</td>
                                                <td>Tỷ lệ % phiên Chat KH với CSKH được trả lời</td>
                                                <td>SL phiên Chat KH với Bán hàng - Có trả lời</td>
                                                <td>SL phiên Chat KH với Bán hàng - Không trả lời</td>
                                                <td>Tỷ lệ % phiên Chat KH với Bán hàng được trả lời</td>
                                                <td>Khách hàng</td>
                                                <td>Bưu tá</td>
                                                <td>Nhân viên nghiệp vụ</td>
                                                <td>Nhân viên CSKH</td>
                                                <td>Nhân viên bán hàng</td>
                                                <td>Tổng số lượng Account thử nghiệm</td>
                                                <td>Khách hàng</td>
                                                <td>Bưu tá</td>
                                                <td>Nhân viên nghiệp vụ</td>
                                                <td>Nhân viên CSKH</td>
                                                <td>Nhân viên bán hàng</td>
                                                <td>Tổng số lượng Account</td>
                                                <td>Tỷ lệ % số Accoun có phát sinh phiên Chat</td>
                                                <td>SL tin nhắn của KH đến bưu tá</td>
                                                <td>SL tin nhắn của bưu tá đến KH</td>
                                                <td>SL phiên Chat KH với BC Thu gom - Có trả lời</td>
                                                <td>SL phiên Chat KH với BC Thu gom - Không trả lời</td>
                                                <td>Tỷ lệ % phiên chat của KH đến BC Thu gom được trả lời</td>
                                                <td>SL phiên Chat KH với BC Phát - Có trả lời</td>
                                                <td>SL phiên Chat KH với BC Phát - Không trả lời</td>
                                                <td>Tỷ lệ % phiên chat của KH đến BC Phát được trả lời</td>
                                                <td>SL phiên Chat KH với CSKH - Có trả lời</td>
                                                <td>SL phiên Chat KH với CSKH - Không trả lời</td>
                                                <td>Tỷ lệ % phiên Chat KH với CSKH được trả lời</td>
                                                <td>SL phiên Chat KH với Bán hàng - Có trả lời</td>
                                                <td>SL phiên Chat KH với Bán hàng - Không trả lời</td>
                                                <td>Tỷ lệ % phiên Chat KH với Bán hàng được trả lời</td>
                                                <td>Khách hàng</td>
                                                <td>Bưu tá</td>
                                                <td>Nhân viên nghiệp vụ</td>
                                                <td>Nhân viên CSKH</td>
                                                <td>Nhân viên bán hàng</td>
                                                <td>Tổng số lượng Account thử nghiệm</td>
                                                <td>Khách hàng</td>
                                                <td>Bưu tá</td>
                                                <td>Nhân viên nghiệp vụ</td>
                                                <td>Nhân viên CSKH</td>
                                                <td>Nhân viên bán hàng</td>
                                                <td>Tổng số lượng Account</td>
                                                <td>Tỷ lệ % số Account có phát sinh phiên Chat</td>
                                                <td>SL tin nhắn của KH đến bưu tá</td>
                                                <td>SL tin nhắn của bưu tá đến KH</td>
                                                <td>SL phiên Chat KH với BC Thu gom - Có trả lời</td>
                                                <td>SL phiên Chat KH với BC Thu gom - Không trả lời</td>
                                                <td>Tỷ lệ % phiên chat của KH đến BC Thu gom được trả lời</td>
                                                <td>SL phiên Chat KH với BC Phát - Có trả lời</td>
                                                <td>SL phiên Chat KH với BC Phát - Không trả lời</td>
                                                <td>Tỷ lệ % phiên chat của KH đến BC Phát được trả lời</td>
                                                <td>SL phiên Chat KH với CSKH - Có trả lời</td>
                                                <td>SL phiên Chat KH với CSKH - Không trả lời</td>
                                                <td>Tỷ lệ % phiên Chat KH với CSKH được trả lời</td>
                                                <td>SL phiên Chat KH với Bán hàng - Có trả lời</td>
                                                <td>SL phiên Chat KH với Bán hàng - Không trả lời</td>
                                                <td>Tỷ lệ % phiên Chat KH với Bán hàng được trả lời</td>
                                            </tr>
                                          
                                            
                        ',
                        'captionOptions' => ['class' => 'text-center'], // căn giữa nội dung caption
                        'headerRowOptions' => ['style' => 'text-align: center;'], // căn giữa header bằng CSS
                        //'filterModel' => $searchModel,
                        // 'filterSelector' => 'select[name="per-page"]',
                        'layout' => "{items}\n <div class='form-inline pagination page-size'>" . awesome\backend\grid\AwsPageSize::widget([
                            'options' => [
                                'class' => 'form-control  form-control-sm',
                                'style' => 'display:none;',
                                'headerOptions' => ['style' => 'display: none;']
                            ]
                        ]) . '</div> <div class="col-md-6">{pager}</div> <div class="pagination col-md-3 text-right total-count">' . Yii::t('backend', 'Tổng số') . ': <b>' . number_format($dataProvider->getTotalCount()) . '</b> ' . Yii::t('backend', 'bản ghi') . '</div>',
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
                                'attribute' => 'id_province',
                                'headerOptions' => ['style' => 'display: none;'],
                            ],
                            [
                                'attribute' => 'province',
                                'headerOptions' => ['style' => 'display: none;'],
                            ],
                            [
                                'attribute' => 'id_department',
                                'headerOptions' => ['style' => 'display: none;'],
                            ],
                            [
                                'attribute' => 'sld_acc_op_kh',
                                'headerOptions' => ['style' => 'display: none;']
                            ],
                            'sld_acc_op_bt',
                            'sld_acc_op_nvt',
                            'sld_acc_op_cskh',
                            'sld_acc_op_ccp',
                            'sld_ts_dm',
                            'sld_acc_chat_kh',
                            'sld_acc_chat_bt',
                            'sld_acc_chat_nvt',
                            'sld_acc_chat_cskh',
                            'sld_acc_chat_ccp',
                            'sld_ts_psc_acc',
                            'sld_ts_psc_tl',
                            'sld_chat_bc_kh_bt',
                            'sld_chat_bc_bt_kh',
                            'sld_chat_bc_kh_bct_tl',
                            'sld_chat_bc_kh_bct_ktl',
                            'sld_ts_pc_kh_bct',
                            'sld_chat_bc_kh_bcp_tl',
                            'sld_chat_bc_kh_bcp_ktl',
                            'sld_ts_pc_kh_bcp',
                            'sld_chat_cskh_tl',
                            'sld_chat_cskh_ktl',
                            'sld_ts_pc_kh_cskh',
                            'sld_chat_ccp_tl',
                            'sld_chat_ccp_ktl',
                            'sld_ts_pc_kh_bh',
                            'slm_acc_op_kh',
                            'slm_acc_op_bt',
                            'slm_acc_op_nvt',
                            'slm_acc_op_cskh',
                            'slm_acc_op_ccp',
                            'slm_ts_dm',
                            'slm_acc_chat_kh',
                            'slm_acc_chat_bt',
                            'slm_acc_chat_nvt',
                            'slm_acc_chat_cskh',
                            'slm_acc_chat_ccp',
                            'slm_ts_psc_acc',
                            'slm_ts_psc_tl',
                            'slm_chat_bc_kh_bt',
                            'slm_chat_bc_bt_kh',
                            'slm_chat_bc_kh_bct_tl',
                            'slm_chat_bc_kh_bct_ktl',
                            'slm_ts_pc_kh_bct',
                            'slm_chat_bc_kh_bcp_tl',
                            'slm_chat_bc_kh_bcp_ktl',
                            'slm_ts_pc_kh_bcp',
                            'slm_chat_cskh_tl',
                            'slm_chat_cskh_ktl',
                            'slm_ts_pc_kh_cskh',
                            'slm_chat_ccp_tl',
                            'slm_chat_ccp_ktl',
                            'slm_ts_pc_kh_bh',
                            'slc_acc_op_kh',
                            'slc_acc_op_bt',
                            'slc_acc_op_nvt',
                            'slc_acc_op_cskh',
                            'slc_acc_op_ccp',
                            'slc_ts_dm',
                            'slc_acc_chat_kh',
                            'slc_acc_chat_bt',
                            'slc_acc_chat_nvt',
                            'slc_acc_chat_cskh',
                            'slc_acc_chat_ccp',
                            'slc_ts_psc_acc',
                            'slc_ts_psc_tl',
                            'slc_chat_bc_kh_bt',
                            'slc_chat_bc_bt_kh',
                            'slc_chat_bc_kh_bct_tl',
                            'slc_chat_bc_kh_bct_ktl',
                            'slc_ts_pc_kh_bct',
                            'slc_chat_bc_kh_bcp_tl',
                            'slc_chat_bc_kh_bcp_ktl',
                            'slc_ts_pc_kh_bcp',
                            'slc_chat_cskh_tl',
                            'slc_chat_cskh_ktl',
                            'slc_ts_pc_kh_cskh',
                            'slc_chat_ccp_tl',
                            'slc_chat_ccp_ktl',
                            'slc_ts_pc_kh_bh',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>