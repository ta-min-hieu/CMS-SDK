<?php

namespace common\models\db;

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
class BoxChatDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'box_chat';
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
            [['type_box_chat','description','title_question','greeting'], 'required'],
            [['id_box_chat','type_box_chat'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['title_question','greeting'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_box_chat' => Yii::t('backend', 'ID'),
            'type_box_chat' => Yii::t('backend', 'Mission'),
            'description' => Yii::t('backend', 'Description'),
            'title_question' => Yii::t('backend', 'Title Question'),
            'greeting' => Yii::t('backend', 'Greeting'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}
