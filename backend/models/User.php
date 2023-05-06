<?php

namespace backend\models;

use Yii;
use common\models\UserBase;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

class User extends UserBase implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const SCENARIO_RESET_PASS = 'RESET_PASSWORD';
    const SCENARIO_CREATE_USER = 'CREATE_USER';

    const ADMIN_ROLE = 'agent';
    const SUPER_ADMIN_ROLE = 'supervisor';

    public $re_password;
    public $password_old;
    public $new_password;
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'email'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'fullname', 'address', 'new_password'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 500],
            [['auth_key'], 'string', 'max' => 32],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['email'], 'email'],
            [['username', 'email'], 'unique'],

            [['password_hash'], 'match', 'pattern' => '((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{6,20})',
                'message' => Yii::t('backend', 'Mật khẩu phải từ 6-20 ký tự và bao gồm chữ thường, chữ HOA, số và ký tự đặc biệt')],
            [['new_password'], 'match', 'pattern' => '((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{6,20})',
                'message' => Yii::t('backend', 'Mật khẩu phải từ 6-20 ký tự và bao gồm chữ thường, chữ HOA, số và ký tự đặc biệt'),
                'on' => self::SCENARIO_RESET_PASS],

            [['new_password'], 'required', 'on' => [self::SCENARIO_RESET_PASS, self::SCENARIO_CREATE_USER]],

            [['re_password'], 'compare', 'compareAttribute' => 'new_password', 'message' => Yii::t('backend', 'Mật khẩu gõ lại chưa đúng')],
            [['username', 'email', 'password_hash', 'fullname', 'address'], 'trim'],


            [['user_type', 'msisdn'], 'string'],
            [['id_province'], 'string', 'max' => 45],
            //[['user_type'], 'required'],
//            [['branch_id', 'partner_id'], 'integer'],
//            ['branch_id', 'required', 'when' => function ($model) {
//                return $model->user_type == 'branch';
//            }, 'whenClient' => "function (attribute, value) {
//                return $('#user-type').find(':checked').val() == 'branch';
//            }"],
//            ['partner_id', 'required', 'when' => function ($model) {
//                return $model->user_type == 'partner';
//            }, 'whenClient' => "function (attribute, value) {
//                return $('#user-type').find(':checked').val() == 'partner';
//            }"],

            [['msisdn'], 'match', 'pattern' => Yii::$app->params['phonenumber_pattern']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('backend', 'ID'),
            'username' => Yii::t('backend', 'Username'),
            'auth_key' => Yii::t('backend', 'Auth Key'),
            'new_password' => Yii::t('backend', 'New password'),
            'password_hash' => Yii::t('backend', 'Password'),
            're_password' => Yii::t('backend', 'Re-type password'),
            'password_reset_token' => Yii::t('backend', 'Password Reset Token'),
            'email' => Yii::t('backend', 'Email'),
            'msisdn' => Yii::t('backend', 'Phone number'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'password_old' => Yii::t('backend', 'Old password'),
            'fullname' => Yii::t('backend', 'Fullname'),
            'address' => Yii::t('backend', 'Address'),
            'id_province' => Yii::t('backend', 'ID Province'),
//            'partner_id' => Yii::t('backend', 'Partner'),

        ];
    }

    /**
     * @return bool
     */
    public function beforeSave($insert = true) {
        if ($this->new_password) {
            $this->setPassword($this->new_password);
            $this->generateAuthKey();
            $this->generatePasswordResetToken();
        }
        if (!$this->isNewRecord) {
            $insert = false;
            if (!$this->new_password) {

                $this->password_hash = $this->getOldAttribute('password_hash');
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by token
     *
     * @param string $token
     * @return static|null
     */
    public static function findByToken($token) {
        return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public static function getUserId() {
        $user = Yii::$app->get('user', false);
        return $user && !$user->isGuest ? $user->id : null;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * Tai khoan co phai la admin ko?
     * Yii::$app->user->identity->isAdmin()
     * @return bool
     */
    public function isAdmin() {
        return (Yii::$app->user->can(self::ADMIN_ROLE) || Yii::$app->user->can(self::SUPER_ADMIN_ROLE));
    }

    /**
     * Tai khoan co phai super admin ko
     * Yii::$app->user->identity->isSuperAdmin()
     * @return bool
     */
    public function isSuperAdmin() {
        return (Yii::$app->user->can(self::SUPER_ADMIN_ROLE));
    }

    public static function getUserTypeArr() {
        $user = Yii::$app->user->identity;
        switch ($user->user_type) {
            case 'supervisor':
                return [
                    'supervisor' => Yii::t('backend', 'Supervisor'),
                    'agent' => Yii::t('backend', 'Agent'),
//                    'branch' => Yii::t('backend', 'Branch'),
//                    'partner' => Yii::t('backend', 'Partner'),
                ];
                break;
//            case 'ho':
//                return [
//                    'ho' => Yii::t('backend', 'HO'),
////                    'branch' => Yii::t('backend', 'Branch'),
//                    'partner' => Yii::t('backend', 'Partner'),
//                ];
                break;
//            case 'branch':
//                return [
//                    'branch' => Yii::t('backend', 'Branch'),
//                    'partner' => Yii::t('backend', 'Partner'),
//                ];
//                break;
//            case 'partner':
//                break;
        }
        return [
            'supervisor' => Yii::t('backend', 'Supervisor'),
            'agent' => Yii::t('backend', 'Agent'),
//            'branch' => Yii::t('backend', 'Branch'),
//            'partner' => Yii::t('backend', 'Partner'),
        ];
    }

    public function getUserTypeName() {
        $types = self::getUserTypeArr();
        return isset($types[$this->user_type])? $types[$this->user_type]: $this->user_type;
    }

    public static function getAllSupportStatus() {
        return [
            '0' => Yii::t('backend', 'Offline'),
            '1' => Yii::t('backend', 'Available'),
            '2' => Yii::t('backend', 'Busy'),
        ];
    }

    public function getDisplaySupportStatus() {
        $allSupportStatus = self::getAllSupportStatus();
        return in_array($this->support_status, array_keys($allSupportStatus)) ? $allSupportStatus[$this->support_status] : $this->support_status;
    }


}
