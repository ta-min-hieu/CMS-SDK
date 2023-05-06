<?php

namespace backend\controllers;

use backend\models\UserProfile;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserProfileController extends Controller
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


    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public
    function actionUpdate($id)
    {

        $userid = Yii::$app->user->identity->id;
        if ($id != $userid) {
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            $model = $this->findModel($id);

            if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->password_hash == null) {

                    $model->updateAttributes(array(
                        'email' ,
                        'fullname',
                        'address',
                    ));

                    Yii::$app->session->setFlash('success', Yii::t('backend', 'Update info successfully!'));
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('backend', 'Changed password and info successfully!'));
                    $model->save();
                    return $this->redirect(['view', 'id' => $model->id]);
                }

            } else {
                $model->password_hash = null;

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionView($id)
    {
        $userid = Yii::$app->user->identity->id;
        if ($id != $userid) {
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserProfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = UserProfile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
