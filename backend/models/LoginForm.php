<?php

namespace backend\models;

use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\base\Model;
//use yii\captcha\Captcha;

/**
 * Login form
 */
class LoginForm extends Model
{

    public $username;
    public $password;
    public $rememberMe = true;
    public $isFirstLogin = false;
    public $token = null;
    private $_user;
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            [['username'], 'trim'],
            ['reCaptcha', 'captcha'],
            //['reCaptcha', 'safe'],
            // Google captcha
            //[['reCaptcha'], ReCaptchaValidator::className(), 'secret' => Yii::$app->params['recaptcha_secret']]
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('backend', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $this->isFirstLogin = $user->is_first_login;
            if ($this->isFirstLogin) {
                $this->token = $user->password_reset_token;
            }
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $user->last_time_login = date('Y-m-d H:i:s');
            $user->save(false);

            return Yii::$app->user->login($user, $this->rememberMe ? 1800 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('backend', 'Password'),
            're_password' => Yii::t('backend', 'Re-type new password'),
            'captcha' => Yii::t('backend', 'Captcha'),
            'username' => Yii::t('backend', 'Username'),
        ];
    }
}
