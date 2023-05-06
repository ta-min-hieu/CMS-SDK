<?php

namespace backend\models;

use Yii;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
class Queue extends \common\models\QueueBase {
    // public function getUserProvince(){
    //     $id_user = \Yii::$app->user->id;
    //     $iDProvince = User::findOne($id_user)->id_province;
    //     return $iDProvince;
    //     }
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id_department' => 'id_department']);
    }
}