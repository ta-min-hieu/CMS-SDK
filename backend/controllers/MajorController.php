<?php

namespace backend\controllers;
use backend\models\Log;
use backend\models\Major;
use backend\models\MajorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\Html;
/**
 * MajorController implements the CRUD actions for Major model.
 */
class MajorController extends Controller
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
     * Lists all Major models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MajorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Major model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_mission)
    {
        if (Yii::$app->request->isAjax) {
            $this->layout = false;
        }
        return $this->render('view', [
            'model' => $this->findModel($id_mission),
            'isAjax' => Yii::$app->request->isAjax,
        ]);
    }

    /**
     * Creates a new Major model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    /**
     * Finds the Major model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Major the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Major::findOne(['id_mission' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
