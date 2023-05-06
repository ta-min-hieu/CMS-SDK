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
class HomepageAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';


    public $js = [
        'https://www.amcharts.com/lib/3/amcharts.js',
        'https://www.amcharts.com/lib/3/serial.js',
        'https://www.amcharts.com/lib/3/plugins/export/export.min.js',
        'https://www.amcharts.com/lib/3/themes/light.js',
        '/js/admin.home.js'

    ];
    public $css = [
        'https://www.amcharts.com/lib/3/plugins/export/export.css',

    ];
    public $depends = [
        'backend\assets\AppAsset'
    ];

}
