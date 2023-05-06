<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "event_click".
 *
 * @property string|null $created_at
 * @property string|null $event_name
 * @property int|null $total_click
 * @property int|null $total_click_android
 * @property int|null $total_click_ios
 * @property int|null $total_uuid
 * @property float|null $total_sum
 * @property float|null $sum_android
 * @property float|null $sum_ios
 */
class EventClick extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_click';
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
            [['total_click', 'total_click_android', 'total_click_ios', 'total_uuid'], 'integer'],
            [['total_sum', 'sum_android', 'sum_ios'], 'number'],
            [['event_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('backend', 'Created At'),
            'event_name' => Yii::t('backend', 'Event Name'),
            'total_click' => Yii::t('backend', 'Total Click'),
            'total_click_android' => Yii::t('backend', 'Total Click Android'),
            'total_click_ios' => Yii::t('backend', 'Total Click Ios'),
            'total_uuid' => Yii::t('backend', 'Total Uuid'),
            'total_sum' => Yii::t('backend', 'Total Sum'),
            'sum_android' => Yii::t('backend', 'Sum Android'),
            'sum_ios' => Yii::t('backend', 'Sum Ios'),
        ];
    }
}
