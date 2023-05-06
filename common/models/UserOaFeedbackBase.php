<?php

namespace common\models;

use Yii;

class UserOaFeedbackBase extends \common\models\db\UserOaFeedbackDB
{


    public function rules()
    {
        return [
            [['id', 'time_receive'], 'safe'],
            [['message'], 'string'],
            [['is_receive', 'is_read'], 'integer'],
            [['official_account_id', 'username'], 'string', 'max' => 50],
            [['sub_type'], 'string', 'max' => 30]
        ];
    }
}