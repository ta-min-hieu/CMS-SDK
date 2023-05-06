<?php

namespace backend\models;

use Yii;

class SystemSetting extends \common\models\SystemSettingBase {

    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->config_key == 'HO_PHONE_NUMBER') {
            $this->config_key = str_replace(' ', '', $this->config_key);
        }

        return parent::save($runValidation, $attributeNames);
    }
}