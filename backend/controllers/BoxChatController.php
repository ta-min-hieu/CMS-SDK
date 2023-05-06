<?php

namespace backend\controllers;

use backend\models\BoxChat;
use backend\models\Log;
use backend\models\BoxChatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\Html;
/**
 * BoxChatController implements the CRUD actions for BoxChat model.
 */
class BoxChatController extends Controller
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
     * Lists all BoxChat models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BoxChatSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BoxChat model.
     * @param int $id_box_chat Id box chat
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_box_chat)
    {
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
        }
        return $this->render('view', [
            'model' => $this->findModel($id_box_chat),
            'isAjax' => Yii::$app->request->isAjax,
        ]);
    }

    /**
     * Creates a new BoxChat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BoxChat();
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // var_dump($model->validate());
                // die();
                Yii::$app->session->setFlash('info', Yii::t('backend', 'Create "{object}" successfully!', ['object' => Yii::t('backend', $modelClassName)]));
                Log::saveDBLog(Yii::$app->user->identity->id, $model->id_box_chat, 'create', Inflector::camel2words(StringHelper::basename($model::className())));
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
     * Updates an existing BoxChat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_box_chat Id box chat
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('info', \Yii::t('backend', 'Update account successfully!'));
            Log::saveDBLog(Yii::$app->user->identity->id, $model->id_box_chat, 'update', Inflector::camel2words(StringHelper::basename($model::className())));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BoxChat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_box_chat Id box chat
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Delete account successfully!');
        Log::saveDBLog(Yii::$app->user->identity->id, $model->id_box_chat, 'delete', Inflector::camel2words(StringHelper::basename($model::className())));
        return $this->redirect(['index']);
    }

    /**
     * Finds the BoxChat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_box_chat Id box chat
     * @return BoxChat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_box_chat)
    {
        if (($model = BoxChat::findOne(['id_box_chat' => $id_box_chat])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
