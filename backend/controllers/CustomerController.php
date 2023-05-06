<?php

namespace backend\controllers;
use backend\models\Log;
use backend\models\Customer;
use backend\models\Staff;
use backend\models\Users;
use backend\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\components\slim\Slim;
use common\helpers\FileHelper;
use StringBackedEnum;
use yii\helpers\Html;
use yii\httpclient\Client;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
     * Lists all Customer models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param string $phonenumber phonenumber
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($phonenumber)
    {
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
        }
        return $this->render('view', [
            'model' => $this->findModel($phonenumber),
            'isAjax' => Yii::$app->request->isAjax,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Customer();
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < 10; $i++) {
            $model->username .= $chars[rand(0, $size - 1)];
        }
        $model->username .= time() * 1000;
        $model->type_user = '1';
        // $model->state = '1';
        $model->serviceID = 'vnpost';
        $form_values = Yii::$app->request->post();
        // Lay du lieu anh upload len de validate
        // Neu ko co thi xoa
        $form_values['avatar'] = Slim::getImagesFromSlimRequest('avatar');
        if ($model->load($form_values)) {
            if (substr($model->phonenumber, 0, 2) == '84') {
                $model->phonenumber = '0' . substr($model->phonenumber, 2);
            }
            // var_dump($form_values['avatar']);
            // die();
            $model2 = new Users();
            $model2->username = $model->username;
            $model2->server_host = $model->serviceID;
            $model2->password = sha1($model->username);
            if ($model->save() && $model2->save(false)) {
                FileHelper::processUploadNewImage($model, 'avatar');
                Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                Log::saveDBLog(Yii::$app->user->identity->id, $model->phonenumber, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $phonenumber phonenumber
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
        $model->loadDefaultValues();
        $form_values = Yii::$app->request->post();

        $form_values['avatar'] = Slim::getImagesFromSlimRequest('avatar');

        if ($model->load($form_values)) {
            if (substr($model->phonenumber, 0, 2) == '84') {
                $model->phonenumber = '0' . substr($model->phonenumber, 2);
            }
            // $model->state = '1';
            if ($model->save()) {
                $staff = Staff::findOne(['username' => $model->username]);
                if ($staff != null) {
                    $staff->status = $model->state;
                    $staff->save(false);
                    StaffController::deleteCacheAgent($staff->username);
                }
                FileHelper::processUploadNewImage($model, 'avatar');
                Yii::$app->session->setFlash('info', Yii::t('backend', 'Update {object} successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                Log::saveDBLog(Yii::$app->user->identity->id, $model->phonenumber, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
                $this->deleteCacheUserInfo($model->username);
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $phonenumber phonenumber
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->state = 0;
        $staff = Staff::findOne(['username' => $model->username]);
        if ($staff != null) {
            $staff->status = 0;
            $staff->save(false);
            $command = Yii::$app->dbsdk;
            $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
            $query1 = (new \yii\db\Query())
            ->select(['queue_agent.agent_name'])
            ->from($dbn . '.queue_agent')
            ->innerJoin($dbn . '.staff', 'staff.username = queue_agent.agent_name')
            ->where(['staff.username' => $model->username])
            ->one();
            if ($query1 != false) {
                $agent_name = $query1["agent_name"];
                Yii::$app->db->createCommand("
            DELETE FROM $dbn.queue_agent
            WHERE agent_name = '$agent_name'
            ")->execute();
            }
            StaffController::deleteCacheAgent($staff->username);
        }
        $model->save(false);
        Yii::$app->session->setFlash('success', 'Delete account successfully!');
        Log::saveDBLog(Yii::$app->user->identity->id, $model->phonenumber, 'delete', Inflector::camel2words(StringHelper::basename($model::className())));
        $this->deleteCacheUserInfo($model->username);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $phonenumber phonenumber
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($phonenumber)
    {
        if (($model = Customer::findOne(['phonenumber' => $phonenumber])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    public function deleteCacheUserInfo($username)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->addHeaders(['content-type' => 'application/json'])
            ->setUrl('https://sdkapis2.ringme.vn/sdkapi/s2s/cache/user/info?username='.$username)
            ->send();
        $dataRespone = $response->getContent();
        $data = json_decode($dataRespone);
        Yii::info("deleteCacheUserInfo(".$username.")|Response|".json_encode($data));
        return $data;
    }
}
