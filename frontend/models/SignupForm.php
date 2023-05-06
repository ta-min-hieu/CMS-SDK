<?php
namespace frontend\models;

use common\helpers\Helpers;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $otp;
    public $password;
    public $re_password;
    public $captcha;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'captcha', 're_password', 'otp'], 'trim'],

            [['username', 'otp', 'password'], 'required', 'message' => Yii::t('frontend', 'Bắt buộc')],
            [['username'], 'match', 'pattern' => Yii::$app->params['phonenumber_pattern'], 'message' => Yii::t('frontend', 'Số điện thoại không hợp lệ!')],
            ['username', 'validateSub'],
            //['username', 'unique', 'targetClass' => Subscriber::class, 'targetAttribute' => ['username' => 'MSISDN'], 'message' => Yii::t('frontend', 'Tài khoản đã tồn tại!')],
            //['username', 'string', 'min' => 3, 'max' => 200, 'message' => Yii::t('frontend', 'Vui lòng nhập từ 3 - 200 ký tự!')],

            ['otp', 'validateOtp'],

            ['password', 'string', 'min' => 6, 'message' => Yii::t('frontend', 'Mật khẩu cần tối thiểu 6 ký tự')],
            ['re_password', 'compare', 'compareAttribute'=>'password', 'message'=>  Yii::t('frontend', "Nhập lại mật khẩu mới chưa khớp!") ],
            ['captcha', 'safe', 'message' => Yii::t('frontend', 'Mã xác nhận không đúng')],
        ];
    }

    public function validateSub($attribute, $params) {
        if (!$this->hasErrors()) {
            // Validate Sub
            $checkSub = Subscriber::findOne(['MSISDN' => Helpers::convertMsisdn($this->username)]);
            if (!$checkSub) {
                return true;
            } else {
                $this->addError($attribute, Yii::t('frontend', 'Tài khoản đã tồn tại!'));
            }
        }
    }

    public function validateOtp($attribute, $params) {
        if (!$this->hasErrors()) {

            // Validate OTP
            $checkOtp = OneTimePassword::checkOtp($this->username, $this->otp);
            if ($checkOtp > 0) {
                return true;
            } else {

                $this->addError($attribute, Yii::t('frontend', 'Mã OTP không hợp lệ, vui lòng thử lại!'));
            }
        }
    }
}
