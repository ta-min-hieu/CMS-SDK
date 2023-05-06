<?php

namespace backend\models;

use Yii;

class Staff extends \common\models\StaffBase {
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['username' => 'username']);
    }
    public function getDispnamequeuestaff($id_staff){
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        $query1 = (new \yii\db\Query())
            ->select(['queue.id_department','department.department_name'])
            ->from($dbn . '.queue')
            ->innerJoin($dbn . '.queue_agent', 'queue.queue_name = queue_agent.queue_name')
            ->innerJoin($dbn . '.staff', 'staff.username = queue_agent.agent_name')
            ->innerJoin($dbn . '.department', 'queue.id_department = department.id_department')
            ->where(['staff.id_staff' => $id_staff])
            ->one();
        return $query1;
    }
}