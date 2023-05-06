<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/scss/theme.css',
        '/css/style.css',
        '/js/simplebar/dist/simplebar.min.css',
    ];
    public $js = [
        // '/js/jquery-3.3.1.min.js',
        // '/js/popper.min.js',
        '/js/bootstrap/dist/js/bootstrap.min.js',
        '/js/smooth-scroll/dist/smooth-scroll.polyfills.min.js',
        '/js/simplebar/dist/simplebar.min.js',
        // '/js/bootstrap-toastr/toastr.min.js',
        '/js/theme.min.js',
    ];
    public $sourcePath = '@frontend/assets';
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
