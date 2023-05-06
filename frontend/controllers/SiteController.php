<?php
namespace frontend\controllers;

use common\core\MpsConnector;
use common\core\MytelApiGw;
use common\helpers\Helpers;
use common\models\MscCollectionBase;
use frontend\models\GetOtpForm;
use frontend\models\LoanPack;
use frontend\models\LoanType;
use frontend\models\LoginForm;
use frontend\models\OneTimePassword;
use frontend\models\Pkg;
use frontend\models\Subscriber;
use Yii;
use yii\base\Theme;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\SimpleImage;
use yii\helpers\Url;
use yii\web\Request;

/**
 * Site controller
 */
class SiteController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {

        return [

            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],

            'captcha' => [
                'class' => 'common\components\captcha\BackendCaptcha',
                'transparent' => false,
                'foreColor' => 0x800080,
                'backColor' => 0xffffff,
                'minLength' => 2,
                'maxLength' => 3,
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

    public function actionChangeTheme($theme_name)
    {
        if (Yii::$app->request->get('theme_name')) {
            Yii::$app->getView()->theme = new Theme([
                'basePath' => '@app/themes/basic'. $theme_name,
                'baseUrl' => '@web/themes/'. $theme_name,
                'pathMap' => [
                    '@app/views' => '@app/themes/'. $theme_name,
                ],
            ]);
        }
        return $this->render('index');
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->view->title = Yii::t('frontend', 'Trang chủ | umusic');
        $collections = Yii::$app->cache->getOrSet('collections', function(){
            return MscCollectionBase::find()->with(['contents', 'contents.album', 'contents.playlist', 'contents.category'])->all();
        });
        return $this->render('index', ['collections' => $collections]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }



    public function actionError()
    {
        $this->layout = 'fullWidth';
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }

    public function actionChangeLanguage() {

        $lang = $_GET['lang']; // Yii::$app->request->get('lang', 'vi');
        $referrer = 'http://localhost:9016/owen-ihe-wase'; //Yii::$app->request->referrer;
        $referrer = 'http://localhost:9016/chuyen-san/123/dffd-df'; //Yii::$app->request->referrer;

        if (in_array($lang, Yii::$app->params['language_support'])) {
//            $referrerRequest = new Request();
//            $referrerRequest->setUrl(parse_url($referrer, PHP_URL_PATH));
//            $referrerRouting = Yii::$app->getUrlManager()->parseRequest($referrerRequest);

            Yii::$app->session->set('language', $lang);
            Yii::$app->language = $lang;

            return $this->goHome();

        } else {
            return $this->goHome();
        }

    }

    public function actionTerms() {

        if (Yii::$app->language == 'en') {
            return $this->render('termsEn', [
            ]);
        } else {
            return $this->render('termsMm', [
            ]);
        }

    }

    public function actionRegisterOtp() {
        if (!Yii::$app->user->isGuest) {

            $serviceSub = (!Yii::$app->user->isGuest)? Yii::$app->user->identity->getServiceSubscriber(): null;
            if (!$serviceSub || !$serviceSub->isActivatingService()) {
                return $this->redirect(['subscriber/index']);
            }
        }
        $model = new GetOtpForm();
        $model->msisdn = Helpers::convertMsisdn(Yii::$app->request->get('msisdn'));
        $package = Pkg::findOne(1);

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

                return $this->redirect(['site/register-otp-2', 'msisdn' => $model->msisdn]);
            }
        }

        return $this->render('registerOtp', [
            'getOtpModal' => $model,
            'package' => $package
        ]);
    }

    public function actionRegisterOtp2() {
        if (!Yii::$app->user->isGuest) {
            $serviceSub = (!Yii::$app->user->isGuest)? Yii::$app->user->identity->getServiceSubscriber(): null;
            if (!$serviceSub || !$serviceSub->isActivatingService()) {
                return $this->redirect(['subscriber/index']);
            }
        }
        $package = Pkg::findOne(1);
        $model = new LoginForm();
        $model->username = Yii::$app->request->get('msisdn');
        $model->using_otp = true;
        $model->load(Yii::$app->request->post());

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Dang ky dv

            return $this->redirect(['mps/register-service', 'pkg_id' => 1]);
        }

        return $this->render('registerOtp2', [
            'model' => $model,
            'package' => $package
        ]);
    }

    /**
     * Xu ly login sdt bang token cua MyID
     * @param $token
     */
    public function actionLoginByToken($type, $token = null) {

        if (!$token || $token == '<token>') {
            return $this->goHome();
        }

        Yii::info('Login By Token: '. $type. '; token='. $token, 'mytelapi');

        // Luu lai vao phien giao dich
        Yii::$app->session->set('REFER_CHANNEL', $type);

        $responseArr = MytelApiGw::getSubInfoByMyIdToken($token);

        if ($responseArr['errorCode'] == 0 && isset($responseArr['result']['content'])) {
            $msisdn = '';
            $subArr = $responseArr['result']['content'];
            foreach ($subArr as $sub) {
                if ($sub["verify"] == true && isset($sub['isdn'])) {
                    $msisdn = $sub['isdn'];
                    break;
                }
            }

            $msisdn = Helpers::convertMsisdn($msisdn);
            // Tao sub
            $user = Subscriber::findOne(['MSISDN' => $msisdn]);
            if (!$user) {
                // Tao moi
                $user = new Subscriber();
                $user->MSISDN = $msisdn;
                $user->USERNAME = $msisdn;
                $user->REG_TIME = date('Y-m-d H:i:s');
                $user->LAST_LOGIN_TIME = date('Y-m-d H:i:s');
                $user->STATUS = 1;
                // $user->CHANNEL_ID = 4;// WEB
                $user->LANGUAGE = Yii::$app->language;
                $user->save(false);
            }
            Yii::info('Login By Token: msisdn='. $user->MSISDN, 'mytelapi');
            Yii::$app->session->set('msisdn', $user->MSISDN);
            Yii::$app->user->login($user);
            return $this->goHome();

        } else {
            return $this->goHome();
        }

    }

    public function actionSignIn() {
        Yii::$app->view->title = Yii::t('frontend', 'Đăng nhập');
        $this->layout = 'fullWidth';
        Yii::$app->view->params['bodyClass'] = 'bg-light';
        return $this->render('signin');
    }

    public function actionSignInByPhone() {
        Yii::$app->view->title = Yii::t('frontend', 'Đăng nhập');
        $this->layout = 'fullWidth';
        Yii::$app->view->params['bodyClass'] = 'bg-light';
        return $this->render('signInByPhone');
    }
}