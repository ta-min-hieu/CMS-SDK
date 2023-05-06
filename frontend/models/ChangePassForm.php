<?php

namespace frontend\models;

use Yii;
use yii\base\Model;


class ChangePassForm extends Model {

    public $using_otp;
    public $old_password;
    public $password;
    public $captcha;
    public $token = null;
    public $new_password = null;
    public $retype_new_password = null;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['old_password', 'new_password', 'password', 'captcha'], 'required', 'message' => Yii::t('frontend', 'Bắt buộc')],
            // password is validated by validatePassword()
            ['old_password', 'validatePassword'],
            ['password', 'compare', 'compareAttribute'=>'new_password', 'message'=>  Yii::t('frontend', "Nhập lại mật khẩu mới chưa khớp!") ],
            ['captcha', 'captcha', 'message' => Yii::t('frontend', 'Mã xác nhận không đúng')],
            [['captcha'], 'trim'],
            ['using_otp', 'boolean'],
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
            $user = Yii::$app->user->identity;

            if ($this->using_otp) {
                // Validate OTP
                $checkOtp = OneTimePassword::checkOtp($user->MSISDN, $this->old_password);
                if ($checkOtp > 0) {
                    return true;
                } else {
                    $this->addError($attribute, Yii::t('frontend', 'Mã OTP không hợp lệ, vui lòng thử lại!'));
                }
            } else {

                if (!$user || !$user->validatePassword($this->old_password)) {
                    $this->addError($attribute, Yii::t('frontend', 'Mật khẩu cũ không đúng!'));
                }
            }

        }
    }

    public function attributeLabels()
    {
        return [
            'old_password' => Yii::t('frontend', 'Mật khẩu cũ'),
            'new_password' => Yii::t('frontend', 'Mật khẩu mới'),
            'password' => Yii::t('frontend', 'Nhập lại mật khẩu mới'),
            'captcha' => Yii::t('frontend', 'Nhập lại mật khẩu mới'),
        ];
    }

}
