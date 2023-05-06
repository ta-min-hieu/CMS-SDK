<?php

namespace backend\controllers;
use backend\models\Log;
use backend\models\OA;
use backend\models\OASearch;
use backend\models\OfficialAccount;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use common\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\components\slim\Slim;
use yii\web\UploadedFile;
use yii\helpers\Html;
/**
 * OAController implements the CRUD actions for OA model.
 */
class OAController extends Controller
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
     * Lists all OA models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OASearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OA model.
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
     * Creates a new OA model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OA();
        
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
        
        $form_values = Yii::$app->request->post();
        // Lay du lieu anh upload len de validate
        // Neu ko co thi xoa
        
        $form_values['image'] = Slim::getImagesFromSlimRequest('image');
        
        if ($model->load($form_values)) {
            // var_dump($model->type);
            // die();
            
            FileHelper::processUploadNewImage($model, 'image');
            $model->video = UploadedFile::getInstance($model, 'video');
            $model->excel = UploadedFile::getInstance($model, 'excel');
            
            if($model->type=='text'){
                $model->image=null;
                $model->video=null;
            }
            if($model->type=='video'){
                $model->text=null;
                $model->image=null;
            }
            if($model->type=='image'){
                $model->text=null;
                $model->video=null;
            }
            if (substr($model->excel, -4) == '.xls' || substr($model->excel, -5) == '.xlsx' || substr($model->excel, -5) == '.xlsm') {
                if ($model->save()) {
                    
                    $model->uploadMedia();
                    $model->uploadExcel();
                    Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
                    return $this->redirect(['index']);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
            else{
                Yii::$app->session->setFlash('info', Yii::t('backend', 'The file is not in the correct format', ['object' => Yii::t('backend', $modelClassName)]));
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OA model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $a = $model->type;
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));

        $model->loadDefaultValues();
        $form_values = Yii::$app->request->post();

        $form_values['image'] = Slim::getImagesFromSlimRequest('image');

        if ($model->load($form_values)) {
            FileHelper::processUploadNewImage($model, 'image');
            if (!$model->video) {
                $model->video = $model->getOldAttribute('video');
            }
            $b = $model->type;
            if ($a == $b) {
                    $model->status = 0;
                if ($model->save()) {
                    $model->video = UploadedFile::getInstance($model, 'video');
                    $model->uploadMedia();
                    Yii::$app->session->setFlash('info', Yii::t('backend', 'Update "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                    Log::saveDBLog(Yii::$app->user->identity->id, $model->id, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
                    return $this->redirect(['index']);
                }
            } else {
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

    /**
     * Deletes an existing OA model.
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
    public function actionAjaxSearch($q = null, $ids = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $data = OfficialAccount::find()
                ->select('id as id, appname as text')
                ->where(['OR',
                ['like', 'id', $q],
                ['like', 'appname', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        } elseif (is_array($ids) && count($ids)) {
            $data = OfficialAccount::find()
                ->select('id as id, appname  AS text')
                ->where(['OR',
                ['like', 'id', $ids],
                ['like', 'appname', $ids],])
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    /**
     * Finds the OA model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return OA the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OA::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
