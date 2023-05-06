<?php

namespace backend\models;

class ReportThreadChat2QueueSearch extends \yii\base\Model
{
    public $queue_name;
    public $type;
    public $date;
    function formName()
    {
        return '';
    }
}
