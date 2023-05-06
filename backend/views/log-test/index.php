<?php


use \yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use bsadnu\googlecharts\ColumnChart;
use practically\chartjs\Chart;
use \kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use bsadnu\googlecharts\PieChart;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CamidCampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row msc-country-index">
    <div class="col-md-12">
        <div class="light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="panel panel-default stat-ticket-day-search">
                    <div class="panel-body row">
                        <?php $form = ActiveForm::begin([
                            'action' => ['index'],
                            'method' => 'get',
                        ]); ?>


                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-body row">
                    <div class="col-md-12">
                        <H2>Kiểm tra trạng thái on/off của chat service: </h2>
                        <?php if ($data1 != null) { ?>
                            <h4><?= $data1 ?></h4>
                        <?php } else { ?>
                            <h1 style="margin-left: 50px">No data</h1>
                        <?php } ?>
                    </div>

                    <div class="col-md-12">
                        <H2>Lấy số user đang kết nối tới chat service: </h2>
                        <?php if ($data2 != null) { ?>
                            <table id="table-2" class="table">
                                <?php foreach ($data2 as $key => $doc_count) { ?>
                                    <tr>
                                        <td>
                                            <?= $key.": ".$doc_count ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } else { ?>
                            <h1 style="margin-left: 50px">No data</h1>
                        <?php } ?>
                    </div>

                    <div class="col-md-12">
                        <H2>Lấy danh sách các chat instances: </h2>
                        <?php if ($data4 != null) { ?>
                            <table id="table-4" class="table">
                                <?php foreach ($data4 as $key => $doc_count) { ?>
                                    <tr>
                                        <td>
                                            <?= $key.": ".$doc_count ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } else { ?>
                            <h1 style="margin-left: 50px">No data</h1>
                        <?php } ?>
                    </div>

                    <div class="col-md-12">
                    <H2>Lấy thông tin của user: </h2>
                        <form action="index" method="get">
                            <label for="user">User:</label><br>
                            <input type="text" id="user" name="user"><br>
                            <input type="submit" value="Submit">
                        </form>
                        <?php if ($data3 != null && $data3[0] != null) { ?>
                            <table id="table-4" class="table">
                                <?php foreach ($data3[0] as $key => $doc_count) { ?>
                                    <tr>
                                        <td>
                                            <?= $key.": ".$doc_count ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } else { ?>
                            <h1 style="margin-left: 50px">No data</h1>
                        <?php } ?>
                    </div>
                    
                    <div class="col-md-12">
                    <H2>Disconnect user's active sessions: </h2>
                        <form action="index" method="get">
                            <label for="userdis">User:</label><br>
                            <input type="text" id="userdis" name="userdis"><br>
                            <input type="submit" value="Submit">
                        </form>
                        <?php if ($data5 != null) { ?>
                            <table id="table-5" class="table">
                                <?php foreach ($data5 as $key => $doc_count) { ?>
                                    <tr>
                                        <td>
                                            <?= $key.": ".$doc_count ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } else { ?>
                            <h1 style="margin-left: 50px">No data</h1>
                        <?php } ?>
                    </div>
                    
                    <div class="col-md-12">
                    <H2>Check Staff in Queue: </h2>
                        <form action="index" method="get">
                            <label for="phone_number">Phone Number:</label><br>
                            <input type="text" id="phone_number" name="phone_number"><br>
                            <input type="submit" value="Submit">
                        </form>
                        <?php if ($data6 != null) { ?>
                            <table id="table-6" class="table">
                                <?php foreach ($data6 as $key => $doc_count) { ?>
                                    <tr>
                                        <td>
                                            <?= $key.": ".$doc_count ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } else { ?>
                            <h1 style="margin-left: 50px">No data</h1>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>