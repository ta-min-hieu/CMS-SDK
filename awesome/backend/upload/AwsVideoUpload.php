<?php
/**
 * Created by PhpStorm.
 *
 * Date: 12/15/2016
 * Time: 5:09 PM
 */

namespace awesome\backend\upload;


use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\InputWidget;

class AwsVideoUpload extends InputWidget
{
    /**
     * upload file to URL
     * @var string
     * @example
     * http://xxxxx/upload.php
     * ['article/upload']
     * ['upload']
     */
    public $url;

    /**
     * @var bool
     */
    public $csrf = true;

    /**
     * 是否渲染Tag
     * @var bool
     */
    public $renderTag = true;

    /**
     * uploadify js options
     * @var array
     * @example
     * [
     * 'height' => 30,
     * 'width' => 120,
     * 'swf' => '/uploadify/uploadify.swf',
     * 'uploader' => '/uploadify/uploadify.php',
     * ]
     * @see http://www.uploadify.com/documentation/
     */
    public $jsOptions = [];

    /**
     * @var int
     */
    public $registerJsPos = View::POS_LOAD;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        //init var
        if (empty($this->url)) {
            throw new InvalidConfigException('Url must be set');
        }
        if (empty($this->id)) {
            $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
        $this->options['id'] = $this->id;
        if (empty($this->name)) {
            $this->name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->id;
        }

        //register Assets
        $assets = UploadifyAsset::register($this->view);

        $this->initOptions($assets);
        $this->initCsrfOption();

        parent::init();
    }
}