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
class UserZaloDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_zalo';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsdk');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','phone_number'], 'required'],
            [['gender'], 'integer'],
            [['disp_name','birth_day','avatar','type','agent','email','phone_number'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID Khách Hàng Zalo'),
            'phone_number' => Yii::t('backend', 'Số Điện Thoại'),
            'gender' => Yii::t('backend', 'Giới Tính'),
            'disp_name' => Yii::t('backend', 'Tên Khách Hàng'),
            'birth_day' => Yii::t('backend', 'Ngày Sinh'),
            'avatar' => Yii::t('backend', 'Ảnh'),
            'type' => Yii::t('backend', 'Loại'),
            'email' => Yii::t('backend', 'Email'),
            'agent' => Yii::t('backend', 'Nhân Viên Tiếp Nhận Tin Nhắn'),
        ];
    }
}
