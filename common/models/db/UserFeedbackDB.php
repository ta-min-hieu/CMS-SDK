<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user_feedback".
 *
 * @property integer $id
 * @property string $msisdn
 * @property string $clientType
 * @property string $revision
 * @property string $version_app
 * @property string $content
 * @property string $images
 * @property string $device_os
 * @property string $device_name
 * @property string $created_at
 * @property integer $status_processing
 * @property string $error_type
 */
class UserFeedbackDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['images'], 'string'],
            [['created_at'], 'safe'],
            [['status_processing'], 'integer'],
            [['msisdn', 'clientType', 'revision'], 'string', 'max' => 30],
            [['version_app', 'error_type'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 1000],
            [['device_os'], 'string', 'max' => 100],
            [['device_name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'msisdn' => Yii::t('backend', 'Msisdn'),
            'clientType' => Yii::t('backend', 'Client Type'),
            'revision' => Yii::t('backend', 'Revision'),
            'version_app' => Yii::t('backend', 'Version App'),
            'content' => Yii::t('backend', 'Content'),
            'images' => Yii::t('backend', 'Images'),
            'device_os' => Yii::t('backend', 'Device Os'),
            'device_name' => Yii::t('backend', 'Device Name'),
            'created_at' => Yii::t('backend', 'Created At'),
            'status_processing' => Yii::t('backend', 'Status Processing'),
            'error_type' => Yii::t('backend', 'Error Type'),
        ];
    }
}
