<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_setting".
 *
 * @property integer $id
 * @property string $config_key
 * @property string $config_value
 * @property string $description
 */
class SystemSettingDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_value', 'description'], 'required'],
            [['config_value', 'description'], 'string'],
            [['config_key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'config_key' => Yii::t('backend', 'Config Key'),
            'config_value' => Yii::t('backend', 'Config Value'),
            'description' => Yii::t('backend', 'Description'),
        ];
    }
}
