<?php

namespace backend\controllers;

use Yii;
use backend\models\UserLoginFailed;
use backend\models\UserLoginFailedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * UserLoginFailedController implements the CRUD actions for UserLoginFailed model.
 */
class UserLoginFailedController extends Controller
{
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
     * Lists all UserLoginFailed models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserLoginFailedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserLoginFailed model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserLoginFailed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserLoginFailed();
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('backend', 'Thêm mới "{object}" thành công!', ['object' => Yii::t('backend', $modelClassName)]));
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserLoginFailed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('backend', 'Chỉnh sửa {object} thành công!', ['object' => Yii::t('backend', $modelClassName)]));
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserLoginFailed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $modelClassName =  Inflector::camel2words(StringHelper::basename($model::className()));
        $model->delete();

        Yii::$app->session->setFlash('info', Yii::t('backend', 'Xóa {object} thành công!', ['object' => Yii::t('backend', $modelClassName)]));

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserLoginFailed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return UserLoginFailed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserLoginFailed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
