<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\helpers\Helpers;

class CustomerBase extends \common\models\db\CustomerDB
{
    public function getAvatarUrl($w = null, $h = null) {
        if (!$w && !$h) {
            return Helpers::getMediaUrl($this->avatar);
        } else {
            return Helpers::getMediaUrl(Helpers::getThumbUrl($this->avatar, $w, $h));
        }

    }

}