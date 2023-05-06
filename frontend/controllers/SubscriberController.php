<?php
namespace frontend\controllers;

use common\core\MpsConnector;
use common\helpers\Helpers;
use common\models\LoanBase;
use frontend\models\ChangePassForm;
use frontend\models\LoginForm;
use frontend\models\GetOtpForm;
use frontend\models\OneTimePassword;
use frontend\models\PasswordResetRequestForm;
use frontend\models\Pkg;
use frontend\models\ResetPasswordForm;
use frontend\models\ServiceSubscriber;
use frontend\models\SignupForm;
use frontend\models\Subscriber;
use frontend\models\Charge;
use frontend\models\SystemSetting;
use Symfony\Component\EventDispatcher\Tests\Service;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class SubscriberController extends FrontendController
{
    public function actions() {

        return [
            'fast-login' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
            ],
        ];
    }

    public function actionSignUp()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new SignupForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $msisdn = Helpers::convertMsisdn($model->username);

                $sub = new Subscriber();
                $sub->MSISDN = $msisdn;
                $sub->USERNAME = $msisdn;
                $sub->REG_TIME = date('Y-m-d H:i:s');
                $sub->LAST_LOGIN_TIME = date('Y-m-d H:i:s');
                $sub->STATUS = 1;

                $sub->LANGUAGE = Yii::$app->language;
                $sub->setPassword($model->password);
                $sub->generatePasswordResetToken();
                $sub->save();

                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Sign up successfully!'));
                Yii::$app->session->set('msisdn', $sub->MSISDN);
                Yii::$app->user->login($sub);
                return $this->goHome();
            }
        }

        return $this->render('signUp', [
            'model' => $model
        ]);
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $loan = null;
        $msisdn = Yii::$app->session->get('msisdn', null);
        if ($msisdn) {
            $loan = LoanBase::findOne(['msisdn' => $msisdn]);
        }

        return $this->render('index', [
            'loan' => $loan,
        ]);
    }

    public function actionLogin() {

        if (!Yii::$app->user->isGuest) {
            //Yii::$app->session->setFlash('info', Yii::t('frontend', 'Bạn đang đăng nhập!'));
            return $this->goHome();
        }

        $source = Yii::$app->request->get('source', Yii::$app->session->get('login_source', null));
        if ($source) 
            Yii::$app->session->set('login_source', $source);
        
        $model = new LoginForm();
        $model->username = Yii::$app->request->get('msisdn', null);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            Yii::$app->session->set('rememberMe', $model->rememberMe);
            if ($model->login()) {
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Đăng nhập thành công!'));

                return $this->goHome();
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Lay lai mat khau
     * @return string|\yii\web\Response
     */
    public function actionResetPass($token) {
        $token = Yii::$app->request->get('token');
        if (!$token) {
            Yii::$app->session->setFlash('warning', Yii::t('frontend', 'Bạn không có quyền truy cập trang này'));
            return $this->goHome();
        } elseif (!Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', Yii::t('frontend', 'Bạn đang đăng nhập! Vui lòng thoát ra để thực hiện tính năng này!'));
            return $this->goHome();
        }

        $user = Subscriber::findByResetPassToken($token);
        if (!$user) {
            Yii::$app->session->setFlash('warning', Yii::t('frontend', 'Bạn không có quyền truy cập trang này'));
            return $this->goHome();
        }

        $model = new ResetPasswordForm($token);
        $model->setUser($user);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {

                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Mật khẩu của bạn đã được khôi phục thành công!'));

                return $this->redirect(Url::to(['subscriber/login']));
            }
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionChangePass() {
        // Check login
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', Yii::t('frontend', 'Bạn cần đăng nhập để sử dụng tính năng này!'));
            return $this->redirect(['subscriber/login']);
        }

        $userId = Yii::$app->user->getId();
        $model = new ChangePassForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $user = Yii::$app->user->identity;
                $user->setPassword($model->password);
                $user->save();

                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Đổi mật khẩu thành công!'));
                return $this->redirect(Url::to(['subscriber/change-pass']));
            }
        }

        return $this->render('changePass', [
            'model' => $model,

        ]);
    }

    /**
     * Trang quen mat khau
     */
    public function actionForgotPass() {

        $model = new PasswordResetRequestForm();
        $sendResult = null;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $sendResult = $model->sendEmail();
                //Yii::$app->session->setFlash('success', Yii::t('frontend', 'Vui lòng kiểm tra email để khôi phục mật khẩu của bạn!'));

            }
        }

        return $this->render('forgotPass', [
            'model' => $model,
            'sendResult' => $sendResult,
        ]);
    }



    /**
     * Xu ly sau khi dang nhap MXH thanh cong
     * @param $client
     * @return \yii\web\Response
     */
    public function oAuthSuccess($client) {
        // get user data from client
        $attributes = $client->getUserAttributes();

        $oAuthId = null;
        $fullName = '';
        $email = '';
        $avartarUrl = '';
        if ($client instanceof \yii\authclient\clients\Google) {
            $oAuthId = $attributes['id'];
            $fullName = $attributes['displayName'];
            $email = $attributes['emails'][0]['value'];
            $avartarUrl = str_replace('sz=50', 'sz=200', $attributes['image']['url']);
        } elseif ($client instanceof \yii\authclient\clients\Facebook) {
            $oAuthId = $attributes['id'];
            $fullName = $attributes['name'];
            $email = $attributes['email'];
            $avartarUrl = $attributes['picture']['data']['url'];
        }

        // Dang nhap thanh cong, co id
        if ($oAuthId) {
            $subscriber = Subscriber::getByOauthId($oAuthId);

            if (!$subscriber) {
                // Chua co user
                $newSub = new Subscriber();
                $newSub->insertUserSocial($oAuthId, $fullName, $email, $avartarUrl);

                Yii::$app->user->login($newSub);
                Yii::$app->getSession()->set("oauth_id", $newSub->oauth_id);
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Đăng nhập thành công!'));

                return $this->goBack();
            } else {
                Yii::$app->user->login($subscriber);
                Yii::$app->getSession()->set("oauth_id", $subscriber->oauth_id);
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Đăng nhập thành công!'));
            }
        }

    }


    public function actionGetOtp() {
        $this->layout = false;  
        $isFinish = false;
        $message = '';
        $model = new GetOtpForm();
        $model->msisdn = Yii::$app->session->get('msisdn');
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                
                // Luu OTP
                $otpStr = OneTimePassword::insertNewOtp($model->msisdn);

                // Gui tin nhan SMS
//                $content = Yii::t('frontend', SystemSetting::getConfigByKey('SMS_GET_OTP_MSG'), [
                $content = Yii::t('frontend', '{otp} la mat khau cua quy khach de su dung tren wapsite {url} Mat khau se het han sau {expired_minute} phut', [
                    'otp' => $otpStr,
                    'expired_minute' => Yii::$app->params['otp_expired_minutes'],
                    'url' => Yii::$app->params['wapsite_url'],

                ]);
                $send = MpsConnector::sendMt(Yii::$app->params['sms_shortcode'], $model->msisdn, $content);

                // Ket thuc 
                $isFinish = true; 
                $message = Yii::t('frontend', 'Mã OTP đã được gửi về số điện thoại của Quý Khách. Vui lòng kiểm tra tin nhắn SMS! Xin cảm ơn!');
            }
        }

        return $this->render('getOtp', [
            'getOtpModal' => $model,
            'isFinish' => $isFinish, 
            'message' => $message, 
        ]);
    }

    public function actionGetNewPass() {
        $isFinish = false;
        $message = '';
        $model = new GetOtpForm();
        $model->msisdn = Yii::$app->session->get('msisdn', Yii::$app->request->get('msisdn', null));
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                //$newPass = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvxyz'), 0, 6);
                $newPass = substr(str_shuffle('0123456789'), 0, 6);

                // update pass
                $webSub = Subscriber::findOrCreateNew($model->msisdn);
                $webSub->setPassword($newPass);
                $webSub->save();

                // Gui tin nhan SMS
                $content = Yii::t('frontend', '{password} is your password to use on website {url}', [
                    'password' => $newPass,
                    'url' => Yii::$app->params['wapsite_url'],

                ]);
                $send = MpsConnector::sendMt(Yii::$app->params['sms_shortcode'], $model->msisdn, $content);

                // Ket thuc
                $isFinish = true;
                $message = Yii::t('frontend', 'The new password has already sent to your phone number. Please check your SMS. Thank you!');
                Yii::$app->session->setFlash('info', $message);
                return $this->redirect(['subscriber/login', 'msisdn' => $model->msisdn]);
            }
        }

        return $this->render('getNewPass', [
            'getOtpModal' => $model,
            'isFinish' => $isFinish,
            'message' => $message,
        ]);
    }

    public function actionUpdateProfile() {
        // Check login
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', Yii::t('frontend', 'Bạn cần đăng nhập để sử dụng tính năng này!'));
            return $this->redirect(['subscriber/login']);
        }

        $errorMsg = null;
        $user = Yii::$app->user->identity;
        $serviceSub = $user->getServiceSubscriber();

        if ($serviceSub->BIRTHDAY) {
            $bdtimestapm = strtotime($serviceSub->BIRTHDAY);
            $day = date('d', $bdtimestapm);
            $month = date('m', $bdtimestapm);
            $year = date('Y', $bdtimestapm);
            $hour = date('H', $bdtimestapm);
            $minute = date('i', $bdtimestapm);
        }

        if (Yii::$app->request->isPost) {
            $day = Yii::$app->request->post('day', null);
            $month = Yii::$app->request->post('month', null);
            $year = Yii::$app->request->post('year', null);

            $hour = Yii::$app->request->post('hour', null);
            $minute = Yii::$app->request->post('minute', null);
            if ($hour !== null)
                $hour = str_pad($hour, 2, "0", STR_PAD_LEFT);
            if ($minute !== null)
                $minute = str_pad($minute, 2, "0", STR_PAD_LEFT);

            $hourValid = true;

            if (!checkdate($month, $day, $year)) {
                $errorMsg = Yii::t('frontend', 'Ngày sinh không hợp lệ!');
            } elseif (!preg_match("/^(?:2[0-3]|[01][0-9]|[0-9]):[0-5][0-9]$/", $hour. ':'. $minute)) {
                $hourValid = false;
                $errorMsg = Yii::t('frontend', 'Giờ sinh không hợp lệ!');
            } else {
                $user = Yii::$app->user->identity;
                $serviceSub = $user->getServiceSubscriber();
                if (!$serviceSub) {
                    $serviceSub = new ServiceSubscriber();
                    $serviceSub->PKG_ID = null;
                    $serviceSub->MSISDN = $user->MSISDN;
                    $serviceSub->STATUS = '0';
                }

                $serviceSub->BIRTHDAY = $year.'-'.$month.'-'.$day;
                if ($hourValid) {
                    $serviceSub->BIRTHDAY = $year.'-'.$month.'-'.$day. ' '. $hour. ':'. $minute;
                }
                $serviceSub->save(false);


                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Cập nhật ngày sinh thành công!'));
                return $this->redirect(Url::to(['subscriber/update-profile']));
            }
        }

        return $this->render('updateProfile', [
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'hour' => $hour,
            'minute' => $minute,
            'errorMsg' => $errorMsg,
        ]);
    }
}
