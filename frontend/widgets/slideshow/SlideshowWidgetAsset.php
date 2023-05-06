<?php
/*
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 *
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/themes/theme01/css/layout.css',
        '/themes/theme01/css/widget.css',

        '/themes/theme01/css/main.css',

    ];
    public $js = [
        '/themes/theme01/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js',
        '/themes/theme01/js/jquery.bxslider.min.js',
        '/js/bootstrap-toastr/toastr.min.js',
        '/themes/theme01/js/main.js?v=1.3',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
*/

namespace frontend\widgets\slideshow;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class SlideshowWidgetAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/core/swiper/css/swiper.min.css',
        '/core/swiper/css/myswiper.css',
    ];
    public $js = [
        '/core/swiper/js/swiper.min.js',
        '/core/swiper/js/slideshow.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
