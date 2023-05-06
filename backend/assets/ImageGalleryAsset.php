<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ImageGalleryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

        //'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.1.8/css/lightgallery.min.css',
        //'https://use.fontawesome.com/releases/v5.3.1/css/all.css',
        '/bootstrap-icons/font/bootstrap-icons.css',
    ];
    public $js = [
        //'https://use.fontawesome.com/releases/v5.3.1/js/all.js',
        //'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.1.8/lightgallery.min.js',
        '/js/admin.btsimage.js?v=1.43',
    ];
    public $depends = [
        'backend\assets\AppAsset',

    ];
}
