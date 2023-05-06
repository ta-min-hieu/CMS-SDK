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
class DigitalServiceAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        '/js/digital-service.js?v1.1'
    ];
    public $css = [
        '/css/digital-service/_form.css',
    ];
}
