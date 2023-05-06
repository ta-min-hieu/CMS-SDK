<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\helpers\Helpers;

class StaffBase extends \common\models\db\StaffDB
{
    public function getAvatarUrl($w = null, $h = null) {
        if (!$w && !$h) {
            return Helpers::getMediaUrl($this->thumb);
        } else {
            return Helpers::getMediaUrl(Helpers::getThumbUrl($this->thumb, $w, $h));
        }

    }
    public function getStaffs() {
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn,(strpos($command->dsn, 'dbname=')) + 7);
        $query = (new \yii\db\Query())
        ->select(['staff.id_staff' ,'staff.username', 'staff.staff_name', 'staff.phone_number', 'staff.position', 'staff.id_department', 'staff.status', 'queue.thumb'])
        ->from($dbn.'.staff')
        ->innerJoin($dbn.'.queue_agent', 'staff.username = queue_agent.agent_name')
        ->innerJoin($dbn.'.queue','queue_agent.queue_name = queue.queue_name')
        ->where(['queue.id' => $this->id])
        ->all();
        return $query;
    }
}