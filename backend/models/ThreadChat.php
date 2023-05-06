<?php

namespace backend\models;
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
class ThreadChat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'thread_chat';
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'thread_id' => Yii::t('backend', 'Thread ID'),
            'timestamp' => Yii::t('backend', 'Timestamp'),
            'status' => Yii::t('backend', 'Status'),
            'queue_name' => Yii::t('backend', 'Queue Name'),
            'agent' => Yii::t('backend', 'Agent'),
            'ojid' => Yii::t('backend', 'OJid'),
            'created_at' => Yii::t('backend', 'Created At'),
            'default_queue' => Yii::t('backend', 'Default Queue'),
            'accepted_at' => Yii::t('backend', 'Accepted At'),
            'session_id' => Yii::t('backend', 'Session ID'),
            'with_value' => Yii::t('backend', 'With Value'),
            'time_wait' => Yii::t('backend', 'Time Wait'),
            'order_id' => Yii::t('backend', 'Order ID'),
            'channel' => Yii::t('backend', 'Channel'),
            'source' => Yii::t('backend', 'Source'),
            'ended_at' => Yii::t('backend', 'Ended At'),
        ];
    }
}
