<?php

namespace backend\controllers;
use backend\models\User;
use backend\models\Log;
use backend\models\LogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller
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
     * Lists all Log models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionAjaxSearch($q = null, $ids = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $data = User::find()
                ->select('id as id, username as text')
                ->where(['OR',
                ['like', 'id', $q],
                ['like', 'username', $q],])
                ->limit(20)
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
            for($i = 0; $i<count($out['results']); $i++){
                $out['results'][$i]['text'] = $out['results'][$i]['id'].' - '.$out['results'][$i]['text'];
            }
        } elseif (is_array($ids) && count($ids)) {
            $data = User::find()
                ->select('id as id, username  AS text')
                ->where(['OR',
                ['like', 'id', $ids],
                ['like', 'username', $ids],])
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
