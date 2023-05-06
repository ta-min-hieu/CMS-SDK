<?php

namespace backend\controllers;
use backend\models\Log;
use backend\models\OfficialAccount;
use backend\models\OfficialAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use common\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\components\slim\Slim;
use yii\helpers\Html;
/**
 * OfficialAccountController implements the CRUD actions for OfficialAccount model.
 */
class OfficialAccountController extends Controller
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
     * Lists all OfficialAccount models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OfficialAccountSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OfficialAccount model.
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
     * Creates a new OfficialAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OfficialAccount();

        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));

        $form_values = Yii::$app->request->post();
        // Lay du lieu anh upload len de validate
        // Neu ko co thi xoa
        $form_values['thumb'] = Slim::getImagesFromSlimRequest('thumb');
        //$form_values['queue_name'] = "a";
        // $model->queue_name = $form_values['id_department'];

        if ($model->load($form_values)) {
                $model->hostname = "vnpost";
                $model->serviceID = "vnpost";
                FileHelper::processUploadNewImage($model, 'thumb');
                Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OfficialAccount model.
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

        if ($model->load($form_values)) {
            FileHelper::processUploadNewImage($model, 'thumb');
            if ($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('backend', 'Update "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OfficialAccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Delete account successfully!');
        Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'delete', Inflector::camel2words(StringHelper::basename($model::className())));
        return $this->redirect(['index']);
    }

    /**
     * Finds the OfficialAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return OfficialAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OfficialAccount::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
