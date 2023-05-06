<?php

namespace backend\controllers;

use backend\models\Province;
use backend\models\ReportSDK;
use backend\models\ReportSDKSearch;
use backend\models\User;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\web\Controller;
use yii;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
class ReportSDKController extends Controller
{
  public function actionIndex()
  {
    $searchModel = new ReportSDKSearch();
    $reportSDK = new ReportSDK();
    $id_province = isset($_GET['id_province']) && $_GET['id_province'] != '' ? $_GET['id_province'] : null;
    $id_department = isset($_GET['id_department']) && $_GET['id_department'] != '' ? $_GET['id_department'] : null;
    $report_date = isset($_GET['report_date']) && $_GET['report_date'] != '' ? $_GET['report_date'] : null;
    if($report_date != null)
      $report_dates = \common\helpers\Helpers::splitDateNew($report_date, 'd/m/Y');
    if(User::findOne(\Yii::$app->user->id)->id_province != null)
      $id_province = User::findOne(\Yii::$app->user->id)->id_province;
    //ngày null
    if ($report_date == null) {
      $dataProvider1 = array();
      $dataProvider1 = $this->convertDataProvinder($dataProvider1);
    } else {
      $data = $reportSDK->searchSldSlc($id_province,$id_department,$report_dates[0],$report_dates[1]);
      if ($id_department == null) {
        if ($id_province == null) {
          if (User::findOne(\Yii::$app->user->id)->id_province == null)
            $dataProvider1 = ReportSDKController::processArray($data, $report_dates[0], $report_dates[1]);
          else
            $dataProvider1 = ReportSDKController::processArrayDepartment($data, $report_dates[0], $report_dates[1], $id_province);
        } else
          $dataProvider1 = ReportSDKController::processArrayDepartment($data, $report_dates[0], $report_dates[1], $id_province);
      } else {
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        $id_province = (new \yii\db\Query())
          ->select(['department.id_province'])
          ->from($dbn . '.department')
          ->where(['department.id_department' => $id_department])
          ->one();
        $dataProvider1 = ReportSDKController::processArrayDepartment($data, $report_dates[0], $report_dates[1], $id_province['id_province'], $id_department);
      }
    }
    $dataProviderr = clone $dataProvider1;
    $dataProviderr->pagination = false;
    return $this->render('index', [
      'searchModel' => $searchModel,
      'dataProvider' => $dataProvider1,
      'dataProviderr' => $dataProviderr->getModels(),
    ]);
  }
  public function processArray($data, $date_start, $date_end)
  {
    $command = Yii::$app->dbsdk;
    $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
    $b = ReportSDK::getAllProvince($date_start, $date_end, $dbn);
    $slm = ReportSDK::getAllProvinceNew($date_start, $date_end);
    //SLC
    for ($i = 0; $i < count($data); $i++) {
      $data = $this->createSlm($data, $i);
      for ($j = 0; $j < count($slm); $j++) {
        if ($data[$i]['id_province'] == $slm[$j]['id_province']) {
          $data = $this->updateSlm($data, $i, $slm, $j);
          break;
        }
      }
      //SLM ko tổng được
      for ($j = 0; $j < count($b); $j++) {
        ($data[$i]['id_province'] == $b[$j]['id_province'] && isset($b[$j]['type_user']) && $b[$j]['type_user'] == 0)?($data[$i]['slm_acc_chat_kh']=$b[$j]['distinct_user_count']):'0';
        ($data[$i]['id_province'] == $b[$j]['id_province'] && isset($b[$j]['type_user']) && $b[$j]['type_user'] == 1)?($data[$i]['slm_acc_chat_bt']=$b[$j]['distinct_user_count']):'0';
        ($data[$i]['id_province'] == $b[$j]['id_province'] && isset($b[$j]['type_user']) && $b[$j]['type_user'] == 2)?($data[$i]['slm_acc_chat_nvt']=$b[$j]['distinct_user_count']):'0';
        ($data[$i]['id_province'] == $b[$j]['id_province'] && isset($b[$j]['type_user']) && $b[$j]['type_user'] == 3)?($data[$i]['slm_acc_chat_cskh']=$b[$j]['distinct_user_count']):'0';
        ($data[$i]['id_province'] == $b[$j]['id_province'] && isset($b[$j]['type_user']) && $b[$j]['type_user'] == 4)?($data[$i]['slm_acc_chat_ccp']=$b[$j]['distinct_user_count']):'0';
      }
      //TS SLM
      $data = $this->createTsSlm($data, $i);
    }
    $data = $this->convertDataProvinder($data);
    return $data;
  }
  public function processArrayDepartment($data, $date_start, $date_end, $id_province, $id_department = null)
  {
    if($id_department == null){
      $psc = ReportSDK::getAllDepartmentPSC($id_province, $date_start, $date_end);
      $slm = ReportSDK::getAllDepartmentNew($id_province, $date_start, $date_end);
    }
    else{
      $psc = ReportSDK::getDepartmentPSC($date_start, $date_end, $id_department);
      $slm = ReportSDK::getDepartmentNew($id_department, $date_start, $date_end);
    }
    $c = ReportSDK::getProvince($date_start, $date_end, $id_province);
    for ($i = 0; $i < count($data); $i++) {
      $data = $this->createSlm($data, $i);
      for ($j = 0; $j < count($slm); $j++) {
        if ($data[$i]['id_department'] == $slm[$j]['id_department']) {
          $data = $this->updateSlm($data, $i, $slm, $j);
          break;
        }
      }
      for ($j = 0; $j < count($psc); $j++) {
        ($data[$i]['id_department'] == $psc[$j]['id_department'] && isset($psc[$j]['type_user']) && $psc[$j]['type_user'] == 0)?($data[$i]['slm_acc_chat_kh']=$psc[$j]['distinct_user_count']):'0';
        ($data[$i]['id_department'] == $psc[$j]['id_department'] && isset($psc[$j]['type_user']) && $psc[$j]['type_user'] == 1)?($data[$i]['slm_acc_chat_bt']=$psc[$j]['distinct_user_count']):'0';
        ($data[$i]['id_department'] == $psc[$j]['id_department'] && isset($psc[$j]['type_user']) && $psc[$j]['type_user'] == 2)?($data[$i]['slm_acc_chat_nvt']=$psc[$j]['distinct_user_count']):'0';
        ($data[$i]['id_department'] == $psc[$j]['id_department'] && isset($psc[$j]['type_user']) && $psc[$j]['type_user'] == 3)?($data[$i]['slm_acc_chat_cskh']=$psc[$j]['distinct_user_count']):'0';
        ($data[$i]['id_department'] == $psc[$j]['id_department'] && isset($psc[$j]['type_user']) && $psc[$j]['type_user'] == 4)?($data[$i]['slm_acc_chat_ccp']=$psc[$j]['distinct_user_count']):'0';
        if ($data[$i]['id_department'] == null) {
          for ($z = 0; $z < count($c); $z++) {
            ($data[$i]['id_province'] == $c[$z]['id_province'] && isset($c[$z]['type_user']) && $c[$z]['type_user'] == 0) ? ($data[$i]['slm_acc_chat_kh'] = $c[$z]['distinct_user_count']) : '0';
            ($data[$i]['id_province'] == $c[$z]['id_province'] && isset($c[$z]['type_user']) && $c[$z]['type_user'] == 1) ? ($data[$i]['slm_acc_chat_bt'] = $c[$z]['distinct_user_count']) : '0';
            ($data[$i]['id_province'] == $c[$z]['id_province'] && isset($c[$z]['type_user']) && $c[$z]['type_user'] == 2) ? ($data[$i]['slm_acc_chat_nvt'] = $c[$z]['distinct_user_count']) : '0';
            ($data[$i]['id_province'] == $c[$z]['id_province'] && isset($c[$z]['type_user']) && $c[$z]['type_user'] == 3) ? ($data[$i]['slm_acc_chat_cskh'] = $c[$z]['distinct_user_count']) : '0';
            ($data[$i]['id_province'] == $c[$z]['id_province'] && isset($c[$z]['type_user']) && $c[$z]['type_user'] == 4) ? ($data[$i]['slm_acc_chat_ccp'] = $c[$z]['distinct_user_count']) : '0';
          }
        }
      }
      //TS SLM
      $data = $this->createTsSlm($data, $i);
    }
    $data = $this->convertDataProvinder($data);
    return $data;
  }
  public function actionAjaxSearchProvince($q = null, $ids = [])
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $out = ['results' => ['id' => '', 'text' => '']];
    if (!is_null($q)) {
      $data = Province::find()
        ->select('id_province as id, province as text')
        ->where([
          'OR',
          ['id_province' => $q],
          ['like', 'province', $q],
        ])
        ->limit(20)
        ->asArray()
        ->all();
      $out['results'] = array_values($data);
      for ($i = 0; $i < count($out['results']); $i++) {
        $out['results'][$i]['text'] = $out['results'][$i]['id'] . ' - ' . $out['results'][$i]['text'];
      }
    } elseif (is_array($ids) && count($ids)) {
      $data = Province::find()
        ->select('id_province as id, province as text')
        ->distinct()
        ->where([
          'OR',
          ['id_province' => $ids],
          ['like', 'province', $ids],
        ])
        ->asArray()
        ->all();
      $out['results'] = array_values($data);
      for ($i = 0; $i < count($out['results']); $i++) {
        $out['results'][$i]['text'] = $out['results'][$i]['id'] . ' - ' . $out['results'][$i]['text'];
      }
    }
    return $out;
  }
  public function actionExportReportExcel()
    {
      if($this->request->isPost){
      $dataProvider = json_decode($_POST['export'], true);
      $dataProvider = new ArrayDataProvider([
        'allModels' => $dataProvider,
        'pagination' => false,
        'sort' => [
          'attributes' => ['id_province', 'province'],
        ],
      ]);
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $headers = [
          Yii::t('backend', 'Mã BĐT'),
          Yii::t('backend', 'Tên BĐT'),
          Yii::t('backend', 'Mã Bưu Cục'),
          Yii::t('backend', 'Khách hàng'),
          Yii::t('backend', 'Bưu tá'),
          Yii::t('backend', 'Nhân viên nghiệp vụ'),
          Yii::t('backend', 'Nhân viên CSKH'),
          Yii::t('backend', 'Nhân viên bán hàng'),
          Yii::t('backend', 'Tổng số lượng Account thử nghiệm'),
          Yii::t('backend', 'Khách hàng'),
          Yii::t('backend', 'Bưu tá'),
          Yii::t('backend', 'Nhân viên nghiệp vụ'),
          Yii::t('backend', 'Nhân viên CSKH'),
          Yii::t('backend', 'Nhân viên bán hàng'),
          Yii::t('backend', 'Tổng số lượng Account'),
          Yii::t('backend', 'Tỷ lệ % số Account có phát sinh phiên Chat'),
          Yii::t('backend', 'SL tin nhắn của KH đến bưu tá'),
          Yii::t('backend', 'SL tin nhắn của bưu tá đến KH'),
          Yii::t('backend', 'SL phiên Chat KH với BC Thu gom - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Thu gom - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên chat của KH đến BC Thu gom được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Phát - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Phát - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên chat của KH đến BC Phát được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với CSKH - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với CSKH - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên Chat KH với CSKH được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với Bán hàng - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với Bán hàng - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên Chat KH với Bán hàng được trả lời'),
          Yii::t('backend', 'Khách hàng'),
          Yii::t('backend', 'Bưu tá'),
          Yii::t('backend', 'Nhân viên nghiệp vụ'),
          Yii::t('backend', 'Nhân viên CSKH'),
          Yii::t('backend', 'Nhân viên bán hàng'),
          Yii::t('backend', 'Tổng số lượng Account thử nghiệm'),
          Yii::t('backend', 'Khách hàng'),
          Yii::t('backend', 'Bưu tá'),
          Yii::t('backend', 'Nhân viên nghiệp vụ'),
          Yii::t('backend', 'Nhân viên CSKH'),
          Yii::t('backend', 'Nhân viên bán hàng'),
          Yii::t('backend', 'Tổng số lượng Account'),
          Yii::t('backend', 'Tỷ lệ % số Accoun có phát sinh phiên Chat'),
          Yii::t('backend', 'SL tin nhắn của KH đến bưu tá'),
          Yii::t('backend', 'SL tin nhắn của bưu tá đến KH'),
          Yii::t('backend', 'SL phiên Chat KH với BC Thu gom - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Thu gom - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên chat của KH đến BC Thu gom được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Phát - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Phát - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên chat của KH đến BC Phát được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với CSKH - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với CSKH - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên Chat KH với CSKH được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với Bán hàng - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với Bán hàng - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên Chat KH với Bán hàng được trả lời'),
          Yii::t('backend', 'Khách hàng'),
          Yii::t('backend', 'Bưu tá'),
          Yii::t('backend', 'Nhân viên nghiệp vụ'),
          Yii::t('backend', 'Nhân viên CSKH'),
          Yii::t('backend', 'Nhân viên bán hàng'),
          Yii::t('backend', 'Tổng số lượng Account thử nghiệm'),
          Yii::t('backend', 'Khách hàng'),
          Yii::t('backend', 'Bưu tá'),
          Yii::t('backend', 'Nhân viên nghiệp vụ'),
          Yii::t('backend', 'Nhân viên CSKH'),
          Yii::t('backend', 'Nhân viên bán hàng'),
          Yii::t('backend', 'Tổng số lượng Account'),
          Yii::t('backend', 'Tỷ lệ % số Account có phát sinh phiên Chat'),
          Yii::t('backend', 'SL tin nhắn của KH đến bưu tá'),
          Yii::t('backend', 'SL tin nhắn của bưu tá đến KH'),
          Yii::t('backend', 'SL phiên Chat KH với BC Thu gom - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Thu gom - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên chat của KH đến BC Thu gom được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Phát - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với BC Phát - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên chat của KH đến BC Phát được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với CSKH - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với CSKH - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên Chat KH với CSKH được trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với Bán hàng - Có trả lời'),
          Yii::t('backend', 'SL phiên Chat KH với Bán hàng - Không trả lời'),
          Yii::t('backend', 'Tỷ lệ % phiên Chat KH với Bán hàng được trả lời'),
        ];

        $activeSheet->mergeCells("A1:E1");
        $activeSheet->mergeCells("A2:E2");
        $activeSheet->mergeCells("A5:A7");
        $activeSheet->mergeCells("B5:B7");
        $activeSheet->mergeCells("C5:C7");
        
        $activeSheet->setCellValue('A1', Yii::t('backend', 'TỔNG CÔNG TY BƯU ĐIỆN VIỆT NAM'));
        $activeSheet->setCellValue('A2', Yii::t('backend', 'BƯU ĐIỆN TỈNH/TP: Tất cả hoặc 1 BĐT/TP cụ thể'));
        $activeSheet->setCellValue('B3', Yii::t('backend', 'Report date: ').date('Y-m-d H:i:s'));

        $activeSheet->setCellValue('A5', Yii::t('backend', 'Mã BĐT'));
        $activeSheet->setCellValue('B5', Yii::t('backend', 'Tên BĐT'));
        $activeSheet->setCellValue('C5', Yii::t('backend', 'Mã Bưu Cục'));
        $activeSheet->mergeCells("D5:AD5");
        $activeSheet->setCellValue('D5', Yii::t('backend', 'SỐ LIỆU ĐẦU KỲ BÁO CÁO'));
        $activeSheet->mergeCells("AE5:BE5");
        $activeSheet->setCellValue('AE5', Yii::t('backend', 'SỐ LIỆU PHÁT SINH MỚI TRONG KỲ BÁO CÁO'));
        $activeSheet->mergeCells("BF5:CF5");
        $activeSheet->setCellValue('BF5', Yii::t('backend', 'SỐ LIỆU LUỸ KẾ CUỐI KỲ BÁO CÁO'));

        $activeSheet->mergeCells("D6:I6");
        $activeSheet->setCellValue('D6', Yii::t('backend', 'Số lượng account đã mở'));
        $activeSheet->mergeCells("J6:P6");
        $activeSheet->setCellValue('J6', Yii::t('backend', 'Số lượng account phát sinh chat'));
        $activeSheet->mergeCells("Q6:R6");
        $activeSheet->setCellValue('Q6', Yii::t('backend', 'Chat KH vs Bưu tá (DD)'));
        $activeSheet->mergeCells("S6:X6");
        $activeSheet->setCellValue('S6', Yii::t('backend', 'SL phiên chat KH với bưu cục (PNS)'));
        $activeSheet->mergeCells("Y6:AA6");
        $activeSheet->setCellValue('Y6', Yii::t('backend', 'SL phiên Chat KH với đầu mối CSKH (One CX)'));
        $activeSheet->mergeCells("AB6:AD6");
        $activeSheet->setCellValue('AB6', Yii::t('backend', 'SL phiên Chat KH với đầu mối bán hàng (CCP)'));
        $activeSheet->mergeCells("AE6:AJ6");
        $activeSheet->setCellValue('AE6', Yii::t('backend', 'Số lượng account đã mở'));
        $activeSheet->mergeCells("AK6:AQ6");
        $activeSheet->setCellValue('AK6', Yii::t('backend', 'Số lượng account phát sinh chat'));
        $activeSheet->mergeCells("AR6:AS6");
        $activeSheet->setCellValue('AR6', Yii::t('backend', 'Chat KH vs Bưu tá (DD)'));
        $activeSheet->mergeCells("AT6:AY6");
        $activeSheet->setCellValue('AT6', Yii::t('backend', 'SL phiên chat KH với bưu cục (PNS)'));
        $activeSheet->mergeCells("AZ6:BB6");
        $activeSheet->setCellValue('AZ6', Yii::t('backend', 'SL phiên Chat KH với đầu mối CSKH (One CX)'));
        $activeSheet->mergeCells("BC6:BE6");
        $activeSheet->setCellValue('BC6', Yii::t('backend', 'SL phiên Chat KH với đầu mối bán hàng (CCP)'));
        $activeSheet->mergeCells("BF6:BK6");
        $activeSheet->setCellValue('BF6', Yii::t('backend', 'Số lượng account đã mở'));
        $activeSheet->mergeCells("BL6:BR6");
        $activeSheet->setCellValue('BL6', Yii::t('backend', 'Số lượng account phát sinh chat'));
        $activeSheet->mergeCells("BS6:BT6");
        $activeSheet->setCellValue('BS6', Yii::t('backend', 'Chat KH vs Bưu tá (DD)'));
        $activeSheet->mergeCells("BU6:BZ6");
        $activeSheet->setCellValue('BU6', Yii::t('backend', 'SL phiên chat KH với bưu cục (PNS)'));
        $activeSheet->mergeCells("CA6:CC6");
        $activeSheet->setCellValue('CA6', Yii::t('backend', 'SL phiên Chat KH với đầu mối CSKH (One CX)'));
        $activeSheet->mergeCells("CD6:CF6");
        $activeSheet->setCellValue('CD6', Yii::t('backend', 'SL phiên Chat KH với đầu mối bán hàng (CCP)'));

        $activeSheet
            ->fromArray(
                $headers,  // The data to set
                NULL,        // Array values with this value will not be set
                'A7'         // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
            );
        $activeSheet->getStyle('A5:CF7')->applyFromArray([
            'font' => [
                'bold' => true, 'size' => 11
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);

        $r = 8;
        if ($dataProvider->getTotalCount()) {
            foreach ($dataProvider->getModels() as $item) {
                $activeSheet->setCellValue('A' . strval($r), $item['id_province']);
                $activeSheet->setCellValue('B' . strval($r), $item['province']);
                $activeSheet->setCellValue('C' . strval($r), isset($item['id_department'])?$item['id_department']:"Toàn bộ");
                $activeSheet->setCellValue('D' . strval($r), $item['sld_acc_op_kh']);
                $activeSheet->setCellValue('E' . strval($r), $item['sld_acc_op_bt']);
                $activeSheet->setCellValue('F' . strval($r), $item['sld_acc_op_nvt']);
                $activeSheet->setCellValue('G' . strval($r), $item['sld_acc_op_cskh']);
                $activeSheet->setCellValue('H' . strval($r), $item['sld_acc_op_ccp']);
                $activeSheet->setCellValue('I' . strval($r), $item['sld_ts_dm']);
                $activeSheet->setCellValue('J' . strval($r), $item['sld_acc_chat_kh']);
                $activeSheet->setCellValue('K' . strval($r), $item['sld_acc_chat_bt']);
                $activeSheet->setCellValue('L' . strval($r), $item['sld_acc_chat_nvt']);
                $activeSheet->setCellValue('M' . strval($r), $item['sld_acc_chat_cskh']);
                $activeSheet->setCellValue('N' . strval($r), $item['sld_acc_chat_ccp']);
                $activeSheet->setCellValue('O' . strval($r), $item['sld_ts_psc_acc']);
                $activeSheet->setCellValue('P' . strval($r), $item['sld_ts_psc_tl']);
                $activeSheet->setCellValue('Q' . strval($r), $item['sld_chat_bc_kh_bt']);
                $activeSheet->setCellValue('R' . strval($r), $item['sld_chat_bc_bt_kh']);
                $activeSheet->setCellValue('S' . strval($r), $item['sld_chat_bc_kh_bct_tl']);
                $activeSheet->setCellValue('T' . strval($r), $item['sld_chat_bc_kh_bct_ktl']);
                $activeSheet->setCellValue('U' . strval($r), $item['sld_ts_pc_kh_bct']);
                $activeSheet->setCellValue('V' . strval($r), $item['sld_chat_bc_kh_bcp_tl']);
                $activeSheet->setCellValue('W' . strval($r), $item['sld_chat_bc_kh_bcp_ktl']);
                $activeSheet->setCellValue('X' . strval($r), $item['sld_ts_pc_kh_bcp']);
                $activeSheet->setCellValue('Y' . strval($r), $item['sld_chat_cskh_tl']);
                $activeSheet->setCellValue('Z' . strval($r), $item['sld_chat_cskh_ktl']);
                $activeSheet->setCellValue('AA' . strval($r), $item['sld_ts_pc_kh_cskh']);
                $activeSheet->setCellValue('AB' . strval($r), $item['sld_chat_ccp_tl']);
                $activeSheet->setCellValue('AC' . strval($r), $item['sld_chat_ccp_ktl']);
                $activeSheet->setCellValue('AD' . strval($r), $item['sld_ts_pc_kh_bh']);

                $activeSheet->setCellValue('AE' . strval($r), $item['slm_acc_op_kh']);
                $activeSheet->setCellValue('AF' . strval($r), $item['slm_acc_op_bt']);
                $activeSheet->setCellValue('AG' . strval($r), $item['slm_acc_op_nvt']);
                $activeSheet->setCellValue('AH' . strval($r), $item['slm_acc_op_cskh']);
                $activeSheet->setCellValue('AI' . strval($r), $item['slm_acc_op_ccp']);
                $activeSheet->setCellValue('AJ' . strval($r), $item['slm_ts_dm']);
                $activeSheet->setCellValue('AK' . strval($r), $item['slm_acc_chat_kh']);
                $activeSheet->setCellValue('AL' . strval($r), $item['slm_acc_chat_bt']);
                $activeSheet->setCellValue('AM' . strval($r), $item['slm_acc_chat_nvt']);
                $activeSheet->setCellValue('AN' . strval($r), $item['slm_acc_chat_cskh']);
                $activeSheet->setCellValue('AO' . strval($r), $item['slm_acc_chat_ccp']);
                $activeSheet->setCellValue('AP' . strval($r), $item['slm_ts_psc_acc']);
                $activeSheet->setCellValue('AQ' . strval($r), $item['slm_ts_psc_tl']);
                $activeSheet->setCellValue('AR' . strval($r), $item['slm_chat_bc_kh_bt']);
                $activeSheet->setCellValue('AS' . strval($r), $item['slm_chat_bc_bt_kh']);
                $activeSheet->setCellValue('AT' . strval($r), $item['slm_chat_bc_kh_bct_tl']);
                $activeSheet->setCellValue('AU' . strval($r), $item['slm_chat_bc_kh_bct_ktl']);
                $activeSheet->setCellValue('AV' . strval($r), $item['slm_ts_pc_kh_bct']);
                $activeSheet->setCellValue('AW' . strval($r), $item['slm_chat_bc_kh_bcp_tl']);
                $activeSheet->setCellValue('AX' . strval($r), $item['slm_chat_bc_kh_bcp_ktl']);
                $activeSheet->setCellValue('AY' . strval($r), $item['slm_ts_pc_kh_bcp']);
                $activeSheet->setCellValue('AZ' . strval($r), $item['slm_chat_cskh_tl']);
                $activeSheet->setCellValue('BA' . strval($r), $item['slm_chat_cskh_ktl']);
                $activeSheet->setCellValue('BB' . strval($r), $item['slm_ts_pc_kh_cskh']);
                $activeSheet->setCellValue('BC' . strval($r), $item['slm_chat_ccp_tl']);
                $activeSheet->setCellValue('BD' . strval($r), $item['slm_chat_ccp_ktl']);
                $activeSheet->setCellValue('BE' . strval($r), $item['slm_ts_pc_kh_bh']);

                $activeSheet->setCellValue('BF' . strval($r), !isset($item['slc_acc_op_kh'])?"0":$item['slc_acc_op_kh']);
                $activeSheet->setCellValue('BG' . strval($r), !isset($item['slc_acc_op_bt'])?"0":$item['slc_acc_op_bt']);
                $activeSheet->setCellValue('BH' . strval($r), !isset($item['slc_acc_op_nvt'])?"0":$item['slc_acc_op_nvt']);
                $activeSheet->setCellValue('BI' . strval($r), !isset($item['slc_acc_op_cskh'])?"0":$item['slc_acc_op_cskh']);
                $activeSheet->setCellValue('BJ' . strval($r), !isset($item['slc_acc_op_ccp'])?"0":$item['slc_acc_op_ccp']);
                $activeSheet->setCellValue('BK' . strval($r), !isset($item['slc_ts_dm'])?"0":$item['slc_ts_dm']);
                $activeSheet->setCellValue('BL' . strval($r), !isset($item['slc_acc_chat_kh'])?"0":$item['slc_acc_chat_kh']);
                $activeSheet->setCellValue('BM' . strval($r), !isset($item['slc_acc_chat_bt'])?"0":$item['slc_acc_chat_bt']);
                $activeSheet->setCellValue('BN' . strval($r), !isset($item['slc_acc_chat_nvt'])?"0":$item['slc_acc_chat_nvt']);
                $activeSheet->setCellValue('BO' . strval($r), !isset($item['slc_acc_chat_cskh'])?"0":$item['slc_acc_chat_cskh']);
                $activeSheet->setCellValue('BP' . strval($r), !isset($item['slc_acc_chat_ccp'])?"0":$item['slc_acc_chat_ccp']);
                $activeSheet->setCellValue('BQ' . strval($r), !isset($item['slc_ts_psc_acc'])?"0":$item['slc_ts_psc_acc']);
                $activeSheet->setCellValue('BR' . strval($r), !isset($item['slc_ts_psc_tl'])?"0":$item['slc_ts_psc_tl']);
                $activeSheet->setCellValue('BS' . strval($r), !isset($item['slc_chat_bc_kh_bt'])?"0":$item['slc_chat_bc_kh_bt']);
                $activeSheet->setCellValue('BT' . strval($r), !isset($item['slc_chat_bc_bt_kh'])?"0":$item['slc_chat_bc_bt_kh']);
                $activeSheet->setCellValue('BU' . strval($r), !isset($item['slc_chat_bc_kh_bct_tl'])?"0":$item['slc_chat_bc_kh_bct_tl']);
                $activeSheet->setCellValue('BV' . strval($r), !isset($item['slc_chat_bc_kh_bct_ktl'])?"0":$item['slc_chat_bc_kh_bct_ktl']);
                $activeSheet->setCellValue('BW' . strval($r), !isset($item['slc_ts_pc_kh_bct'])?"0":$item['slc_ts_pc_kh_bct']);
                $activeSheet->setCellValue('BX' . strval($r), !isset($item['slc_chat_bc_kh_bcp_tl'])?"0":$item['slc_chat_bc_kh_bcp_tl']);
                $activeSheet->setCellValue('BY' . strval($r), !isset($item['slc_chat_bc_kh_bcp_ktl'])?"0":$item['slc_chat_bc_kh_bcp_ktl']);
                $activeSheet->setCellValue('BZ' . strval($r), !isset($item['slc_ts_pc_kh_bcp'])?"0":$item['slc_ts_pc_kh_bcp']);
                $activeSheet->setCellValue('CA' . strval($r), !isset($item['slc_chat_cskh_tl'])?"0":$item['slc_chat_cskh_tl']);
                $activeSheet->setCellValue('CB' . strval($r), !isset($item['slc_chat_cskh_ktl'])?"0":$item['slc_chat_cskh_ktl']);
                $activeSheet->setCellValue('CC' . strval($r), !isset($item['slc_ts_pc_kh_cskh'])?"0":$item['slc_ts_pc_kh_cskh']);
                $activeSheet->setCellValue('CD' . strval($r), !isset($item['slc_chat_ccp_tl'])?"0":$item['slc_chat_ccp_tl']);
                $activeSheet->setCellValue('CE' . strval($r), !isset($item['slc_chat_ccp_ktl'])?"0":$item['slc_chat_ccp_ktl']);
                $activeSheet->setCellValue('CF' . strval($r), !isset($item['slc_ts_pc_kh_bh'])?"0.00%":$item['slc_ts_pc_kh_bh']);
                $r++;
            }
            $activeSheet->getColumnDimension('A')->setAutoSize(true);
            $activeSheet->getColumnDimension('B')->setAutoSize(true);
            $activeSheet->getColumnDimension('C')->setAutoSize(true);
            $activeSheet->getColumnDimension('D')->setAutoSize(true);
            $activeSheet->getColumnDimension('E')->setAutoSize(true);
            $activeSheet->getColumnDimension('F')->setAutoSize(true);
            $activeSheet->getColumnDimension('G')->setAutoSize(true);
            $activeSheet->getColumnDimension('H')->setAutoSize(true);
            $activeSheet->getColumnDimension('I')->setAutoSize(true);
            $activeSheet->getColumnDimension('J')->setAutoSize(true);
            $activeSheet->getColumnDimension('K')->setAutoSize(true);
            $activeSheet->getColumnDimension('L')->setAutoSize(true);
            $activeSheet->getColumnDimension('M')->setAutoSize(true);
            $activeSheet->getColumnDimension('N')->setAutoSize(true);
            $activeSheet->getColumnDimension('O')->setAutoSize(true);
            $activeSheet->getColumnDimension('P')->setAutoSize(true);
            $activeSheet->getColumnDimension('Q')->setAutoSize(true);
            $activeSheet->getColumnDimension('R')->setAutoSize(true);
            $activeSheet->getColumnDimension('S')->setAutoSize(true);
            $activeSheet->getColumnDimension('T')->setAutoSize(true);
            $activeSheet->getColumnDimension('U')->setAutoSize(true);
            $activeSheet->getColumnDimension('V')->setAutoSize(true);
            $activeSheet->getColumnDimension('W')->setAutoSize(true);
            $activeSheet->getColumnDimension('X')->setAutoSize(true);
            $activeSheet->getColumnDimension('Y')->setAutoSize(true);
            $activeSheet->getColumnDimension('Z')->setAutoSize(true);
            $activeSheet->getColumnDimension('AA')->setAutoSize(true);
            $activeSheet->getColumnDimension('AB')->setAutoSize(true);
            $activeSheet->getColumnDimension('AC')->setAutoSize(true);
            $activeSheet->getColumnDimension('AD')->setAutoSize(true);
            $activeSheet->getColumnDimension('AE')->setAutoSize(true);
            $activeSheet->getColumnDimension('AF')->setAutoSize(true);
            $activeSheet->getColumnDimension('AG')->setAutoSize(true);
            $activeSheet->getColumnDimension('AH')->setAutoSize(true);
            $activeSheet->getColumnDimension('AI')->setAutoSize(true);
            $activeSheet->getColumnDimension('AJ')->setAutoSize(true);
            $activeSheet->getColumnDimension('AK')->setAutoSize(true);
            $activeSheet->getColumnDimension('AL')->setAutoSize(true);
            $activeSheet->getColumnDimension('AM')->setAutoSize(true);
            $activeSheet->getColumnDimension('AN')->setAutoSize(true);
            $activeSheet->getColumnDimension('AO')->setAutoSize(true);
            $activeSheet->getColumnDimension('AP')->setAutoSize(true);
            $activeSheet->getColumnDimension('AQ')->setAutoSize(true);
            $activeSheet->getColumnDimension('AR')->setAutoSize(true);
            $activeSheet->getColumnDimension('AS')->setAutoSize(true);
            $activeSheet->getColumnDimension('AT')->setAutoSize(true);
            $activeSheet->getColumnDimension('AU')->setAutoSize(true);
            $activeSheet->getColumnDimension('AV')->setAutoSize(true);
            $activeSheet->getColumnDimension('AW')->setAutoSize(true);
            $activeSheet->getColumnDimension('AX')->setAutoSize(true);
            $activeSheet->getColumnDimension('AY')->setAutoSize(true);
            $activeSheet->getColumnDimension('AZ')->setAutoSize(true);
            $activeSheet->getColumnDimension('BA')->setAutoSize(true);
            $activeSheet->getColumnDimension('BB')->setAutoSize(true);
            $activeSheet->getColumnDimension('BC')->setAutoSize(true);
            $activeSheet->getColumnDimension('BD')->setAutoSize(true);
            $activeSheet->getColumnDimension('BE')->setAutoSize(true);
            $activeSheet->getColumnDimension('BF')->setAutoSize(true);
            $activeSheet->getColumnDimension('BG')->setAutoSize(true);
            $activeSheet->getColumnDimension('BH')->setAutoSize(true);
            $activeSheet->getColumnDimension('BI')->setAutoSize(true);
            $activeSheet->getColumnDimension('BJ')->setAutoSize(true);
            $activeSheet->getColumnDimension('BK')->setAutoSize(true);
            $activeSheet->getColumnDimension('BL')->setAutoSize(true);
            $activeSheet->getColumnDimension('BM')->setAutoSize(true);
            $activeSheet->getColumnDimension('BN')->setAutoSize(true);
            $activeSheet->getColumnDimension('BO')->setAutoSize(true);
            $activeSheet->getColumnDimension('BP')->setAutoSize(true);
            $activeSheet->getColumnDimension('BQ')->setAutoSize(true);
            $activeSheet->getColumnDimension('BR')->setAutoSize(true);
            $activeSheet->getColumnDimension('BS')->setAutoSize(true);
            $activeSheet->getColumnDimension('BT')->setAutoSize(true);
            $activeSheet->getColumnDimension('BU')->setAutoSize(true);
            $activeSheet->getColumnDimension('BV')->setAutoSize(true);
            $activeSheet->getColumnDimension('BW')->setAutoSize(true);
            $activeSheet->getColumnDimension('BX')->setAutoSize(true);
            $activeSheet->getColumnDimension('BY')->setAutoSize(true);
            $activeSheet->getColumnDimension('BZ')->setAutoSize(true);
            $activeSheet->getColumnDimension('CA')->setAutoSize(true);
            $activeSheet->getColumnDimension('CB')->setAutoSize(true);
            $activeSheet->getColumnDimension('CC')->setAutoSize(true);
            $activeSheet->getColumnDimension('CD')->setAutoSize(true);
            $activeSheet->getColumnDimension('CE')->setAutoSize(true);
            $activeSheet->getColumnDimension('CF')->setAutoSize(true);
        }
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ],
            ],
        ];
        $activeSheet->getStyle(
            'A5:' .
                $activeSheet->getHighestColumn() .
                $activeSheet->getHighestRow()
        )->applyFromArray($styleArray);
        $fileName = 'report_sdk-' . date('ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        ob_start();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        // ob_end_clean();
      Yii::$app->response->headers->set('Pragma', 'no-cache');
      Yii::$app->response->headers->set('Expires', '0');
      ob_end_flush(); // Tắt bộ đệm
        $writer->save('php://output');
        die();
    }
  }
  public function createSlm($data, $i)
  {
    $data[$i]['slm_acc_op_kh'] = 0;
    $data[$i]['slm_acc_op_bt'] = 0;
    $data[$i]['slm_acc_op_nvt'] = 0;
    $data[$i]['slm_acc_op_cskh'] = 0;
    $data[$i]['slm_acc_op_ccp'] = 0;
    $data[$i]['slm_acc_chat_kh'] = 0;
    $data[$i]['slm_acc_chat_bt'] = 0;
    $data[$i]['slm_acc_chat_nvt'] = 0;
    $data[$i]['slm_acc_chat_cskh'] = 0;
    $data[$i]['slm_acc_chat_ccp'] = 0;
    $data[$i]['slm_chat_bc_kh_bt'] = 0;
    $data[$i]['slm_chat_bc_bt_kh'] = 0;
    $data[$i]['slm_chat_bc_kh_bct_tl'] = 0;
    $data[$i]['slm_chat_bc_kh_bct_ktl'] = 0;
    $data[$i]['slm_chat_bc_kh_bcp_tl'] = 0;
    $data[$i]['slm_chat_bc_kh_bcp_ktl'] = 0;
    $data[$i]['slm_chat_cskh_tl'] = 0;
    $data[$i]['slm_chat_cskh_ktl'] = 0;
    $data[$i]['slm_chat_ccp_tl'] = 0;
    $data[$i]['slm_chat_ccp_ktl'] = 0;
    return $data;
  }
  public function updateSlm($data, $i, $slm, $j)
  {
    $data[$i]['slm_acc_op_kh'] = $slm[$j]['sum(slm_acc_op_kh)'];
    $data[$i]['slm_acc_op_bt'] = $slm[$j]['sum(slm_acc_op_bt)'];
    $data[$i]['slm_acc_op_nvt'] = $slm[$j]['sum(slm_acc_op_nvt)'];
    $data[$i]['slm_acc_op_cskh'] = $slm[$j]['sum(slm_acc_op_cskh)'];
    $data[$i]['slm_acc_op_ccp'] = $slm[$j]['sum(slm_acc_op_ccp)'];
    $data[$i]['slm_chat_bc_kh_bt'] = $slm[$j]['sum(slm_chat_bc_kh_bt)'];
    $data[$i]['slm_chat_bc_bt_kh'] = $slm[$j]['sum(slm_chat_bc_bt_kh)'];
    $data[$i]['slm_chat_bc_kh_bct_tl'] = $slm[$j]['sum(slm_chat_bc_kh_bct_tl)'];
    $data[$i]['slm_chat_bc_kh_bct_ktl'] = $slm[$j]['sum(slm_chat_bc_kh_bct_ktl)'];
    $data[$i]['slm_chat_bc_kh_bcp_tl'] = $slm[$j]['sum(slm_chat_bc_kh_bcp_tl)'];
    $data[$i]['slm_chat_bc_kh_bcp_ktl'] = $slm[$j]['sum(slm_chat_bc_kh_bcp_ktl)'];
    $data[$i]['slm_chat_cskh_tl'] = $slm[$j]['sum(slm_chat_cskh_tl)'];
    $data[$i]['slm_chat_cskh_ktl'] = $slm[$j]['sum(slm_chat_cskh_ktl)'];
    $data[$i]['slm_chat_ccp_tl'] = $slm[$j]['sum(slm_chat_ccp_tl)'];
    $data[$i]['slm_chat_ccp_ktl'] = $slm[$j]['sum(slm_chat_ccp_ktl)'];
    return $data;
  }
  public function createTsSlm($data, $i)
  {
    $data[$i]['slm_ts_dm'] = $data[$i]['slm_acc_op_kh'] + $data[$i]['slm_acc_op_bt'] + $data[$i]['slm_acc_op_nvt'] + $data[$i]['slm_acc_op_cskh'] + $data[$i]['slm_acc_op_ccp'];
    $data[$i]['slm_ts_psc_acc'] = $data[$i]['slm_acc_chat_kh'] + $data[$i]['slm_acc_chat_bt'] + $data[$i]['slm_acc_chat_nvt'] + $data[$i]['slm_acc_chat_cskh'] + $data[$i]['slm_acc_chat_ccp'];
    $data[$i]['slm_ts_psc_tl'] = ($data[$i]['slm_ts_dm'] == 0) ? "0.00" : number_format(($data[$i]['slm_ts_psc_acc'] / $data[$i]['slm_ts_dm']) * 100, 2);
    $data[$i]['slm_ts_pc_kh_bct'] = ($data[$i]['slm_chat_bc_kh_bct_tl'] + $data[$i]['slm_chat_bc_kh_bct_ktl'] == 0) ? "0.00" : number_format($data[$i]['slm_chat_bc_kh_bct_tl'] / ($data[$i]['slm_chat_bc_kh_bct_tl'] + $data[$i]['slm_chat_bc_kh_bct_ktl']) * 100, 2);
    $data[$i]['slm_ts_pc_kh_bcp'] = ($data[$i]['slm_chat_bc_kh_bcp_tl'] + $data[$i]['slm_chat_bc_kh_bcp_ktl'] == 0) ? "0.00" : number_format($data[$i]['slm_chat_bc_kh_bcp_tl'] / ($data[$i]['slm_chat_bc_kh_bcp_tl'] + $data[$i]['slm_chat_bc_kh_bcp_ktl']) * 100, 2);
    $data[$i]['slm_ts_pc_kh_cskh'] = ($data[$i]['slm_chat_cskh_tl'] + $data[$i]['slm_chat_cskh_ktl'] == 0) ? "0.00" : number_format($data[$i]['slm_chat_cskh_tl'] / ($data[$i]['slm_chat_cskh_tl'] + $data[$i]['slm_chat_cskh_ktl']) * 100, 2);
    $data[$i]['slm_ts_pc_kh_bh'] = ($data[$i]['slm_chat_ccp_tl'] + $data[$i]['slm_chat_ccp_ktl'] == 0) ? "0.00" : number_format($data[$i]['slm_chat_ccp_tl'] / ($data[$i]['slm_chat_ccp_tl'] + $data[$i]['slm_chat_ccp_ktl']) * 100, 2);
    return $data;
  }
  public function convertDataProvinder($data){
    $data = new ArrayDataProvider([
      'allModels' => $data,
      'pagination' => new Pagination([
        'defaultPageSize' => 100,
        'totalCount' => count($data),
      ]),
      'sort' => [
        'attributes' => ['id_province', 'province'],
      ],
    ]);
    return $data;
  }
}