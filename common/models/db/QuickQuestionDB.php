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
class QuickQuestionDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quick_question';
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
            [['type_question','question','answer','id_box_chat'], 'required'],
            [['id_question'], 'integer'],
            [['type_question'], 'integer', 'max' => 5],
            [['updated_at', 'created_at'], 'safe'],
            [['question'], 'string', 'max' => 1000],
            [['answer'], 'string', 'max' => 4000],
            [['id_box_chat'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_question' => Yii::t('backend', 'ID'),
            'type_question' => Yii::t('backend', 'Type Question'),
            'question' => Yii::t('backend', 'Question'),
            'answer' => Yii::t('backend', 'Answer'),
            'id_box_chat' => Yii::t('backend', 'Id Box Chat'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}
