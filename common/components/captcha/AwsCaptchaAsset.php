<?php
/**
 * Created by PhpStorm.
 *
 * Date: 3/9/2017
 * Time: 4:41 PM
 */

namespace awesome\backend\captcha;


use awesome\backend\assets\AssetBundle;

class AwsCaptchaAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/jii.captcha']);
        parent::init();
    }
}