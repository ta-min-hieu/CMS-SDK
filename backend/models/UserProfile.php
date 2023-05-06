<?php

namespace backend\models;

use Yii;

class UserProfile extends User
{
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password_hash'], 'safe'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'new_password', 'password_reset_token', 'email', 'fullname'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 500],
            [['auth_key'], 'string', 'max' => 32],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['email'], 'email'],
            [['username', 'email'], 'unique'],
            [['username', 'email', 'new_password', 'fullname', 'address'], 'trim'],
            [['re_password'], 'compare', 'compareAttribute' => 'new_password', 'message' => Yii::t('backend', 'Mật khẩu mới gõ lại chưa khớp')],
            [['new_password'], 'match', 'pattern' => '((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{6,20})',
                'message' => Yii::t('backend', 'Mật khẩu phải lớn hơn 6 ký tự và bao gồm chữ thường, chữ HOA, số và ký tự đặc biệt')],
            [['password_old'], 'verifyOldPassword'],
            [['re_password'], 'verifyNewPassword'],

        ];
    }

    public function verifyOldPassword($attribute, $params) {

        // Check mat khau hien tai co dung ko
        if ($this->password_old && !password_verify($this->password_old, $this->password_hash)) {

            $this->addError($attribute, 'Mật khẩu hiện tại không đúng!');
            return false;

        }

        return true;
    }

    public function verifyNewPassword($attribute, $params) {

        if ($this->re_password && $this->password_old && password_verify($this->password_old, $this->password_hash) && password_verify($this->re_password, $this->password_hash)) {
            // Check mat khau moi co trung voi mat khau cu khong
            // Chi check neu mat khau cu hop le
            $this->addError($attribute, 'Mật khẩu mới không được trùng với mật khẩu cũ');
            return false;
        }

        return true;
    }

    public function beforeSave($insert = true)
    {
        $this->username = $this->username;

        return parent::beforeSave($insert);
    }

    public function getRoleName()
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        if (!$roles) {
            return null;
        }

        reset($roles);
        /* @var $role \yii\rbac\Role */
        $role = current($roles);

        return $role->name;
    }
}