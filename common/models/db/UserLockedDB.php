<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user_locked".
 *
 * @property string $id
 * @property string $username
 * @property string $ip
 * @property string $created_at
 */
class UserLockedDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_locked';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['username'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 50]
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
            'ip' => Yii::t('backend', 'Ip'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}
