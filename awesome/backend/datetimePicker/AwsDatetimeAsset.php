<?php
/**
 * Created by PhpStorm.
 *
 * Date: 09-Jul-16
 * Time: 11:03
 */

namespace awesome\backend\datetimePicker;

use awesome\backend\assets\AssetBundle;

class AwsDatetimeAsset extends AssetBundle {

    /**
     * @inheritdoc
     */
    public function init()
    {
//        $this->depends = array_merge($this->depends, [
//            'yii\grid\GridViewAsset'
//        ]);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/bootstrap-datetimepicker']);
        $this->setupAssets('js', ['js/bootstrap-datetimepicker']);
        parent::init();
    }
}