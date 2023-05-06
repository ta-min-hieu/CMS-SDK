<?php

namespace common\models;
use common\helpers\Helpers;
use Yii;

class OABase extends \common\models\db\OADB {
    public function getAvatarUrl($w = null, $h = null) {
        if (!$w && !$h) {
            
            return Helpers::getMediaUrl($this->image);
        } else {
            return Helpers::getMediaUrl(Helpers::getThumbUrl($this->image, $w, $h));
        }

    }
}