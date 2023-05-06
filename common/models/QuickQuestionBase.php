<?php

namespace common\models;

use Yii;

class QuickQuestionBase extends \common\models\db\QuickQuestionDB {

    public function __toString()
    {
        return $this->type_box_chat;
    }

    public function attributeLabels()
    {
        return [
            'id_question' => Yii::t('backend', 'ID'),
            'type_question' => Yii::t('backend', 'Type Question'),
            'question' => Yii::t('backend', 'Question'),
            'answer' => Yii::t('backend', 'Answer'),
            'id_box_chat' => Yii::t('backend', 'Mission'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}