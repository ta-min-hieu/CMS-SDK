<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user_login_failed".
 *
 * @property string $id
 * @property string $username
 * @property string $user_id
 * @property string $ip
 * @property string $created_at
 */
class UserLoginFailedDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_login_failed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['user_id', 'created_at'], 'integer'],
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
            'user_id' => Yii::t('backend', 'User ID'),
            'ip' => Yii::t('backend', 'Ip'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}
