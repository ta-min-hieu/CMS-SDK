<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/10/2016
 * Time: 6:36 PM
 */

namespace common\components\toast;

use awesome\backend\assets\AssetBundle;

class AwsAlertToastAsset extends AssetBundle {

    /**
     * @inheritdoc
     */
    public function init()
    {
//        $this->depends = array_merge($this->depends, [
//            'yii\grid\GridViewAsset'
//        ]);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/toastr']);
        $this->setupAssets('js', ['js/toastr']);
        parent::init();
    }
}