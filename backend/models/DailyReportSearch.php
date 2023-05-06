<?php

namespace backend\models;

class DailyReportSearch extends \yii\base\Model
{
    public $queue_name;
    public $type;
    public $date;
    function formName()
    {
        return '';
    }
}
