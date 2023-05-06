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
class MajorDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mission';
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
            [['mission_name'], 'required'],
            [['mission_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_mission' => Yii::t('backend', 'ID'),
            'mission_name' => Yii::t('backend', 'Name'),

            'created_at' => Yii::t('backend', 'Created At'),

        ];
    }

}
