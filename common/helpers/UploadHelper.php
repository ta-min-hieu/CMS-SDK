<?php
/**
 * Created by PhpStorm.
 *
 * Date: 12/13/2016
 * Time: 4:40 PM
 */

namespace common\helpers;


use Yii;

class UploadHelper
{
    public static function getFilePathToSave($filePath, $type) {
        if (is_file($filePath)) {
            $config = Yii::$app->params['upload'][$type]['basePath'];
            $file = explode($config, $filePath);
            return $config . $file[1];
        }
        return '';
    }

    public static function getBasePathUpload($type) {
        return Yii::$app->params['upload']['basePath'] . Yii::$app->params['upload'][$type]['basePath'];
    }
}