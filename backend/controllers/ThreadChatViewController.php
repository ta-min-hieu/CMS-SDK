<?php

namespace backend\controllers;

use backend\models\Queue;
use backend\models\ThreadChatView;
use backend\models\ThreadChatViewSearch;
use backend\models\User;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * BandWidthController implements the CRUD actions for BandWidth model.
 */
class ThreadChatViewController extends Controller
{
  /**
   * Lists all Log models.
   * @return mixed
   */
  public function actionIndex()
  {
    $searchModel = new ThreadChatViewSearch();
    if (
      isset($_GET['queue_name']) && $_GET['queue_name'] != null && isset($_GET['quy']) && $_GET['quy'] != null
      && isset($_GET['year']) && $_GET['year'] != null
    ) {
      $date = explode("|", $_GET['quy']);
      $start_date = $_GET['year'] . "-" . $date[0];
      $end_date = $_GET['year'] . "-" . $date[1];
      $queue_name = $_GET['queue_name'];
      $trcv = new ThreadChatView();
      $total = $trcv->getTotal($start_date, $end_date, $queue_name);
      $missing = $trcv->getMissing($start_date, $end_date, $queue_name);
      $done = $trcv->getDone($start_date, $end_date, $queue_name);
      $processing = $trcv->getProcessing($start_date, $end_date, $queue_name);
      $agent = $trcv->getAgent($start_date, $end_date, $queue_name);
      for($i=0;$i<count($total);$i++){
        $a[$i]['stt'] = $i+1;
        $a[$i]['month'] = explode("-", $total[$i]['date_check'])[1];
        $a[$i]['date'] = $total[$i]['date_check'];
        $a[$i]['total'] = isset($total[$i]['count'])?$total[$i]['count']:0;
        $a[$i]['done'] = isset($done[$i]['count'])?$done[$i]['count']:0;
        $a[$i]['processing'] = isset($processing[$i]['count'])?$processing[$i]['count']:0;
        $a[$i]['agent'] = isset($agent[$i]['count'])?$agent[$i]['count']:0;
        for($j=0;$j<count($missing);$j++){
          if($total[$i]['date_check'] == $missing[$j]['date_check']){
            $a[$i]['missing'] = $missing[$j]['count(thread_id)'];
            break;
          }
          else
            $a[$i]['missing'] = 0;
        }
      }
    }
    else
      $a = null;

    $arrActiveDevice = new ArrayDataProvider([
      'allModels' => $a,
      'pagination' => false,
      'sort' => [
        'attributes' => [],
      ],
    ]);
    return $this->render('index', [
      'searchModel' => $searchModel,
      'arrActiveDevice' => $arrActiveDevice,
    ]);
  }

  public function actionAjaxSearch($q = null, $ids = [])
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $out = ['results' => ['id' => '', 'text' => '']];
    if (!is_null($q)) {
      if (User::findOne(\Yii::$app->user->id)->id_province == null) {
        $data = Queue::find()
          ->select('id_department as id, disp_name as text, queue_name')
          ->where([
            'OR',
            ['id_department' => $q],
            ['like', 'disp_name', $q],
          ])
          ->limit(20)
          ->asArray()
          ->all();
      } else {
        $data = Queue::find()
          ->select('id_department as id, disp_name as text, queue_name')
          ->innerJoin('department', 'department.id_department = queue.id_department')
          ->where(['id_province' => User::findOne(\Yii::$app->user->id)->id_province])
          ->andWhere([
            'OR',
            ['id_department' => $q],
            ['like', 'disp_name', $q],
          ])
          ->limit(20)
          ->asArray()
          ->all();
      }
      $out['results'] = array_values($data);
      for ($i = 0; $i < count($out['results']); $i++) {
        $out['results'][$i]['text'] = $out['results'][$i]['id'] . ' - ' . $out['results'][$i]['text'];
        $out['results'][$i]['id'] = $data[$i]['queue_name'];
      }
    } else {
      $data = Queue::find()
        ->select('id_department => $q as id, disp_name AS text, queue_name')
        ->where(['disp_name' => 'CSKH TCT Giám sát'])
        ->asArray()
        ->all();
      $out['results'] = array_values($data);
      for ($i = 0; $i < count($out['results']); $i++) {
        $out['results'][$i]['text'] = $out['results'][$i]['id'] . ' - ' . $out['results'][$i]['text'];
        $out['results'][$i]['id'] = $data[$i]['queue_name'];
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
      ]);
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $headers = [
          Yii::t('backend', 'STT'),
          Yii::t('backend', 'Tháng'),
          Yii::t('backend', 'Ngày'),
          Yii::t('backend', 'Tổng số lượng Thread'),
          Yii::t('backend', 'Số lượng Done Thread'),
          Yii::t('backend', 'Số lượng Missing Thread'),
          Yii::t('backend', 'Số lượng Processing Thread'),
          Yii::t('backend', 'Số lượng điện thoại viên tiếp nhận'),
        ];
        $activeSheet
            ->fromArray(
                $headers,  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
            );
        $activeSheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true, 'size' => 11
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);
        $r = 2;
        if ($dataProvider->getTotalCount()) {
            foreach ($dataProvider->getModels()["allModels"] as $item) {
                $activeSheet->setCellValue('A' . strval($r), $item['stt']);
                $activeSheet->setCellValue('B' . strval($r), $item['month']);
                $activeSheet->setCellValue('C' . strval($r), $item['date']);
                $activeSheet->setCellValue('D' . strval($r), isset($item['total'])?$item['total']:0);
                $activeSheet->setCellValue('E' . strval($r), isset($item['done'])?$item['done']:0);
                $activeSheet->setCellValue('F' . strval($r), isset($item['missing'])?$item['missing']:0);
                $activeSheet->setCellValue('G' . strval($r), isset($item['processing'])?$item['processing']:0);
                $activeSheet->setCellValue('H' . strval($r), isset($item['agent'])?$item['agent']:0);
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
}