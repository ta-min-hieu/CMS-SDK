<?php

namespace backend\models;

use Yii;
use common\models\QuickQuestionBase;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

class QuickQuestion extends QuickQuestionBase {
    public function rules()
    {
        $rules = [
            
            [['type_question','question','id_box_chat'], 'required'],
            [['id_question'], 'integer'],
            [['type_question'], 'integer', 'max' => 5],
            [['updated_at', 'created_at'], 'safe'],
            [['question'], 'string', 'max' => 1000],
            [['answer'], 'string', 'max' => 4000],
            [['id_box_chat'], 'string', 'max' => 100],
            ['answer','required','when'=>function($model) {
                return $model->type_question == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#quickquestion-type_question').val() == '1';
            }"],
        ];
        return $rules;
    }
}
