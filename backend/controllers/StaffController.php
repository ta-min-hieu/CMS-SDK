<?php

namespace backend\controllers;
use backend\models\Queue;
use backend\models\StaffQueue;
use backend\models\Staff;
use backend\models\Users;
use backend\models\Customer;
use backend\models\StaffSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use yii\web\Response;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use backend\models\Department;
use yii\helpers\Html;
use backend\models\Log;
use backend\models\User;
use yii\httpclient\Client;

/**
 * StaffController implements the CRUD actions for Staff model.
 */
class StaffController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Staff models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StaffSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndexListStaff()
    {
        $searchModel = new StaffSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('indexListStaff', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionViewList()
    {
        return $this->redirect('index', [
        ]);
    }
    /**
     * Displays a single Staff model.
     * @param int $id_staff Id staff
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'isAjax' => Yii::$app->request->isAjax,
        ]);
    }

    /**
     * Creates a new Staff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Staff();
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < 10; $i++) {
            $model->username .= $chars[rand(0, $size - 1)];
        }
        $model->hostname = "vnpost";
        $model->username .= time() * 1000;
        $form_values = Yii::$app->request->post();
        if ($model->load($form_values)) {
            if (substr($model->phone_number, 0, 2) == '84') {
                $model->phone_number = '0' . substr($model->phone_number, 2);
            }
            $customer = Customer::find()
                ->where(['username' => $model->username])
                ->one();
            if ($customer == null) {
                $department = Department::find()
                ->select('id_province')
                ->where(['id_department' => $model->id_department])
                ->one();
                $model1 = new Customer();
                $model1->username = $model->username;
                $model1->sUserID = $model->sUserID;
                $model1->serviceID = $model->hostname;
                $model1->phonenumber = $model->phone_number;
                $model1->type_user = 2;
                $model1->state = 1;
                $model1->app_id = '';
                $model1->fullname = $model->staff_name;
                if($department != null){
                    // var_dump($department['id_province']);die();
                    $model1->id_province = $department['id_province'];
                }
            }

            $users = Users::find()
                ->where(['username' => $model->username])
                ->one();
            if ($users == null) {
                $model2 = new Users();
                $model2->username = $model->username;
                $model2->server_host = $model->hostname;
                $model2->password = sha1($model->username);
            }

            if ($model->save() && $model1->save(false) && $model2->save(false)) {

                Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                Log::saveDBLog(Yii::$app->user->identity->id, $model->id_staff, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Staff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_staff Id staff
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $staff = Staff::find()
            ->where(['id_staff' => $id])
            ->one()->username;
        $model->sUserID = Customer::find()
            ->where(['username' => $staff])
            ->one()->sUserID;
            
        if($model->sUserID == null)
            $model->sUserID = '';
        if ($this->request->isPost && $model->load($this->request->post())) {
            if (substr($model->phone_number, 0, 2) == '84') {
                $model->phone_number = '0' . substr($model->phone_number, 2);
            }
            $model1 = Customer::findOne(['username' => $staff]);
            $model1->sUserID = $model->sUserID;
            $model1->state = $model->status;
            $model1->fullname = $model->staff_name;
            $department = Department::find()
                ->select('id_province')
                ->where(['id_department' => $model->id_department])
                ->one();
            if ($department != null) {
                // var_dump($department['id_province']);die();
                $model1->id_province = $department['id_province'];
            }
            if ($model->save() && $model1->save(false)) {
                \Yii::$app->session->setFlash('info', \Yii::t('backend', 'Update account successfully!'));
                Log::saveDBLog(Yii::$app->user->identity->id, $model->id_staff, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
                $this->deleteCacheAgent($model->username);
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Staff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_staff Id staff
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status=0;
        $customer = Customer::findOne(['username' => $model->username]);
        if ($customer != null){
            $customer->state=0;
            $customer->save(false);
        }
        $model->save(false);
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn,(strpos($command->dsn, 'dbname=')) + 7);
        $query1 = (new \yii\db\Query())
            ->select(['queue_agent.agent_name'])
            ->from($dbn . '.queue_agent')
            ->innerJoin($dbn . '.staff', 'staff.username = queue_agent.agent_name')
            ->where(['staff.id_staff' => $id])
            ->one();
        if($query1 != false){
            $agent_name = $query1["agent_name"];
            Yii::$app->db->createCommand("
            DELETE FROM $dbn.queue_agent
            WHERE agent_name = '$agent_name'
            ")->execute();
        }

        Yii::$app->session->setFlash('success', 'Delete account successfully!');
        Log::saveDBLog(Yii::$app->user->identity->id, $model->id_staff, 'delete', Inflector::camel2words(StringHelper::basename($model::className())));
        $this->deleteCacheAgent($model->username);
        return $this->redirect(['index']);
    }
    public function actionListAjax()
    {
        $this->layout = false;

        // ctype = album, playlist, category
        $ctype = Yii::$app->request->get('ctype', null);
        $ctypeId = Yii::$app->request->get('ctype_id', null);

        $searchModel = new StaffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination([
            'pageSize' => 10,

        ]);
        return $this->render('listAjax', [
            'dataProvider' => $dataProvider,
            'ctype' => $ctype,
            'ctypeId' => $ctypeId,
        ]);
    }
    public function actionAjaxSearch($q = null, $ids = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $data = Department::find()
                ->select('id_department as id, department_name as text')
                ->where(['OR',
                ['like', 'id_department', $q],
                ['like', 'department_name', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        } elseif (is_array($ids) && count($ids)) {
            $data = Department::find()
                ->select('id_department as id, department_name  AS text')
                ->where(['OR',
                ['like', 'id_department', $ids],
                ['like', 'department_name', $ids],])
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    public function actionAjaxSearchA($q = null, $ids = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $data = Customer::find()
                ->select('sUserID as id, staff_name as text')
                ->innerJoin('staff', 'staff.username = users_info.username')
                ->where(['OR',
                ['like', 'sUserID', $q],
                ['like', 'staff_name', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        } elseif (is_array($ids) && count($ids)) {
            $data = Department::find()
                ->select('sUserID as id, staff_name  AS text')
                ->where(['OR',
                ['like', 'sUserID', $ids],
                ['like', 'staff_name', $ids],])
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    /**
     * Finds the Staff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_staff Id staff
     * @return Staff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_staff)
    {
        if (($model = Staff::findOne(['id_staff' => $id_staff])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    public function actionAjaxSearchStaff($q = null, $ids = [])
    {
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
            if(User::findOne(\Yii::$app->user->id)->id_province == null){
            $data = Staff::find()
                ->select('phone_number as id, staff_name as text, username')
                ->where(['OR',
                ['like', 'phone_number', $q],
                ['like', 'staff_name', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            }
            else{
                $data = Staff::find()
                ->innerJoin($dbn . '.department', 'department.id_department = staff.id_department')
                ->select('phone_number as id, staff_name as text, username')
                ->where(['department.id_province' => User::findOne(\Yii::$app->user->id)->id_province])
                ->andWhere(['OR',
                ['like', 'phone_number', $q],
                ['like', 'staff_name', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            }
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
                $out['results'][$i]['id'] = $out['results'][$i]['username'];
            }
        return $out;
    }

    public function actionAddToCollection($ctype, $ctype_id, $id_staff)
    {
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $resp = [
            'error_code' => 404,
            'message' => Yii::t('backend', 'Not found')
        ];
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn,(strpos($command->dsn, 'dbname=')) + 7);
        switch ($ctype) {
            case 'queue':
                $album = Queue::find()
                ->where(['queue_name' => $ctype_id])
                ->all();
                $check_isset = StaffQueue::find()
                    ->where(['queue_name' => $ctype_id, 'agent_name' => $id_staff])->count();

                if ($check_isset == "0") {
                    if ($album) {
                        $sa = new StaffQueue();
                        $modelClassName =  Inflector::camel2words(StringHelper::basename($sa::className()));
                        $sa->queue_name  = $ctype_id;
                        $sa->agent_name = $id_staff;
                        //kiem tra xem nhan vien co thuoc queue nao ko?
                        $query = (new \yii\db\Query())
                        ->select(['staff.username','queue.id_department'])
                        ->from($dbn.'.staff')
                        ->innerJoin($dbn.'.queue_agent', 'staff.username = queue_agent.agent_name')
                        ->innerJoin($dbn.'.queue', 'queue.queue_name = queue_agent.queue_name')
                        ->where(['staff.username' => $sa->agent_name])
                        ->all();

                        // echo "<pre>";var_dump($query[0]["id_department"]);die();

                        $queryDepartment = (new \yii\db\Query())
                        ->select(['queue.id_department'])
                        ->from($dbn.'.queue')
                        ->where(['queue.queue_name' => $sa->queue_name])
                        ->all();

                        // echo "<pre>";var_dump($queryDepartment[0]["id_department"]);die();

                        $queryStatus = (new \yii\db\Query())
                        ->select(['users_info.state'])
                        ->from($dbn.'.users_info')
                        ->where(['users_info.username' => $sa->agent_name])
                        ->one();
                        if(($query == null && $queryStatus["state"] == 1) ||empty($query[0]["id_department"])==false && (strcmp($query[0]["id_department"],$queryDepartment[0]["id_department"])==0 && $queryStatus["state"] == 1)){
                        $sa->save();
                        $resp = [
                            'error_code' => 0,
                                'message' => Yii::t('backend', 'Thêm nhân viên vào Queue thành công!')
                            ];
                            Log::saveDBLog(Yii::$app->user->identity->id, $sa->queue_name, 'add staff into queue', Inflector::camel2words(StringHelper::basename($sa::className())));
                            $this->deleteCacheAgentInQueue($ctype_id);
                        }
                        elseif($queryStatus["state"] == 0){
                            $resp = [
                                'error_code' => 0,
                                    'message' => Yii::t('backend', 'Không thể thêm vào queue nhân viên ở trạng thái inactive')
                                ];
                        }
                        else {
                            $query1 = (new \yii\db\Query())
                            ->select(['queue.id_department', 'department.department_name'])
                            ->from($dbn.'.queue')
                            ->innerJoin($dbn.'.queue_agent', 'queue.queue_name = queue_agent.queue_name')
                            ->innerJoin($dbn.'.staff', 'staff.username = queue_agent.agent_name')
                            ->innerJoin($dbn.'.department', 'queue.id_department = department.id_department')
                            ->where(['staff.username' => $id_staff])
                            ->one();
                            $resp = [
                                'error_code' => 0,
                                'message' => Yii::t('backend', 'Nhân viên này đang ở trong phòng ban: '.$query1["id_department"]." - ".$query1["department_name"])
                            ];
                        }
                    }

                } else {
                    $resp = [
                        'error_code' => 0,
                        'message' => Yii::t('backend', 'Nhân viên đã có trong queue này!')
                    ];
                }

                return $resp;

                break;
            default:

                return $resp;
        }
    }
    public function actionRemoveToCollection($ctype, $ctype_id, $id_staff)
    {
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $resp = [
            'error_code' => 404,
            'message' => Yii::t('backend', 'Not found')
        ];

        switch ($ctype) {
            case 'queue':
                $album = Queue::find()
                ->where(['queue_name' => $ctype_id])
                ->all();;
                if ($album) {
                    $albumSongs = StaffQueue::findAll([
                        'queue_name' => $ctype_id,
                        'agent_name' => $id_staff,
                    ]);
                    foreach ($albumSongs as $albumSong) {
                        $albumSong->delete();
                    }

                    $resp = [
                        'error_code' => 0,
                        'message' => Yii::t('backend', 'Remove staff successfully!')
                    ];
                    Log::saveDBLog(Yii::$app->user->identity->id, $albumSong->queue_name, 'remove staff in queue', Inflector::camel2words(StringHelper::basename($albumSong::className())));
                    $this->deleteCacheAgentInQueue($ctype_id);
                    return $resp;
                }
                break;
            default:

                return $resp;
        }
    }

    public static function deleteCacheAgent($username)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->addHeaders(['content-type' => 'application/json'])
            ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/cache/agent/info?username='.$username)
            ->send();
        $dataRespone = $response->getContent();
        $data = json_decode($dataRespone);
        Yii::info("deleteCacheAgent(".$username.")|Response|".json_encode($data));
        return $data;
    }

    public static function deleteCacheAgentInQueue($queue_name)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->addHeaders(['content-type' => 'application/json'])
            ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/cache/queue/agent?queue_name='.$queue_name)
            ->send();
        $dataRespone = $response->getContent();
        $data = json_decode($dataRespone);
        Yii::info("deleteCacheAgentInQueue(".$queue_name.")|Response|".json_encode($data));
        return $data;
    }
}
