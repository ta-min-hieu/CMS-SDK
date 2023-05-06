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
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account'], 'integer'],
            [['action', 'name_object','id_object'], 'safe'],
            [['created_at'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_object' => Yii::t('backend', 'Object ID'),
            'id' => Yii::t('backend', 'ID'),
            'name_object' => Yii::t('backend', 'Object Name'),
            'account' => Yii::t('backend', 'Account ID'),
            'account_name' => Yii::t('backend', 'Account Name'),
            'id_user' => Yii::t('backend', 'Account Name'),
        ];
    }
    public static function saveDBLog($account, $id_object, $action, $name_object){
        $model = new Log();
        $model->account = $account;
        $model->id_object = $id_object;
        $model->action = $action;
        $model->name_object = $name_object;
        $model->save();
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'account']);
    }
}
