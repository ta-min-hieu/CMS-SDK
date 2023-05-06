<?php

namespace frontend\models;

use Yii;
use yii\base\Model;


class UpdateProfileForm extends Model {

    // Birthday
    public $bday;
    public $bmonth;
    public $byear;
    public $birthday;
//    public $captcha;
    public $token = null;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['captcha'], 'required', 'message' => Yii::t('frontend', 'Bắt buộc')],
            [['birthday', 'bday', 'bmonth', 'byear'], 'safe'],
            ['birthday', 'validateBirthday'],

            ['captcha', 'captcha', 'message' => Yii::t('frontend', 'Mã xác nhận không đúng')],
            [['captcha'], 'trim'],
        ];
    }

    /**
     * Validates birthday
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateBirthday($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;


            if (!checkdate($this->bmonth, $this->bday, $this->byear)) {
                $errorArr[] = Yii::t('frontend', 'Ngày sinh không hợp lệ!');
                return false;
            }
            $this->birthday = $this->byear. '-'. $this->bmonth. '-'. $this->bday;

            return true;
        }
    }

    public function attributeLabels()
    {
        return [
            'birthday' => Yii::t('frontend', 'Ngày sinh'),

            'captcha' => Yii::t('frontend', 'Nhập lại mật khẩu mới'),
        ];
    }

}
