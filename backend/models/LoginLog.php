<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "login".
 *
 * @property string|null $created_at
 * @property string|null $kind
 * @property int|null $total
 */
class LoginLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['total'], 'integer'],
            [['kind'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('backend', 'Created At'),
            'kind' => Yii::t('backend', 'Kind'),
            'total' => Yii::t('backend', 'Total'),
        ];
    }
}
