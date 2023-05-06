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
class ListExcelDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'list_excel';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['excel'], 'file','skipOnEmpty' => true, 'maxSize' => 104857600, 'tooBig' => Yii::t('backend', 'File is too big, maximun is 100Mb') ],
            [['action'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'excel' => Yii::t('backend', 'List Staff (File Excel)'),
        ];
    }
}
