<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class SubscriberAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        
    ];
    public $js = [
        '/theme01/js/subscriber.js?v=1.0',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
