<?php

namespace backend\controllers;

use backend\models\DailyReportSearch;
use backend\models\ReportInfoAgentAtQueueSearch;
use backend\models\ReportInfoAgentSpChatSearch;
use backend\models\ReportThreadChat2QueueSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

/**
 * BandWidthController implements the CRUD actions for BandWidth model.
 */
class DailyReportController extends Controller
{
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['post'],
        ],
      ],
    ];
  }

  /**
   * Lists all Log models.
   * @return mixed
   */
  public function actionIndex()
  {
    $searchModel = new DailyReportSearch();
    $arrActiveDevice = array();
    if (isset($_GET['date']) && isset($_GET['type']) && isset($_GET['queue_name'])
     && $_GET['queue_name'] != null  && $_GET['date'] != null  && $_GET['type'] != null) {
      $date_range = $_GET['date'];
      $date_arr = explode(" - ", $date_range);
      $from_date = $date_arr[0];
      $to_date = $date_arr[1];
      $command = Yii::$app->dbsdk;
      $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
      $queue_name = (new \yii\db\Query())
      ->select(['queue.queue_name'])
      ->from($dbn . '.queue')
      ->where(['queue.id' => $_GET['queue_name']])
      ->one();
      $queue_name = $queue_name['queue_name'];
      $a = $this->getDailyReport($queue_name, $from_date, $to_date, $_GET['type']);
    }
    else
      $a = null;
    if(isset($a->data) && $a->code == '200'){
      if(isset($a->data) && count($a->data)){
        for ($i = 0; $i < count($a->data[0]->data_report); $i++) {
          $arrActiveDevice[$i]['date'] = $a->data[0]->data_report[$i]->date;
          $arrActiveDevice[$i]['missing_thread'] = $a->data[0]->data_report[$i]->report->missing_thread;
          $arrActiveDevice[$i]['done_thread'] = $a->data[0]->data_report[$i]->report->done_thread;
          $arrActiveDevice[$i]['total'] = $a->data[0]->data_report[$i]->report->total;
          $arrActiveDevice[$i]['total_thread_agent'] = $a->data[0]->data_report[$i]->report->total_thread_agent;
        }
      }
    }
    else
      $arrActiveDevice = null;
      // echo"<pre>";var_dump($arrActiveDevice);die();
    $arrActiveDevice = new ArrayDataProvider([
      'allModels' => $arrActiveDevice,
      'pagination' => false,
      'sort' => [
        'attributes' => [],
      ],
    ]);
    // echo"<pre>";var_dump($_GET);die();
    
    return $this->render('index', [
      'arrActiveDevice' => $arrActiveDevice,
      'searchModel' => $searchModel,
    ]);
  }
  //$queue_name, $from_date, $to_date, $type
  public function getDailyReport($queue_name, $from_date, $to_date, $type)
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('POST')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/report/by/date?queue_name='.$queue_name.'&from_date='.$from_date.'&to_date='.$to_date.'&type='.$type)
      ->send();

    $dataRespone = $response->getContent();
    $data = json_decode($dataRespone);
    return $data;
  }
}