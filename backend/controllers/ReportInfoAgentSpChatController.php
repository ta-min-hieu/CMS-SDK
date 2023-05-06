<?php

namespace backend\controllers;

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
class ReportInfoAgentSpChatController extends Controller
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
    $searchModel = new ReportInfoAgentSpChatSearch();
    $arrActiveDevice = array();
    $arrayheader = array();
    // $a = $this->getInfoAgentSpChat();
    // echo"<pre>";var_dump(count($a->data[0]->Agents));die();
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
      $a = $this->getInfoAgentSpChat($queue_name, $_GET['date'], $_GET['type']);
    }
    else
      $a = null;
    
    if(isset($a->data) && $a->code == '200'){
      $arrayheader['thumb'] = $a->data[0]->queue->thumb;
      $arrayheader['disp_name'] = "Tên Queue: ".$a->data[0]->queue->disp_name;
      $arrayheader['type'] = "Loại Queue: ".$a->data[0]->queue->type;
      if(isset($a->data[0]->Agents) && count($a->data[0]->Agents)){
        for ($i = 0; $i < count($a->data[0]->Agents); $i++) {
          $arrActiveDevice[$i]['sUserID'] = $a->data[0]->Agents[$i]->sUserID;
          $arrActiveDevice[$i]['phone_number'] = $a->data[0]->Agents[$i]->phone_number;
          $arrActiveDevice[$i]['processing_thread'] = $a->data[0]->Agents[$i]->processing_thread;
        }
      }
    }
    else
      $arrActiveDevice = null;
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
      'arrayheader' => $arrayheader,
      'searchModel' => $searchModel,
    ]);
  }
  //$queue_name, $date, $type
  public function getInfoAgentSpChat($queue_name, $date, $type)
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('POST')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/report/agent?queue_name='.$queue_name.'&type='.$type.'&date='.$date)
      ->send();

    $dataRespone = $response->getContent();
    $data = json_decode($dataRespone);
    return $data;
  }
}