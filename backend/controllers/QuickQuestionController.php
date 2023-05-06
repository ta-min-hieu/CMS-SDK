<?php

namespace backend\controllers;

use backend\models\BoxChat;
use backend\models\Log;
use backend\models\QuickQuestion;
use backend\models\QuickQuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\Html;
/**
 * QuickQuestionController implements the CRUD actions for QuickQuestion model.
 */
class QuickQuestionController extends Controller
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
     * Lists all QuickQuestion models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new QuickQuestionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QuickQuestion model.
     * @param int $id_question Id Question
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_question)
    {
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
        }
        return $this->render('view', [
            'model' => $this->findModel($id_question),
            'isAjax' => Yii::$app->request->isAjax,
        ]);
    }

    /**
     * Creates a new QuickQuestion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new QuickQuestion();
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                Log::saveDBLog(Yii::$app->user->identity->id, $model->id_question, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QuickQuestion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_question Id Question
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('backend', 'Update {object} successfully!', ['object' => Yii::t('backend', $modelClassName)]));
            Log::saveDBLog(Yii::$app->user->identity->id, $model->id_question, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing QuickQuestion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_question Id Question
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Delete account successfully!');
        Log::saveDBLog(Yii::$app->user->identity->id, $model->id_question, 'delete', Inflector::camel2words(StringHelper::basename($model::className())));
        return $this->redirect(['index']);
    }

    /**
     * Finds the QuickQuestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_question Id Question
     * @return QuickQuestion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

     public function actionAjaxSearch($q = null, $ids = [])
     {
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
         $out = ['results' => ['id' => '', 'text' => '']];
         if (!is_null($q)) {
             $data = BoxChat::find()
                 ->select('id_box_chat as id, mission_name as text')
                 ->innerJoin($dbn . '.mission', 'box_chat.type_box_chat = mission.id_mission')
                 ->where(['OR',
                 ['like', 'id_box_chat', $q],
                 ['like', 'mission_name', $q],])
                 ->limit(20)
                 ->asArray()
                 ->all();
             $out['results'] = array_values($data);
             for($i = 0; $i<count($out['results']); $i++){
                 $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
             }
         } elseif (is_array($ids) && count($ids)) {
             $data = BoxChat::find()
                 ->select('id_box_chat as id, mission_name as text')
                 ->innerJoin($dbn . '.mission', 'box_chat.type_box_chat = mission.id_mission')
                 ->where(['OR',
                 ['like', 'id_box_chat', $ids],
                 ['like', 'mission_name', $ids],])
                 ->asArray()
                 ->all();
             $out['results'] = array_values($data);
         }
         return $out;
     }
    
    protected function findModel($id_question)
    {
        if (($model = QuickQuestion::findOne(['id_question' => $id_question])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
