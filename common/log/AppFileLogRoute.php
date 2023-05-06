<?php

namespace common\log;

use yii\log\FileTarget;

class AppFileLogRoute extends FileTarget {

    public $logFormat = '';

    protected function formatMessage($message, $level, $category, $time) {
        $logType = substr(ucfirst($level), 0, 1);

        $formatArray = array('time', 'level', 'category', 'message');
        $messageArray = array(date('Y-m-d H:i:s', $time), $logType, $category, $message);

        $messageAffterFormat = str_replace($formatArray, $messageArray, $this->logFormat);

        return $messageAffterFormat . "\n";
    }

}
