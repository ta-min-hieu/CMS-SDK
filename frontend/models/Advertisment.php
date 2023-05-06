<?php

namespace frontend\models;

use common\libs\RemoveSign;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\imagine\Image;

class Advertisment extends \common\models\AdvertismentBase {
    public function getImagePath($w = 600, $h = null) {
        if ($this->IMAGE_PATH) {
            $pathInfo = pathinfo($this->IMAGE_PATH);

            // Neu la anh gif thi tra ve luon 
            if ($pathInfo['extension'] == 'gif') {
                return $this->IMAGE_PATH;
            }

            $cacheDir = Yii::getAlias('@webroot'). '/cache/'. $pathInfo['dirname'];
            $absThumbPath = '/cache'. $pathInfo['dirname']. '/'. RemoveSign::name2slug($this->NAME). "-".($w? $w: 'auto')."-". ($h? $h: 'auto').'.'. $pathInfo['extension'];
            $fullThumbPath = Yii::getAlias('@webroot'). '/'. $absThumbPath;
            if (file_exists($fullThumbPath)) {
                return $absThumbPath;
            } elseif (file_exists(Yii::getAlias('@webroot'). '/'. $this->IMAGE_PATH)) {
                FileHelper::createDirectory($cacheDir , $mode = 0775, $recursive = true);

                try {

                    Image::thumbnail(Yii::getAlias('@webroot'). '/'. $this->IMAGE_PATH, $w, $h)
                        ->save($fullThumbPath, ['quality' => 100]);

                    return $absThumbPath;

                } catch (Exception $e) {
                    Yii::error('Loi khi tao anh thumb'. $e);
                    return '';
                }
            }

        }
        return $this->IMAGE_PATH;
    }
}