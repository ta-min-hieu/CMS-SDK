<?php

namespace backend\controllers;

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
class ReportThreadChat2QueueController extends Controller
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
    $searchModel = new ReportThreadChat2QueueSearch();
    $arrActiveDevice = array();
    $arrHeader = array();
    if(isset($_GET['date']) && isset($_GET['type'])){
      if(!isset($_GET['queue_name']) || $_GET['queue_name'] == '')
        $queue_name = null;
      else{
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        $queue_name = (new \yii\db\Query())
        ->select(['queue.queue_name'])
        ->from($dbn . '.queue')
        ->where(['queue.id' => $_GET['queue_name']])
        ->one();
        $queue_name = $queue_name['queue_name'];
      }
      $a = $this->getThreadChat2Queue($queue_name, $_GET['date'], $_GET['type']);
    }
    else
      $a = $this->getThreadChat2Queue(null, date('Y-m-d'), 'CSKH');
    if(isset($a->data) && $a->code == '200'){
      if(!isset($a->data->queue)){
        $arrHeader['type'] = "Loại: ".$a->data->type;
        $arrHeader['total'] = "Tổng số Queue: ".$a->data->total;
      }
      else{
        $arrHeader['type'] = "Loại: ".$a->data->queue->type;
        $arrHeader['disp_name'] = "Tên Queue: ".$a->data->queue->disp_name;
        $arrHeader['thumb'] = $a->data->queue->thumb;
      }
      $arrActiveDevice[0]['missing_thread'] = $a->data->info->missing_thread;
      $arrActiveDevice[0]['missing_thread_time'] = $a->data->info->missing_thread_time;
      $arrActiveDevice[0]['waiting_thread'] = $a->data->info->waiting_thread;
      $arrActiveDevice[0]['processing_thread'] = $a->data->info->processing_thread;
      $arrActiveDevice[0]['done_thread'] = $a->data->info->done_thread;
      $arrActiveDevice[0]['waiting_time_accepted'] = $a->data->info->waiting_time_accepted;
      $arrActiveDevice[0]['waiting_time'] = $a->data->info->waiting_time;
      $arrActiveDevice[0]['done_chattime'] = $a->data->info->done_chattime;
      $arrActiveDevice[0]['processing_chattime'] = $a->data->info->processing_chattime;
      $arrActiveDevice[0]['online_agent'] = $a->data->info->online_agent;
      $arrActiveDevice[0]['processing_thread_agent'] = $a->data->info->processing_thread_agent;
      $arrActiveDevice[0]['thread_chat_count_5min'] = $a->data->info->thread_chat_count_5min;

    }
    else{
      $arrActiveDevice = null;
      $arrHeader = null;
    }
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
      'arrHeader' => $arrHeader,
      'searchModel' => $searchModel,
    ]);
  }
  //$queue_name, $date, $type
  public function getThreadChat2Queue($queue_name, $date, $type)
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('POST')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/report/info?queue_name='.$queue_name.'&date='.$date.'&type='.$type)
      ->send();

    $dataRespone = $response->getContent();
    $data = json_decode($dataRespone);
    return $data;
  }
}