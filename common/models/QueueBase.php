<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\helpers\Helpers;

class QueueBase extends \common\models\db\QueueDB
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
    //     $rows = (new \yii\db\Query())
    // ->select('*')
    // ->from('staff')
    // ->limit(10)
    // ->all();
        // $out['results'] = array_values($query);
        
        return $query;
    }
    public static function getDepartmentArray() {
        $itemArr = self::find()
            ->select('queue_name')
            ->asArray()
            ->orderBy('queue_name asc')
            ->all();

        if (!empty($itemArr)) {
            return  \yii\helpers\ArrayHelper::map($itemArr, 'id', 'queue_name');

        } else {
            return array();
        }
    }

    public function updateWorkingTime($id_province, $type_queue, $start_time, $end_time){
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn,(strpos($command->dsn, 'dbname=')) + 7);
        $myUpdate = "UPDATE $dbn.queue join $dbn.department
        on (queue.id_department=department.id_department)
        SET queue.start_time='$start_time', queue.end_time='$end_time'
        WHERE queue.type_queue='$type_queue' and department.id_province='$id_province';";
         \Yii::$app->db->createCommand($myUpdate)->execute();
    }
}