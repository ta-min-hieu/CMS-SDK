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
class OADB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','id_official_account','time'], 'required'],
            [['type','id_official_account'], 'string', 'max' => 45],
            [['text'], 'string', 'max' => 200],
            [['time','image'], 'string', 'max'=>500],
            // ['text','required','when'=>function($model) {
            //     return $model->type == 'text';
            // }],
            // [['video'], 'file', 'skipOnEmpty' => false, 'extensions' => 'mp4', 'maxSize' => 102400, 'tooBig' => Yii::t('backend', 'File is too big, maximun is 100Mb')],
            // [['video'], 'file', 'skipOnEmpty' => false, 'extensions' => 'mp4', 'maxSize' => 102400, 'tooBig' => Yii::t('backend', 'File is too big, maximun is 100Mb'), 'when'=>function($model) {
            //     return $model->type == 'video';
            // }],
            // ['image','required','when'=>function($model) {
            //     return $model->type == 'image';
            // }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'type' => Yii::t('backend', 'Type'),
            'id_official_account' => Yii::t('backend', 'Official Account'),
            'excel' => Yii::t('backend', 'Customer Group'),
            'time' => Yii::t('backend', 'Timer'),
            'text' => Yii::t('backend', 'Text'),
            'image' => Yii::t('backend', 'Image'),
            'video' => Yii::t('backend', 'Video'),
            'status' => Yii::t('backend', 'Status'),
        ];
    }
}
