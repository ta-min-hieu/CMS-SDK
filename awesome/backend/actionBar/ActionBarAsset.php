<?php
namespace awesome\backend\actionBar;

use awesome\backend\assets\AssetBundle;

/**
 * ActionBarAsset represents a collection of asset files, such as CSS, JS, images.
 *
 * @author Oleg Belostotskiy <olgblst@gmail.com>
 */
class ActionBarAsset extends AssetBundle
{
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/actionbar']);
        parent::init();
    }
}
