<?php

namespace backend\controllers;

use backend\models\BandWidth;
use backend\models\Cache;
use backend\models\Music;
use backend\models\Status;
use backend\models\TimeUsing;
use backend\models\View;
use backend\models\ViewPhoneNumber;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\httpclient\Client;
use yii;
use yii\web\NotFoundHttpException;
/**
 * BandWidthController implements the CRUD actions for BandWidth model.
 */
class LogTestController extends Controller
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
  public function getDataOnOffChatService()
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('GET')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('http://42.96.40.220:8080/api/status')
      ->send();

    $dataRespone = $response->getContent();
    $data1 = json_decode($dataRespone);
    // var_dump($data);
    // die();
    return $data1;
  }

  public function getDataUserConnectChatService()
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('GET')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('http://42.96.40.220:8080/api/connected_users_number')
      ->send();

    $dataRespone = $response->getContent();
    $data2 = json_decode($dataRespone);
    // var_dump($data);
    // die();
    return $data2;
  }

  public function getDataUserInfor($user)
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('GET')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('http://42.96.40.220:8080/api/user_sessions_info?user='.$user.'&host=vnpost')
      ->send();

    $dataRespone = $response->getContent();
    $data3 = json_decode($dataRespone);
    // var_dump($data);
    // die();
    return $data3;
  }

  public function getDataDisconnect($userdis)
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('GET')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('http://42.96.40.220:8080/api/kick_user?user='.$userdis.'&host=vnpost')
      ->send();

    $dataRespone = $response->getContent();
    $data5 = json_decode($dataRespone);
    // var_dump($data);
    // die();
    return $data5;
  }

  public function getDataListChatInstance()
  {
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('GET')
      ->addHeaders(['content-type' => 'application/json'])
      ->setUrl('http://42.96.40.220:8080/api/list_cluster')
      ->send();

    $dataRespone = $response->getContent();
    $data4 = json_decode($dataRespone);
    // var_dump($data);
    // die();
    return $data4;
  }

  public function getQueueName($phone_number)
  {
    $command = Yii::$app->dbsdk;
    $dbn = substr($command->dsn,(strpos($command->dsn, 'dbname=')) + 7);
    $data6 = (new \yii\db\Query())
      ->select(['queue.queue_name','queue.disp_name'])
      ->from($dbn . '.staff')
      ->innerJoin($dbn . '.queue_agent', 'staff.username = queue_agent.agent_name')
      ->innerJoin($dbn . '.queue', 'queue.queue_name = queue_agent.queue_name')
      ->where(['staff.phone_number' => $phone_number])
      ->one();
    return $data6;
  }

  public function actionIndex()
  {
    $data1 = $this->getDataOnOffChatService();
    $data2 = $this->getDataUserConnectChatService();
    $data3 = null;
    if($this->request->get()){
    if(isset(($_GET['user'])) && $_GET['user'] != null)
      $data3 = $this->getDataUserInfor($_GET['user']);
    }
    $data4 = $this->getDataListChatInstance();
    $data5 = null;
    if($this->request->get()){
    if(isset(($_GET['userdis'])) && $_GET['userdis'] != null)
    // var_dump($_GET['userdis']);die();
      $data5 = $this->getDataDisconnect($_GET['userdis']);
    }
    $data6 = null;
    if(isset(($_GET['phone_number'])) && $_GET['phone_number'] != null){
      $data6 = $this->getQueueName($_GET['phone_number']);
    }
    // var_dump($data6);die();
    // echo "<pre>";
    // var_dump($data3);
    // die();
    
    return $this->render('index', [
      'data1' => $data1,
      'data2' => $data2,
      'data3' => $data3,
      'data4' => $data4,
      'data5' => $data5,
      'data6' => $data6,
    ]);
  }
}
