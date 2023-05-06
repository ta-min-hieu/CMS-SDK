<?php
/**
 * Created by PhpStorm.
 *
 * Date: 12/28/2016
 * Time: 11:54 PM
 */

namespace common\helpers;


use xj\uploadify\UploadAction;
use Yii;

class LargeUploadAction extends UploadAction
{
    public function run()
    {
        Yii::$app->session->timeout = 2 * 60 * 60;
        set_time_limit(2 * 60 * 60); // 2 hours
        return parent::run();
    }
}