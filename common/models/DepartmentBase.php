<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class DepartmentBase extends \common\models\db\DepartmentDB
{

    public static function getDepartmentArray() {
        $itemArr = self::find()
            ->select('department_name')
            ->asArray()
            ->orderBy('department_name asc')
            ->all();

        if (!empty($itemArr)) {
            return  \yii\helpers\ArrayHelper::map($itemArr, 'id_department', 'department_name');

        } else {
            return array();
        }
    }


}