<?php

namespace common\models;

use Yii;

class UserBase extends \common\models\db\UserDB {

    public function __toString()
    {
        return $this->username;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'username' => Yii::t('backend', 'Username'),
            'auth_key' => Yii::t('backend', 'Auth Key'),
            'password_hash' => Yii::t('backend', 'Password Hash'),
            'password_reset_token' => Yii::t('backend', 'Password Reset Token'),
            'email' => Yii::t('backend', 'Email'),
            'status' => Yii::t('backend', 'Active/Inactive'),
            'last_time_login' => Yii::t('backend', 'Last Time Login'),
            'is_first_login' => Yii::t('backend', 'Is First Login'),
            'cp_id' => Yii::t('backend', 'Cp ID'),
            'num_login_fail' => Yii::t('backend', 'Num Login Fail'),
            'fullname' => Yii::t('backend', 'Fullname'),
            'address' => Yii::t('backend', 'Address'),
            'image_path' => Yii::t('backend', 'Image Path'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}