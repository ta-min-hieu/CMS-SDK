<?php

namespace backend\controllers;

use backend\models\CheckHobbyUrlForm;
use backend\models\LoginForm;
use backend\models\ResetPasswordForm;

use backend\models\User;
use backend\models\UserLocked;
use backend\models\UserLoginFailed;
use common\helpers\NetworkHelper;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $layout = 'default';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'common\components\captcha\BackendCaptcha',
                'transparent' => false,
                'foreColor' => 0x800080,
                'backColor' => 0xf5f5f5,
                'minLength' => 4,
                'maxLength' => 6,
                'offset' => -2,
                'chars' => 'abcdefhjkmnpqrstuxyz2345678',
                'libfont' => [
                    0 => '@backend/web/css/fonts/captcha/vavobi.ttf',
                    1 => '@backend/web/css/fonts/captcha/momtype.ttf',
                    2 => '@backend/web/css/fonts/captcha/captcha4.ttf'
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main';
        if (!Yii::$app->user->isGuest) {

            $user = Yii::$app->user;

            return $this->render('index', [

            ]);
        }
        return $this->redirect('login');
    }

    public function actionLogin()
    {

        $model = new LoginForm();
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Check lock username
            $username = $model->username;
            $ip = NetworkHelper::getRemoteIp(Yii::$app->params['get-ip-method']);
            $time = time();
            $lockedUsernameDuration = Yii::$app->params['login-failed']['locked_username_duration'];
            $lockedIpDuration = Yii::$app->params['login-failed']['locked_ip_duration'];

            if (!UserLocked::isUsernameLocked($username, $time - $lockedUsernameDuration)) {
                if (!UserLocked::isIpLocked($ip, $time - $lockedIpDuration)) {
                    $limitUsername = Yii::$app->params['login-failed']['limit_username'];
                    $limitUsernameDuration = Yii::$app->params['login-failed']['limit_username_duration'];
                    $numUsernameFailed = UserLoginFailed::countByUsername($username, $time - $limitUsernameDuration);
                    if ($numUsernameFailed <= $limitUsername) {
                        $limitIp = Yii::$app->params['login-failed']['limit_ip'];
                        $limitIpDuration = Yii::$app->params['login-failed']['limit_ip_duration'];
                        $numIpFailed = UserLoginFailed::countByIp($ip, $time - $limitIpDuration);
                        if ($numIpFailed <= $limitIp) {
                            Yii::$app->session->set('rememberMe', $model->rememberMe);
                            if ($model->login()) {
                                UserLocked::unlockIp($ip);
                                UserLocked::unlockUsername($username);
                                UserLoginFailed::clearUsername($username);
                                if ($model->isFirstLogin && $model->token) {
                                    Yii::$app->user->logout();
                                    return $this->redirect('/site/reset-password?token=' . $model->token);
                                }
                                return $this->goBack();
                            } else {
                                if ($numIpFailed == $limitIp) {
                                    //UserLocked::lockIp($ip, $time);
                                    Yii::$app->session->setFlash('error', Yii::t('backend', 'Your IP {ip} login failed ' .
                                    'over {failed_time} times in {duration} minutes. Your IP has been locked ' .
                                    'in {locked_duration} minutes', [
                                        'ip' => $ip,
                                        'failed_time' => $limitIp,
                                        'duration' => $limitIpDuration / 60,
                                        'locked_duration' => $lockedIpDuration / 60
                                    ]));
                                } else if ($numUsernameFailed == $limitUsername) {
                                    //UserLocked::lockUsername($username, $time);
                                    Yii::$app->session->setFlash('error', Yii::t('backend', 'Your username {username} ' .
                                    'login failed over {failed_time} times in {duration} minutes. Your username ' .
                                    'has been locked in {locked_duration} minutes', [
                                        'username' => $username,
                                        'failed_time' => $limitUsername,
                                        'duration' => $limitUsernameDuration / 60,
                                        'locked_duration' => $lockedUsernameDuration / 60
                                    ]));
                                } else {
                                    Yii::$app->session->setFlash('error', Yii::t('backend', 'Username or password is invalid'));
                                    //UserLoginFailed::log($username, null, $ip, $time);
                                }
                            }
                        } else {
                            //UserLocked::lockIp($ip, $time);
                            Yii::$app->session->setFlash('error', Yii::t('backend', 'Your IP {ip} login failed over ' .
                            '{failed_time} times in {duration} minutes. Your IP has been locked in ' .
                            '{locked_duration} minutes', [
                                'ip' => $ip,
                                'failed_time' => $limitIp,
                                'duration' => $limitIpDuration / 60,
                                'locked_duration' => $lockedIpDuration / 60
                            ]));
                        }
                    } else {
                        //UserLocked::lockUsername($username, $time);
                        Yii::$app->session->setFlash('error', Yii::t('backend', 'Your username {username} login failed ' .
                        'over {failed_time} times in {duration} minutes. Your username has been locked in ' .
                        '{locked_duration} minutes', [
                            'username' => $username,
                            'failed_time' => $limitUsername,
                            'duration' => $limitUsernameDuration / 60,
                            'locked_duration' => $lockedUsernameDuration / 60
                        ]));
                    }
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('backend', 'Your IP {ip} has been locked in ' .
                    '{locked_duration} minutes', [
                        'ip' => $ip,
                        'locked_duration' => $lockedIpDuration / 60
                    ]));
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('backend', 'Your username {username} has been locked in ' .
                '{locked_duration} minutes', [
                    'username' => $username,
                    'locked_duration' => $lockedUsernameDuration / 60
                ]));
            }
        }
        if (is_iterable($model->username) || is_iterable($model->password)) {
            Yii::$app->session->setFlash('success', 'An Error Occurred');
            return $this->redirect(['login']);
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionGetClients()
    {
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $clients = Yii::$app->session->get('client_list');
            if ($clients && count($clients)) {
                return ArrayHelper::toArray($clients);
            }
        }
        return [];
    }

    public function actionResetPassword()
    {
        $token = Yii::$app->request->get('token');
        if (!Yii::$app->user->isGuest || !$token) {
            Yii::$app->session->setFlash('error', Yii::t('backend', 'You cannot access reset password page'));
            return $this->goHome();
        }
        $user = User::findByToken($token);
        if (!$user) {
            Yii::$app->session->setFlash('error', Yii::t('backend', 'You cannot access reset password page'));
            return $this->goHome();
        }
        $model = new ResetPasswordForm();
        $model->username = $user->username;
        $model->setUser($user);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                Yii::$app->session->setFlash('success', Yii::t('backend', 'You has been charged your password successful'));
                return $this->goBack();
            }
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionChangeLanguage() {

        $lang = Yii::$app->request->get('lang', Yii::$app->params['default_content_lang']);

        if (in_array($lang, array_keys(Yii::$app->params['content_languages']))) {
            Yii::$app->session->set('language', $lang);
            Yii::$app->language = $lang;

            return $this->redirect(Yii::$app->request->getReferrer());

        } else {
            return $this->redirect(Yii::$app->request->getReferrer());
        }

    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
}
	