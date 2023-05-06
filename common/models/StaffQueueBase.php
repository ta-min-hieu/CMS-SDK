<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class StaffQueueBase extends \common\models\db\StaffQueueDB
{


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'value' => new Expression('NOW()'),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    // public function afterDelete()
    // {
    //     parent::afterDelete();
    //     $albums = $this->queues;
    //     foreach ($albums as $album) {
    //         $album->recalculateStaffs();
    //     }
    //     return true;
    // }

    // public function afterSave($insert, $changedAttributes)
    // {
    //     parent::afterSave($insert, $changedAttributes);

    //     if($insert) {
    //         $albums = $this->queues;
    //         foreach ($albums as $album) {
    //             $album->recalculateStaffs();
    //         }
    //     }
    // }

    public function getQueues() {
        return $this->hasMany(QueueBase::className(), ['id' => 'id']);
    }
}