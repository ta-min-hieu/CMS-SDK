<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class HomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'/css/gijgo.min.css',
    ];
    public $js = [
        //'/js/gijgo.min.js',
        // '/theme01/js/home.js?v=1.0',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
