<?php
/**
 * Created by PhpStorm.
 *
 * Date: 28-Apr-16
 * Time: 11:20
 */

namespace awesome\backend\form;


use yii\web\AssetBundle;

class AwsActiveFormAsset extends AssetBundle
{
    public $sourcePath = '@yii/assets';
    public $js = [
        'yii.activeForm.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}