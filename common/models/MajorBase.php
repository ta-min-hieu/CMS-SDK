<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class MajorBase extends \common\models\db\MajorDB
{

    public static function getDepartmentArray() {
        $itemArr = self::find()
            ->select('name')
            ->asArray()
            ->orderBy('name asc')
            ->all();

        if (!empty($itemArr)) {
            return  \yii\helpers\ArrayHelper::map($itemArr, 'id', 'name');

        } else {
            return array();
        }
    }


}