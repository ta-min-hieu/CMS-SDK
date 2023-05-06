<?php
namespace frontend\controllers;

use frontend\models\Contact;
use Yii;
use common\components\SimpleImage;

/**
 * Site controller
 */
class ContactController extends FrontendController
{

    /**
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Contact();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success', 'Cảm ơn quý khách đã liên hệ! Chúng tôi sẽ phản hồi lại sớm nhất có thể!');
            return $this->redirect(['index']);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }

    }

    public function actionAjaxForm()
    {
        $model = new Contact();
        $model->loadDefaultValues();

        $pdId = Yii::$app->request->get('pd_id', null);
        if ($pdId) {
            $model->product_id = $pdId;
        }
        $usePopup = (boolean)json_decode( Yii::$app->request->get('use_popup', true));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->set('last_contact', $model->toArray(['fullname', 'email', 'phonenumber', 'address']));
            return $this->renderAjax('ajaxForm', [
                'usePopup' => $usePopup,
                'model' => null,
                'message' => Yii::t('frontend', 'Cảm ơn quý khách đã liên hệ! Chúng tôi sẽ phản hồi lại sớm nhất có thể!')
            ]);
        } else {
            return $this->renderAjax('ajaxForm', [
                'usePopup' => $usePopup,
                'model' => $model,
            ]);
        }

    }

}
