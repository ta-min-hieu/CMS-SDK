<?php
/**
 * Created by PhpStorm.
 * User: subob
 * Date: 11/9/18
 * Time: 09:42
 */

namespace frontend\controllers;

use common\helpers\Helpers;
use common\models\PartnerBase;
use frontend\models\Subscriber;
use yii;
use yii\web\Controller;

class FrontendController extends Controller
{

    public function beforeAction($action)
    {
        if(isset($_GET['lang']))
        {
            \Yii::$app->language = $_GET['lang'];
            Yii::$app->session->set('language', $_GET['lang']);
        }
        else
        {
            \Yii::$app->language = Yii::$app->session->get('language', 'my');
        }
        if ($action->id == 'captcha') {
            return true;
        }
        // channel refer
        $referChannel = trim(Yii::$app->request->get('refc', null));
        if ($referChannel) {
            Yii::$app->session->set('REFER_CHANNEL', strtoupper($referChannel));
        }
        // partner refer
        $referPidSession = Yii::$app->session->get('REFER_PID', null);
        if (!$referPidSession) {

            $referPid = trim(Yii::$app->request->get('refpid', null));
            if ($referPid) {
                // check partnerid
                $check = PartnerBase::findOne($referPid);
                if ($check) {
                    Yii::$app->session->set('REFER_PID', intval($referPid));
                    $referPidSession = $referPid;
                    Yii::info('REFER_PID: '. $referPid, 'mps');
                }
            }
        } else {
            Yii::info('refer pid ='. $referPidSession, 'mps');
        }

        // Nhan dien thue bao
        //$msisdn = '9688989774';
        $msisdn = Yii::$app->session->get('msisdn', null);

        Yii::info('MSISDN from session: '. $msisdn. ', pid='. $referPidSession, 'mps');

        if ($msisdn && Yii::$app->user->isGuest) {
            $this->autoLogin($msisdn);
        } else {
            // Check sdt tu header
            $msisdn = Helpers::convertMsisdn($_SERVER['HTTP_MSISDN']);
            Yii::info('MSISDN from header: '. $msisdn, 'mps');
            if ($msisdn) {
                $this->autoLogin($msisdn);
            } else {
                $checkMps = Yii::$app->session->get('CHECK_MPS');

                $msisdnEncode = Yii::$app->request->get('mdata', null);

//                if ($msisdnEncode) {
//                    $msisdn = Helpers::decodeAesString($msisdnEncode);
//                    if ($msisdn) {
//                        Yii::info('MSISDN from MPS: '. $msisdn,'mps');
//                        $this->autoLogin($msisdn);
//                        return $this->goHome();
//                    }
//                }
            }
        }

        return true;
    }

    private function autoLogin($msisdn) {
        $user = Subscriber::findByMsisdn($msisdn);
        if (!$user) {

            $user = new Subscriber();
            $user->MSISDN = $msisdn;
            $user->USERNAME = $msisdn;
            $user->STATUS = 1;
            $user->LAST_LOGIN_TIME = date('Y-m-d H:i:s');
            $user->save();
        }
        Yii::$app->session->set('msisdn', $msisdn);
        Yii::$app->user->login($user, 1800);
    }
}