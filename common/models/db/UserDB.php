<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property string $last_time_login
 * @property integer $is_first_login
 * @property string $cp_id
 * @property integer $num_login_fail
 * @property string $fullname
 * @property string $address
 * @property string $image_path
 * @property integer $updated_at
 * @property integer $created_at
 */
class UserDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'be_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'updated_at', 'created_at'], 'required'],
            [['status', 'is_first_login', 'cp_id', 'num_login_fail', 'updated_at', 'created_at'], 'integer'],
            [['last_time_login'], 'safe'],
            [['address'], 'string'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'fullname', 'image_path'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'username' => Yii::t('backend', 'Username'),
            'auth_key' => Yii::t('backend', 'Auth Key'),
            'password_hash' => Yii::t('backend', 'Password Hash'),
            'password_reset_token' => Yii::t('backend', 'Password Reset Token'),
            'email' => Yii::t('backend', 'Email'),
            'status' => Yii::t('backend', 'Status'),
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
