<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ResetPasswordForm extends Model
{

    public $username;
    public $password;
    public $re_password;
    public $captcha;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'captcha'], 'required'],
            // rememberMe must be a boolean value
            ['captcha', 'captcha'],
            [['username', 'captcha'], 'trim'],
            [['re_password'], 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('backend', 'Mật khẩu gõ lại chưa đúng')],
            [['password'], 'match', 'pattern' => '((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,20})',
                'message' => Yii::t('backend', 'Mật khẩu phải từ 6-20 ký tự và bao gồm chữ thường, chữ HOA, số và ký tự đặc biệt')],
        ];
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
            if ($user) {
                $user->last_time_login = date("Y-m-d H:i:s", time());
                $user->new_password = $this->password;
                $user->is_first_login = 0;
                $user->save();
                return Yii::$app->user->login($user, Yii::$app->session->get('rememberMe') ? 1800 : 0);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('backend', 'You cannot access reset password page'));
                return false;
            }
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
        return $this->_user;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function attributeLabels() {
        return [
            'password' => Yii::t('backend', 'New password'),
            're_password' => Yii::t('backend', 'Re-type new password'),
            'captcha' => Yii::t('backend', 'Captcha'),
            'username' => Yii::t('backend', 'Username'),
        ];
    }
}
