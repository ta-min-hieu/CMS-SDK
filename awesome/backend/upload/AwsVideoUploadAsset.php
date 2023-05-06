<?php
/**
 * Created by PhpStorm.
 *
 * Date: 12/15/2016
 * Time: 5:12 PM
 */

namespace awesome\backend\upload;

use awesome\backend\assets\AssetBundle;

class AwsVideoUploadAsset extends AssetBundle
{
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['blueimp-gallery/blueimp-gallery.min']);
        $this->setupAssets('css', ['css/jquery.fileupload']);
        $this->setupAssets('css', ['css/jquery.fileupload-ui']);
        $this->setupAssets('js', ['js/vendor/jquery.ui.widget']);
        $this->setupAssets('js', ['js/vendor/tmpl.min']);
        $this->setupAssets('js', ['js/vendor/load-image.min']);
        $this->setupAssets('js', ['js/vendor/canvas-to-blob.min']);
        $this->setupAssets('js', ['blueimp-gallery/jquery.blueimp-gallery.min']);
        $this->setupAssets('js', ['js/jquery.iframe-transport']);
        $this->setupAssets('js', ['js/jquery.fileupload	']);
        $this->setupAssets('js', ['js/jquery.fileupload-process']);
        $this->setupAssets('js', ['js/jquery.fileupload-image']);
        $this->setupAssets('js', ['js/jquery.fileupload-audio']);
        $this->setupAssets('js', ['js/jquery.fileupload-video']);
        $this->setupAssets('js', ['js/jquery.fileupload-validate']);
        $this->setupAssets('js', ['js/jquery.fileupload-ui']);
        parent::init();
    }
}