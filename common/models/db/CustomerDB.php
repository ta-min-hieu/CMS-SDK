<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 */
class CustomerDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_info';
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
            [['sUserID','serviceID','username','phonenumber','country','birthday','gender','fullname','app_id','type_user'], 'required'],
            [['sUserID','serviceID','username','birthday'], 'string', 'max' => 100],
            [['phonenumber'], 'string', 'max' => 20],
            [['fullname','created_at'], 'string', 'max' => 200],
            [['country','pushid','app_provision','app_revision','device_os_type','device_os_version','device_id','app_id','id_province'], 'string', 'max' => 50],
            [['avatar'], 'string', 'max' => 1000],
            [['type_user','state'], 'string', 'max' => 5],
            [['gender'], 'integer'],
            [['phonenumber'], 'unique'],
            ['phonenumber','match','pattern'=>'/^(84|0)+([0-9]{8,10})$/',],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sUserID' => Yii::t('backend', 'SUser ID'),
            'serviceID' => Yii::t('backend', 'Service'),
            'username' => Yii::t('backend', 'Username'),
            'phonenumber' => Yii::t('backend', 'Phone Number'),
            'country' => Yii::t('backend', 'Country'),
            'birthday' => Yii::t('backend', 'Birthday'),
            'gender' => Yii::t('backend', 'Gender'),
            'fullname' => Yii::t('backend', 'Fullname'),
            'pushid' => Yii::t('backend', 'Push ID'),
            'app_provision' => Yii::t('backend', 'App Provision'),
            'app_revision' => Yii::t('backend', 'App Revision'),
            'device_os_type' => Yii::t('backend', 'Device Os Type'),
            'device_os_version' => Yii::t('backend', 'Device Os Version'),
            'device_id' => Yii::t('backend', 'Device ID'),
            'app_id' => Yii::t('backend', 'App ID'),
            'avatar' => Yii::t('backend', 'Avatar'),
            'created_at' => Yii::t('backend', 'Created At'),
            'id_province' => Yii::t('backend', 'Tỉnh/Thành Phố'),
        ];
    }
}
