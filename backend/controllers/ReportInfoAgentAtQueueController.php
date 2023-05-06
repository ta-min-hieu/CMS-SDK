<?php

namespace backend\controllers;

use backend\models\ReportInfoAgentAtQueueSearch;
use backend\models\ReportThreadChat2QueueSearch;
use backend\models\Staff;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

/**
 * BandWidthController implements the CRUD actions for BandWidth model.
 */
class ReportInfoAgentAtQueueController extends Controller
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
    $searchModel = new ReportInfoAgentAtQueueSearch();
    $arrActiveDevice = array();

    if (isset($_GET['date']) && isset($_GET['phone_number']) && $_GET['phone_number']!=null) {
      $date_range = $_GET['date'];
      $date_arr = explode(" - ", $date_range);
      $start_date = strtotime($date_arr[0])."000";
      $end_date = strtotime($date_arr[1])."000";

      $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        $username = (new \yii\db\Query())
        ->select(['staff.username'])
        ->from($dbn . '.staff')
        ->where(['staff.phone_number' => $_GET['phone_number']])
        ->one();
        $username = $username['username'];
        if(!isset($_GET['havingOrder']))
          $_GET['havingOrder'] = null;
        $a = $this->getInfoAgentAtQueue($username, $start_date, $end_date, $_GET['havingOrder']);
    }
    else{
      $a = null;
    }
    if(isset($a->data->data) && $a->code == '200'){
      for($i=0;$i<count($a->data->data);$i++){
        $arrActiveDevice[$i]['thumb'] = $a->data->data[$i]->queue->thumb;
        $arrActiveDevice[$i]['disp_name'] = $a->data->data[$i]->queue->disp_name;
        $arrActiveDevice[$i]['type'] = $a->data->data[$i]->queue->type;
        $arrActiveDevice[$i]['all'] = $a->data->data[$i]->all;
        $arrActiveDevice[$i]['new'] = $a->data->data[$i]->new;
        $arrActiveDevice[$i]['opened'] = $a->data->data[$i]->opened;
        $arrActiveDevice[$i]['closed'] = $a->data->data[$i]->closed;
        $arrActiveDevice[$i]['acd'] = $a->data->data[$i]->acd;
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
      'searchModel' => $searchModel,
    ]);
  }
  //$username, $start_date, $end_date, $havingOrder
  public function getInfoAgentAtQueue($username, $start_date, $end_date, $havingOrder)
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('POST')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/report/thread?agent='.$username.'&havingOrder='.$havingOrder.'&startedAt='.$start_date.'&endedAt='.$end_date)
      ->send();

    $dataRespone = $response->getContent();
    $data = json_decode($dataRespone);
    return $data;
  }
  public function actionAjaxSearch($q = null, $ids = [])
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $out = ['results' => ['id' => '', 'text' => '']];
    if (!is_null($q)) {
      $data = Staff::find()
        ->select('phone_number as id, staff_name as text')
        ->where([
          'OR',
          ['like', 'phone_number', $q],
          ['like', 'staff_name', $q],
        ])
        ->limit(100)
        ->asArray()
        ->all();
      $out['results'] = array_values($data);
      for ($i = 0; $i < count($out['results']); $i++) {
        $out['results'][$i]['text'] = $out['results'][$i]['id'] . ' - ' . $out['results'][$i]['text'];
      }
    } elseif (is_array($ids) && count($ids)) {
      $data = Staff::find()
        ->select('phone_number as id, staff_name AS text')
        ->where([
          'OR',
          ['like', 'phone_number', $ids],
          ['like', 'staff_name', $ids],
        ])
        ->asArray()
        ->all();
      for ($i = 0; $i < count($out['results']); $i++)
        $out['results'][$i]['text'] = $out['results'][$i]['id'] . ' - ' . $out['results'][$i]['text'];
    }
    return $out;
  }
}