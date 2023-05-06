<?php

namespace frontend\models;

use Yii;

class StaticPage extends \common\models\StaticPageBase {

    public function getImageUrl() {
        return $this->image_path;
    }
}