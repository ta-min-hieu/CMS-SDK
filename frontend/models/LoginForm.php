<?php

namespace frontend\models;

use common\helpers\Helpers;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {

    public $username; // Dang dung SDT thay cho username
    public $password;
    public $using_otp;
    public $captcha;
    public $rememberMe = true;
    #public $isFirstLogin = false;
    public $token = null;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'message' => Yii::t('frontend', 'Bắt buộc')],
            [['username'], 'match', 'pattern' => Yii::$app->params['phonenumber_pattern'], 'message' => Yii::t('frontend', 'Số điện thoại không hợp lệ!')],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['using_otp', 'boolean'],

            ['captcha', 'captcha', 'message' => Yii::t('frontend', 'Mã xác nhận không đúng')],
            [['username', 'captcha'], 'trim'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {

            // Kiem tra co bi khoa vi nhap sai pass nhieu lan ko ?
            if (Subscriber::checkIsLoginFail($this->username)) {
                $this->addError($attribute, Yii::t('frontend', 'Account blocked for {locked_time} minutes! Please come back later!', [
                    'locked_time' => Yii::$app->params['locked_time']
                ]));
            } else {
                $user = $this->getUser();
                if ($this->using_otp) {
                    // Validate OTP
                    $checkOtp = OneTimePassword::checkOtp($this->username, $this->password);
                    if ($checkOtp > 0) {
                        return true;
                    } else {
                        // Sai OTP --> cap nhat so lan sai pass
                        Subscriber::updateLoginFailSession(Helpers::convertMsisdn($this->username));

                        if ($user) {
                            // Update so lan dang nhap sai
                            $user->updateLoginFail();
                        }
                        $this->addError($attribute, Yii::t('frontend', 'Mã OTP không hợp lệ, vui lòng thử lại!'));
                    }
                } else {
                    if (!$user || !$user->validatePassword($this->password)) {
                        // Thong tin dang nhap khong dung --> cap nhat so lan sai pass
                        Subscriber::updateLoginFailSession(Helpers::convertMsisdn($this->username));
                        if ($user) {
                            // Update so lan dang nhap sai
                            $user->updateLoginFail();
                        }
                        $this->addError($attribute, Yii::t('frontend', 'Thông tin đăng nhập không đúng'));
                    }
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        try {
            if ($this->validate()) {
                $user = $this->getUser();
//            $this->isFirstLogin = $user->is_first_login;
//            if ($this->isFirstLogin) {
//                $this->token = $user->password_reset_token;
//            }
                if ($user) {
                    $user->last_login_time = date('Y-m-d H:i:s');
                    $user->save();
                } else {
                    $user = new Subscriber();
                    $user->msisdn = Helpers::convertMsisdn($this->username);
                    $user->username = Helpers::convertMsisdn($this->username);
                    $user->reg_time = date('Y-m-d H:i:s');
                    $user->last_login_time = date('Y-m-d H:i:s');
                    $user->status = 1;
                    //$user->channel_id = 4;// WEB
                    $user->language = Yii::$app->language;
                    $user->save();
                    $this->_user = $user;
                }
                Yii::$app->session->set('msisdn', $user->msisdn);
                return Yii::$app->user->login($user, $this->rememberMe ? 1800 : 0);
            } else {

                return false;
            }
        } catch(Exception $e) {
            return false;
        }

    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $username = Helpers::convertMsisdn($this->username);
            $this->_user = Subscriber::findByMsisdn($username);
        }

        return $this->_user;
    }

}
