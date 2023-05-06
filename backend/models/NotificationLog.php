<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property string|null $created_at
 * @property string|null $kind
 * @property string|null $type_user
 * @property int|null $total_uuid
 * @property int|null $total_uuid_android
 * @property int|null $total_uuid_ios
 */
class NotificationLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['total_uuid', 'total_uuid_android', 'total_uuid_ios'], 'integer'],
            [['kind', 'type_user'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('backend', 'Created At'),
            'kind' => Yii::t('backend', 'Kind'),
            'type_user' => Yii::t('backend', 'Type User'),
            'total_uuid' => Yii::t('backend', 'Total Uuid'),
            'total_uuid_android' => Yii::t('backend', 'Total Uuid Android'),
            'total_uuid_ios' => Yii::t('backend', 'Total Uuid Ios'),
        ];
    }
}
