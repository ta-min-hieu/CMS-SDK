<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user_block".
 *
 * @property integer $id
 * @property string $username
 * @property integer $status
 * @property string $reason
 * @property string $blocked_at
 * @property string $unblocked_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class UserBlockDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['reason'], 'string'],
            [['blocked_at', 'unblocked_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['username'], 'string', 'max' => 15],
            [['status'], 'string', 'max' => 1]
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
            'status' => Yii::t('backend', 'Status'),
            'reason' => Yii::t('backend', 'Reason'),
            'blocked_at' => Yii::t('backend', 'Blocked At'),
            'unblocked_at' => Yii::t('backend', 'Unblocked At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }
}
