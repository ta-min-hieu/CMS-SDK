<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/11/2016
 * Time: 6:00 PM
 */

namespace common\components\assets;


use common\helpers\Helpers;
use common\helpers\FileHelper;

class AssetBundle extends \yii\web\AssetBundle
{
    const EMPTY_ASSET = 'N0/@$$3T$';
    const EMPTY_PATH = 'N0/P@T#';
    const AWESOME_ASSET = 'AWS/@$$3T$';
    const AWESOME_PATH = 'AWS/P@T#';

    public $js = self::AWESOME_ASSET;
    public $css = self::AWESOME_ASSET;
    public $sourcePath = self::AWESOME_PATH;
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->js === self::AWESOME_ASSET) {
            $this->js = [];
        }
        if ($this->css === self::AWESOME_ASSET) {
            $this->css = [];
        }
        if ($this->sourcePath === self::AWESOME_PATH) {
            $this->sourcePath = null;
        }
    }

    /**
     * Adds a language JS locale file
     *
     * @param string $lang the ISO language code
     * @param string $prefix the language locale file name prefix
     * @param string $dir the language file directory relative to source path
     * @param bool $min whether to auto use minified version
     *
     * @return AssetBundle instance
     */
    public function addLanguage($lang = '', $prefix = '', $dir = null, $min = false)
    {
        if (empty($lang) || substr($lang, 0, 2) == 'en') {
            return $this;
        }
        $ext = $min ? (YII_DEBUG ? ".min.js" : ".js") : ".js";
        $file = "{$prefix}{$lang}{$ext}";
        if ($dir === null) {
            $dir = 'js';
        } elseif ($dir === "/") {
            $dir = '';
        }
        $path = $this->sourcePath . '/' . $dir;
        if (!FileHelper::fileExists("{$path}/{$file}")) {
            $lang = CommonHelper::getLang($lang);
            $file = "{$prefix}{$lang}{$ext}";
        }
        if (FileHelper::fileExists("{$path}/{$file}")) {
            $this->js[] = empty($dir) ? $file : "{$dir}/{$file}";
        }
        return $this;
    }

    /**
     * Set up CSS and JS asset arrays based on the base-file names
     *
     * @param string $type whether 'css' or 'js'
     * @param array $files the list of 'css' or 'js' basefile names
     */
    protected function setupAssets($type, $files = [])
    {
        if ($this->$type === self::AWESOME_ASSET) {
            $srcFiles = [];
            $minFiles = [];
            foreach ($files as $file) {
                $srcFiles[] = "{$file}.{$type}";
                $minFiles[] = "{$file}.min.{$type}";
            }
            $this->$type = YII_DEBUG ? $srcFiles : $minFiles;
        } elseif ($this->$type === self::EMPTY_ASSET) {
            $this->$type = [];
        }
    }

    /**
     * Sets the source path if empty
     *
     * @param string $path the path to be set
     */
    protected function setSourcePath($path)
    {
        if ($this->sourcePath === self::AWESOME_PATH) {
            $this->sourcePath = $path;
        } elseif ($this->sourcePath === self::EMPTY_PATH) {
            $this->sourcePath = null;
        }
    }
}