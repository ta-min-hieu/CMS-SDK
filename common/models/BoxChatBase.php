<?php

namespace common\models;

use Yii;

class BoxChatBase extends \common\models\db\BoxChatDB {

    public function __toString()
    {
        return $this->type_box_chat;
    }

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