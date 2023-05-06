<?php
namespace frontend\models;
use yii;
use yii\base\Model;
use yii\base\InvalidParamException;

/**
 * Password reset form
 */
class GetOtpForm extends Model
{
    public $msisdn;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msisdn', 'captcha'], 'required', 'message' => Yii::t('frontend', 'Bắt buộc')],
            [['msisdn'], 'match', 'pattern' => Yii::$app->params['phonenumber_pattern']],
            ['captcha', 'captcha', 'message' => Yii::t('frontend', 'Mã xác nhận không đúng')],
            [['msisdn', 'captcha'], 'trim'],
        ];
    }

}
