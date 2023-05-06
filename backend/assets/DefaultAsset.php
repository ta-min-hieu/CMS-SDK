<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DefaultAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/font-opensans/css/font-opensans.css',
//        'plugins/font-awesome/css/font-awesome.min.css',
//        'plugins/simple-line-icons/simple-line-icons.min.css',
//        'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css',
        'plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'css/components-md.min.css',
        'css/plugins-md.min.css',
        'css/lock.css?v1.1',
        'css/custom.css?v1.4444'
    ];
    public $js = [
        'https://code.jquery.com/jquery-3.2.1.slim.min.js',
//        'https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js',
//        'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js',
//        'https://code.jquery.com/jquery-migrate-3.3.2.js',
        'plugins/bootstrap/js/bootstrap.min.js',
        'js/metronic/app.js',
        'js/admin.js?v1.1',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
