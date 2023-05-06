<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "welcome_page_number".
 *
 * @property int $id
 * @property int|null $welcome_page_id
 */
class PageNumberDB extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'welcome_page_number';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'welcome_page_id'], 'integer'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'welcome_page_id' => 'Welcome Page ID',
        ];
    }
}
