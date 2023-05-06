<?php

namespace frontend\models;

use common\helpers\Helpers;
use common\models\PlaylistBase;
use Yii;

class PlaylistForm extends PlaylistBase {
    public function rules() {
        return [
            ['playlist_name', 'required'],
            ['playlist_name', 'trim'],
            ['playlist_name', 'string', 'max' => 100],
        ];
    }
}