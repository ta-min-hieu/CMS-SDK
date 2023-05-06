<?php

namespace backend\controllers;

use backend\models\ListExcel;
use backend\models\Queue;
use backend\models\Department;
use backend\models\StaffQueue;
use backend\models\QueueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\components\slim\Slim;
use common\helpers\FileHelper;
use backend\models\StaffSearch;
use backend\models\Log;
use backend\models\User;
use common\models\QueueBase;
use yii\bootstrap\Html;
use yii\web\UploadedFile;
use yii\httpclient\Client;

/**
 * QueueController implements the CRUD actions for Queue model.
 */
class QueueController extends Controller
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
     * Lists all Queue models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new QueueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Queue model.
     * @param int $id ID
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
     * Creates a new Queue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Queue();

        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));

        $form_values = Yii::$app->request->post();
        // Lay du lieu anh upload len de validate
        // Neu ko co thi xoa
        $form_values['thumb'] = Slim::getImagesFromSlimRequest('thumb');
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        if ($model->load($form_values)) {
            $query = (new \yii\db\Query())
                ->select(['queue.id_mission'])
                ->from($dbn . '.queue')
                ->where(['queue.id_mission' => $model->id_mission])
                ->andWhere(['queue.id_department' => $model->id_department])
                ->all();
            if ($query == null) {
                $model->hostname = "vnpost";
                $model->queue_name = md5($model->id_department . Yii::$app->params['status4'][$model->id_mission]);
            } else {
                Yii::$app->session->setFlash('info', Yii::t('backend', 'This department has a mission', ['object' => Yii::t('backend', $modelClassName)]));
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            if(strtotime($model->end_time) <= strtotime($model->start_time)){
                Yii::$app->session->setFlash('info', Yii::t('backend', 'The work end time must be greater than the work start time', ['object' => Yii::t('backend', $modelClassName)]));
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            if ($model->type_queue != "CSKH" && $model->type_queue != "Miss Queue") {
                FileHelper::processUploadNewImage($model, 'thumb');
                if ($model->save() && !empty($form_values['thumb'])) {
                    Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('info', Yii::t('backend', 'Photos cannot be blank', ['object' => Yii::t('backend', $modelClassName)]));
                }
            }
            else if ($model->type_queue == "CSKH" && $model->id_mission == "5") {
                FileHelper::processUploadNewImage($model, 'thumb');
                if ($model->save() && !empty($form_values['thumb'])) {
                    Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('info', Yii::t('backend', 'Photos cannot be blank', ['object' => Yii::t('backend', $modelClassName)]));
                }
            }
            else if ($model->type_queue == "Miss Queue" && $model->id_mission == "6") {
                FileHelper::processUploadNewImage($model, 'thumb');
                if ($model->save() && !empty($form_values['thumb'])) {
                    Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('info', Yii::t('backend', 'Photos cannot be blank', ['object' => Yii::t('backend', $modelClassName)]));
                }
            }
            else{
                Yii::$app->session->setFlash('info', Yii::t('backend', 'Invalid queue type', ['object' => Yii::t('backend', $modelClassName)]));
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Queue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));

        $model->loadDefaultValues();
        $form_values = Yii::$app->request->post();

        $form_values['thumb'] = Slim::getImagesFromSlimRequest('thumb');
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        if ($model->load($form_values)) {
            $model->status=0;
            $query = (new \yii\db\Query())
                ->select(['queue.id_mission'])
                ->from($dbn . '.queue')
                ->where(['queue.id_mission' => $model->id_mission])
                ->andWhere(['queue.id_department' => $model->id_department])
                ->andWhere([
                    'not in', 'queue.id_mission',
                    Queue::find()
                        ->select(['queue.id_mission'])
                        ->from($dbn . '.queue')
                        ->where(['queue.id' => $id])
                ])
                ->one();
            // var_dump($query);
            // die();
            if(strtotime($model->end_time) <= strtotime($model->start_time)){
                Yii::$app->session->setFlash('info', Yii::t('backend', 'The work end time must be greater than the work start time', ['object' => Yii::t('backend', $modelClassName)]));
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            if ($model->type_queue != "CSKH" && $model->type_queue != "Miss Queue") {
                if ($query == null) {
                    $query1 = Queue::find()
                    ->select(['id_mission'])
                    ->where(['id_mission' => $model->id_mission])
                    ->andwhere(['queue.id' => $id])
                        ->one();
                    if ($query1 == null) {
                        $model->queue_name = md5($model->id_department . Yii::$app->params['status4'][$model->id_mission]);
                        FileHelper::processUploadNewImage($model, 'thumb');
                    } else {
                        FileHelper::processUploadNewImage($model, 'thumb');
                    }
                } else {
                    Yii::$app->session->setFlash('info', Yii::t(
                        'backend',
                        'This department has a mission',
                        ['object' => Yii::t('backend', $modelClassName)]
                    ));
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('info', Yii::t('backend',
                        'Update "{object}" successfully!',
                        ['object' => Yii::t('backend', $modelClassName)]
                    ));
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
                    $this->deleteCacheQueue($model->queue_name);
                    $this->deleteCacheNextQueue($model->id);
                    return $this->redirect(['index']);
                }
            }
            else if ($model->type_queue == "CSKH" && $model->id_mission == "5") {
                if ($query == null) {
                    $query1 = Queue::find()
                    ->select(['id_mission'])
                    ->where(['id_mission' => $model->id_mission])
                    ->andwhere(['queue.id' => $id])
                        ->one();
                    if ($query1 == null) {
                        $model->queue_name = md5($model->id_department . Yii::$app->params['status4'][$model->id_mission]);
                        FileHelper::processUploadNewImage($model, 'thumb');
                    } else {
                        FileHelper::processUploadNewImage($model, 'thumb');
                    }
                } else {
                    Yii::$app->session->setFlash('info', Yii::t(
                        'backend',
                        'This department has a mission',
                        ['object' => Yii::t('backend', $modelClassName)]
                    ));
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('info', Yii::t('backend',
                        'Update "{object}" successfully!',
                        ['object' => Yii::t('backend', $modelClassName)]
                    ));
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
                    $this->deleteCacheQueue($model->queue_name);
                    $this->deleteCacheNextQueue($model->id);
                    return $this->redirect(['index']);
                }
            }
            else if ($model->type_queue == "Miss Queue" && $model->id_mission == "6") {
                if ($query == null) {
                    $query1 = Queue::find()
                    ->select(['id_mission'])
                    ->where(['id_mission' => $model->id_mission])
                    ->andwhere(['queue.id' => $id])
                        ->one();
                    if ($query1 == null) {
                        $model->queue_name = md5($model->id_department . Yii::$app->params['status4'][$model->id_mission]);
                        FileHelper::processUploadNewImage($model, 'thumb');
                    } else {
                        FileHelper::processUploadNewImage($model, 'thumb');
                    }
                } else {
                    Yii::$app->session->setFlash('info', Yii::t(
                        'backend',
                        'This department has a mission',
                        ['object' => Yii::t('backend', $modelClassName)]
                    ));
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('info', Yii::t('backend',
                        'Update "{object}" successfully!',
                        ['object' => Yii::t('backend', $modelClassName)]
                    ));
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
                    $this->deleteCacheQueue($model->queue_name);
                    $this->deleteCacheNextQueue($model->id);
                    return $this->redirect(['index']);
                }
            }
            else{
                Yii::$app->session->setFlash('info', Yii::t('backend', 'Invalid queue type', ['object' => Yii::t('backend', $modelClassName)]));
                    return $this->render('update', [
                        'model' => $model,
                    ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    public function actionStaff($id)
    {
        $searchModel = new StaffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $this->findModel($id);
        // var_dump($model->staffs);
        // die();
        return $this->render('staff', [
            'model' => $model,
            'staffs' => $model->staffs,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Deletes an existing Queue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status=1;
        $model->save(false);
        Yii::$app->session->setFlash('success', 'Delete account successfully!');
        Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'delete', Inflector::camel2words(StringHelper::basename($model::className())));
        $this->deleteCacheQueue($model->queue_name);
        $this->deleteCacheNextQueue($model->id);
        return $this->redirect(['index']);
    }
    public function actionAddExcelListStaffToQueue($id)
    {
        $model1 = new Queue();
        $model1 = $this->findModel($id);
        $model = new ListExcel();
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
        $form_values = Yii::$app->request->post();
        if ($model->load($form_values)) {
            $model->action = "Add list staff into queue";
            $model->id_queue = $model1->id;
            $model->excel = UploadedFile::getInstance($model, 'excel');
            if (substr($model->excel, -4) == '.xls' || substr($model->excel, -5) == '.xlsx' || substr($model->excel, -5) == '.xlsm') {
                if ($model->save()) {
                    $model->uploadExcel();
                    $command = Yii::$app->dbsdk;
                    $dbn = substr($command->dsn,(strpos($command->dsn, 'dbname=')) + 7);
                    $path = substr($model->excel,1);
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $excel_Obj = $reader->load($path);
                    $worksheet = $excel_Obj->getSheet('0');
                    $lastRow = $worksheet->getHighestRow();
                    $columncount = $worksheet->getHighestDataColumn();
                    $columncount_number = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columncount);
                    $dem = 0;
                    $a = array();
                    for ($row = 1; $row <= $lastRow; $row++) {
                        for ($col = 1; $col <= $columncount_number; $col++) {
                            if ($col == 1) {
                                $sa = new StaffQueue();
                                $query = (new \yii\db\Query())
                                    ->select(['staff.username', 'queue.id_department', 'queue.queue_name'])
                                    ->from($dbn . '.staff')
                                    ->innerJoin($dbn . '.queue_agent', 'staff.username = queue_agent.agent_name')
                                    ->innerJoin($dbn . '.queue', 'queue.queue_name = queue_agent.queue_name')
                                    ->where(['staff.username' => $worksheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row)->getValue()])
                                    ->all();

                                $queryDepartment = (new \yii\db\Query())
                                    ->select(['queue.id_department'])
                                    ->from($dbn . '.queue')
                                    ->where(['queue.queue_name' => $model1->queue_name])
                                    ->all();
                                //Kiem tra xem username co ton tai hay ko
                                $query1 = (new \yii\db\Query())
                                    ->select(['staff.username'])
                                    ->from($dbn . '.staff')
                                    ->where(['staff.username' => $worksheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row)->getValue()])
                                    ->all();

                                $queryStatus = (new \yii\db\Query())
                                ->select(['users_info.state'])
                                ->from($dbn.'.users_info')
                                ->where(['users_info.username' => $worksheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row)->getValue()])
                                ->one();

                                if (($query == null && $query1 != null && $queryStatus["state"] == 1) || ($query1 != null && $queryStatus["state"] == 1 && strcmp($query[0]["id_department"],$queryDepartment[0]["id_department"])==0 && $query[0]["queue_name"] != $model1->queue_name)) {
                                    $sa->agent_name = $worksheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row)->getValue();
                                    $sa->queue_name = $model1->queue_name;
                                    $sa->save();
                                    $b=0;
                                } else {
                                    $dem++;
                                    $a[$col][$row] = $worksheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row)->getValue();
                                    $b = 1;
                                }
                            } if($b == 1) {
                                $a[$col][$row] = $worksheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row)->getValue();
                            }
                        }
                    }
                    \Yii::$app->session['row'] = $lastRow;
                    \Yii::$app->session['col'] = $columncount_number;
                    if($dem == 0)
                        Yii::$app->session->setFlash('success', 'Add list staff successfully!');
                    else {
                        $tippIds = \Yii::$app->session['tippIds'];
                        $tippIds = null;
                        $tippIds[] = $a;
                        \Yii::$app->session['tippIds'] = $tippIds;
                        \Yii::$app->session['id'] = $model1->id;
                        Yii::$app->session->setFlash('success', 'Add list staff successfully. There are '. $dem .' invalid usernames. <a style="text-decoration-line: underline;" href="list-error/">View Detail</a>');
                    }
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'Add list staff into queue', Inflector::camel2words(StringHelper::basename($model::className())));
                    StaffController::deleteCacheAgentInQueue($model1->queue_name);
                    return $this->redirect(['staff', 'id' => $model1->id]);
                } else {
                    return $this->render('add-excel-list-staff-to-queue', [
                        'model' => $model,
                        'model1' => $model1,
                    ]);
                }
            }
            else{
                Yii::$app->session->setFlash('info', Yii::t('backend', 'The file is not in the correct format', ['object' => Yii::t('backend', $modelClassName)]));
                return $this->render('add-excel-list-staff-to-queue', [
                    'model' => $model,
                    'model1' => $model1,
                ]);
            }
        } else {
            return $this->render('add-excel-list-staff-to-queue', [
                'model' => $model,
                'model1' => $model1,
            ]);
        }
    }

    public function actionListError(){
        return $this->render('list-error',[
        ]);
    }
    /**
     * Finds the Queue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Queue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Queue::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
    public function actionAjaxSearch($q = null, $ids = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            if(User::findOne(\Yii::$app->user->id)->id_province == null){
            $data = Department::find()
                ->select('id_department as id, department_name as text')
                ->where(['OR',
                ['id_department' => $q],
                ['like', 'department_name', $q],])
                ->limit(50)
                ->asArray()
                ->all();
            }
            else{
                $data = Department::find()
                ->select('id_department as id, department_name as text')
                ->where(['id_province' => User::findOne(\Yii::$app->user->id)->id_province])
                ->andWhere(['OR',
                ['id_department' => $q],
                ['like', 'department_name', $q],])
                ->limit(50)
                ->asArray()
                ->all();
            }
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
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        } else{
            $data = Department::find()
                ->select('id_department as id, department_name  AS text')
                ->where(['id_department' => '00'])
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        }
        return $out;
    }

    public function actionAjaxSearchDepartmentQueue($q = null, $ids = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
            if(User::findOne(\Yii::$app->user->id)->id_province == null){
            $data = Queue::find()
                ->select('id_department as id, disp_name as text, queue_name')
                ->where(['OR',
                ['like', 'id_department', $q],
                ['like', 'disp_name', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            }
            else{
                $data = Queue::find()
                ->select('id_department as id, disp_name as text, queue_name')
                ->where(['id_province' => User::findOne(\Yii::$app->user->id)->id_province])
                ->andWhere(['OR',
                ['like', 'id_department', $q],
                ['like', 'disp_name', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            }
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
                $out['results'][$i]['id'] = $out['results'][$i]['queue_name'];
            }
        return $out;
    }

    public function actionAjaxSearchNextQueue($q = null, $ids = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            if(User::findOne(\Yii::$app->user->id)->id_province == null){
            $data = Queue::find()
                ->select('id as id, disp_name as text')
                ->where(['OR',
                ['like', 'id', $q],
                ['like', 'disp_name', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            }
            else{
                $data = Queue::find()
                ->select('id as id, disp_name as text')
                ->innerJoin('department', 'department.id_department = queue.id_department')
                ->where(['id_province' => User::findOne(\Yii::$app->user->id)->id_province])
                ->andWhere(['OR',
                ['like', 'id', $q],
                ['like', 'disp_name', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            }
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        } 
        elseif (is_array($ids) && count($ids)) {
            $data = Queue::find()
                ->select('id as id, disp_name  AS text')
                ->where(['OR',
                ['like', 'id', $ids],
                ['like', 'disp_name', $ids],])
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        } else{
            $data = Queue::find()
                ->select('id as id, disp_name AS text')
                ->where(['disp_name' => 'CSKH TCT Giám sát'])
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        }
        return $out;
    }

    public function actionAjaxSearchProvince($q = null, $ids = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $data = Department::find()
                ->select('id_province as id, province as text')
                ->distinct()
                ->where(['OR',
                ['like', 'id_province', $q],
                ['like', 'province', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        } elseif (is_array($ids) && count($ids)) {
            $data = Department::find()
                ->select('id_province as id, province as text')
                ->distinct()
                ->where(['OR',
                ['like', 'id_province', $ids],
                ['like', 'province', $ids],])
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        }
        return $out;
    }
    public function actionEditWorkingTime()
    {
        if(Yii::$app->request->post()){
            $model = new Queue();
            $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
            if(strtotime($_POST['end_time_hour'].":".$_POST['end_time_minute']) <= strtotime($_POST['start_time_hour'].":".$_POST['start_time_minute'])){
                Yii::$app->session->setFlash('info', Yii::t('backend', 'The work end time must be greater than the work start time', ['object' => Yii::t('backend', $modelClassName)]));
                return $this->render('edit-working-time');
            }
            (new QueueBase)->updateWorkingTime($_POST['id_province'],$_POST['type_queue'],$_POST['start_time_hour'].":".$_POST['start_time_minute'],$_POST['end_time_hour'].":".$_POST['end_time_minute']);
            Yii::$app->session->setFlash('success', 'Update Working Time successfully!');
            Log::saveDBLog(Yii::$app->user->identity->id, $_POST['id_province']." - ".$_POST['type_queue'], 'update-working-time', Inflector::camel2words(StringHelper::basename($model::className())));
            return $this->redirect(['index']);
        }
        return $this->render('edit-working-time');
    }

    public function deleteCacheQueue($queue_name)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->addHeaders(['content-type' => 'application/json'])
            ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/cache/queue/info?queue_name='.$queue_name)
            ->send();
        $dataRespone = $response->getContent();
        $data = json_decode($dataRespone);
        Yii::info("deleteCacheQueue(".$queue_name.")|Response|".json_encode($data));
        return $data;
    }

    public function deleteCacheNextQueue($queue_id)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->addHeaders(['content-type' => 'application/json'])
            ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/cache/queue/next?queue_id='.$queue_id)
            ->send();
        $dataRespone = $response->getContent();
        $data = json_decode($dataRespone);
        Yii::info("deleteCacheNextQueue(".$queue_id.")|Response|".json_encode($data));
        return $data;
    }
}
