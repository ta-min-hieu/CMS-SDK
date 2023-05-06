<?php

namespace common\models;
use common\helpers\Helpers;
use Yii;

class OfficialAccountBase extends \common\models\db\OfficialAccountDB {
    public function getAvatarUrl($w = null, $h = null) {
        if (!$w && !$h) {
            
            return Helpers::getMediaUrl($this->thumb);
        } else {
            return Helpers::getMediaUrl(Helpers::getThumbUrl($this->thumb, $w, $h));
        }

    }
}