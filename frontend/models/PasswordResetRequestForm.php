<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'captcha'], 'required', 'message' => 'Bắt buộc'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\frontend\models\Subscriber',
                'filter' => ['status' => Subscriber::STATUS_ACTIVE],
                'message' => 'Email này không tồn tại trong hệ thống.'
            ],
            ['captcha', 'captcha', 'message' => 'Mã xác nhận không đúng'],
            [['email', 'captcha'], 'trim'],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = Subscriber::findOne([
            'status' => Subscriber::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!Subscriber::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Khôi phục mật khẩu ' . Yii::$app->name)
            ->send();
    }
}
