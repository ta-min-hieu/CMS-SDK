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

class MediumUploadAction extends UploadAction
{
    public function run()
    {
        Yii::$app->session->timeout = 30 * 60;
        set_time_limit(30 * 60); // 30 minutes
        return parent::run();
    }
}