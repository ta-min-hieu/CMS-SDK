<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "msc_song_album".
 *
 * @property integer $id
 * @property integer $song_id
 * @property integer $album_id
 * @property string $created_at
 */
class StaffQueueDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue_agent';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsdk');
    }
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['queue_name', 'agent_name'], 'string', 'max' => 45],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'queue_name' => Yii::t('backend', 'Queue name'),
            'agent_name' => Yii::t('backend', 'Agent name'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}
