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
class OfficialAccountDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'official_account';
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
            [['appname','hostname','serviceID'], 'required'],
            [['appname','hostname'], 'string', 'max' => 200],
            [['thumb','created_at'], 'string', 'max'=>500],
            [['serviceID'], 'string', 'max'=>100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'appname' => Yii::t('backend', 'App Name'),
            'thumb' => Yii::t('backend', 'Thumbnail'),
            'hostname' => Yii::t('backend', 'Host Name'),
        ];
    }
}
