<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;

class UserBlockBase extends \common\models\db\UserBlockDB
{


    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(UserBase::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(UserBase::className(), ['id' => 'updated_by']);
    }
}