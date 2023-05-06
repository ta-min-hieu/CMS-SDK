<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "vt_login_fail_times".
 *
 * @property string $id
 * @property string $phone_number
 * @property string $created_time
 */
class LoginFailTimesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vt_login_fail_times';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_number', 'created_time'], 'required'],
            [['created_time'], 'safe'],
            [['phone_number'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'phone_number' => Yii::t('backend', 'Phone Number'),
            'created_time' => Yii::t('backend', 'Created Time'),
        ];
    }
}
