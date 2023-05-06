<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
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
            [['username','server_host','password'], 'required'],
            [['username','server_host'], 'string', 'max' => 191],
            [['password'], 'string'],
            // [['serverkey','salt'], 'string', 'max' => 128],
            // [['iterationcount'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'User Name'),
        ];
    }
}
