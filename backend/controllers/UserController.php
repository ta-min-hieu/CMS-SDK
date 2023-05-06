<?php

namespace backend\controllers;

use backend\models\User;
use Yii;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\httpclient\Client;
use backend\models\HelpdeskSetting;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setScenario(User::SCENARIO_CREATE_USER);
        $model->is_first_login = 1;
        $model->status = 1;

        if (Yii::$app->user->identity->user_type == 'branch') {
            $model->branch_id = Yii::$app->user->identity->branch_id;
            $model->user_type = 'partner';
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // Gan quyen theo usertype
            $this->assignRole($model, true);

            \Yii::$app->session->setFlash('info', \Yii::t('backend', 'Create account successfully!'));

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->assignRole($model, false);
            \Yii::$app->session->setFlash('info', \Yii::t('backend', 'Update account successfully!'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Change password an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionChangePassword()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();
        //        if ($model->load(Yii::$app->request->post()) && $model->login()) {
        //            return $this->goBack();
        //        } else {
        return $this->render('changePass', [
            'model' => $model,
        ]);
        //        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Delete account successfully!');
        return $this->redirect(['index']);
    }

    public function actionManageDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Delete account successfully!');
        return $this->redirect(['manage-agent']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            $user = Yii::$app->user->identity;
            switch ($user->user_type) {
                case 'admin':
                    break;
                case 'ho':
                    if ($model->user_type == 'admin') {
                        throw new NotFoundHttpException('The requested page does not exist.');
                    }
                    break;
                case 'branch':
                    if ($model->user_type == 'admin' || $model->user_type == 'ho') {
                        throw new NotFoundHttpException('The requested page does not exist.');
                    }
                    break;
                case 'partner':

                    throw new NotFoundHttpException('The requested page does not exist.');

                    break;
            }
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Gan quyen cho user khi tao moi va update
     * @param $backendUser
     * @param bool $isCreateNew
     */
    public function assignRole($backendUser, $isCreateNew = false)
    {
        $loginUser  = Yii::$app->user;
        // Gan quyen theo usertype
        $role = Yii::$app->authManager->getRole($backendUser->user_type);

        if (!$isCreateNew) {
            // Update thi clear toan bo role cua user de set lai
            if ($loginUser->identity->getId() != $backendUser->id) {
                Yii::$app->authManager->revokeAll($backendUser->id);
            }
        }

        // Neu user cap thap --> ko cho tao admin
        switch ($backendUser->user_type) {
            case 'admin':
                if ($loginUser->can('admin') || $loginUser->can('super-admin')) {
                    Yii::$app->authManager->assign($role, $backendUser->id);
                }
                break;
                //            case 'ho':
                //                if ($loginUser->can('admin') || $loginUser->can('super-admin')) {
                //                    Yii::$app->authManager->assign($role, $backendUser->id);
                //                }
                //                break;
                //            case 'branch':
                //                if ($loginUser->can('admin') || $loginUser->can('super-admin') || $loginUser->can('ho')) {
                //                    Yii::$app->authManager->assign($role, $backendUser->id);
                //                }
                //                break;
                //            case 'partner':
                //                if ($loginUser->can('admin') || $loginUser->can('super-admin') || $loginUser->can('ho') || $loginUser->can('branch')) {
                //                    Yii::$app->authManager->assign($role, $backendUser->id);
                //                }
                //
                //                break;
        }
    }

    public function actionManageAgent()
    {

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['user_type' => 'agent']);

        $list_user = User::find()->where(['user_type' => 'agent'])->select('id')->column();
        $list_id = null;
        foreach ($list_user as $value) {
            $list_id .= $value . ',';
        }
        $list_id = rtrim($list_id, ',');

        return $this->render('manage-agent', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'list_id' => $list_id
        ]);
    }

}
