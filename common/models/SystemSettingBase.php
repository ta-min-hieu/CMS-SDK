<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

class SystemSettingBase extends \common\models\db\SystemSettingDB {

    public static function getAllConfig() {
        $cache = Yii::$app->cache;
        $key = 'system_setting_all';
        return Yii::$app->cache->getOrSet($key, function() {
            $settings =  self::find()
                ->select('config_key, config_value')
                ->asArray()
                ->all();

            if (!empty($settings)) {
                return ArrayHelper::map($settings, 'config_key', 'config_value');
            }

            return array();
        });
    }

    public static function getConfigByKey($configKey) {

        if ($configKey == null) {
            return null;
        } else {
            $allConfig = self::getAllConfig();
            return (isset($allConfig[$configKey]))? $allConfig[$configKey]: null;
        }
    }
}