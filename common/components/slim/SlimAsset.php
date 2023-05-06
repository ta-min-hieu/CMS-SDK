<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\slim;

use yii\web\AssetBundle;
use yii\web\View;
use backend\assets\AppAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SlimAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//    public $jsOptions = [
//        'position' => View::POS_END,
//    ];
    public $css = [
        '/slim/slimv4.css'
    ];
    public $js = [
        '/slim/require_2.2.0.js',
        '/slim/slimv4.js',
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
        'backend\assets\AppAsset'
    ];

}
