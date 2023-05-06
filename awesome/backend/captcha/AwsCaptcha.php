<?php
/**
 * Created by PhpStorm.
 *
 * Date: 3/9/2017
 * Time: 4:29 PM
 */

namespace awesome\backend\captcha;

use awesome\backend\widgets\AwsBaseHtml;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 *
 * @property array $clientOptions
 */
class AwsCaptcha extends InputWidget
{
    /**
     * @var string|array the route of the action that generates the CAPTCHA images.
     * The action represented by this route must be an action of [[CaptchaAction]].
     * Please refer to [[\yii\helpers\Url::toRoute()]] for acceptable formats.
     */
    public $captchaAction = 'site/captcha';
    /**
     * @var array HTML attributes to be applied to the CAPTCHA image tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $imageOptions = [];
    /**
     * @var string the template for arranging the CAPTCHA image tag and the text input tag.
     * In this template, the token `{image}` will be replaced with the actual image tag,
     * while `{input}` will be replaced with the text input tag.
     */
    public $template = '{image} {input}';
    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'form-control'];


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        static::checkRequirements();

        if (!isset($this->imageOptions['id'])) {
            $this->imageOptions['id'] = $this->options['id'] . '-image';
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            $input = AwsBaseHtml::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $input = AwsBaseHtml::textInput($this->name, $this->value, $this->options);
        }
        $route = $this->captchaAction;
        if (is_array($route)) {
            $route['v'] = uniqid();
        } else {
            $route = [$route, 'v' => uniqid()];
        }
        $image = AwsBaseHtml::img($route, $this->imageOptions);
        echo strtr($this->template, [
            '{input}' => $input,
            '{image}' => $image,
        ]);
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $options = $this->getClientOptions();
        $options = empty($options) ? '' : Json::htmlEncode($options);
        $id = $this->imageOptions['id'];
        $view = $this->getView();
        AwsCaptchaAsset::register($view);
        $view->registerJs("jQuery('#$id').yiiCaptcha($options);");
    }

    /**
     * Returns the options for the captcha JS widget.
     * @return array the options
     */
    protected function getClientOptions()
    {
        $route = $this->captchaAction;
        if (is_array($route)) {
            $route[AwsCaptchaAction::REFRESH_GET_VAR] = 1;
        } else {
            $route = [$route, AwsCaptchaAction::REFRESH_GET_VAR => 1];
        }

        $options = [
            'refreshUrl' => Url::toRoute($route),
            'hashKey' => 'yiiCaptcha/' . trim($route[0], '/'),
        ];

        return $options;
    }

    /**
     * Checks if there is graphic extension available to generate CAPTCHA images.
     * This method will check the existence of ImageMagick and GD extensions.
     * @return string the name of the graphic extension, either "imagick" or "gd".
     * @throws InvalidConfigException if neither ImageMagick nor GD is installed.
     */
    public static function checkRequirements()
    {
        if (extension_loaded('imagick')) {
            $imagickFormats = (new \Imagick())->queryFormats('PNG');
            if (in_array('PNG', $imagickFormats, true)) {
                return 'imagick';
            }
        }
        if (extension_loaded('gd')) {
            $gdInfo = gd_info();
            if (!empty($gdInfo['FreeType Support'])) {
                return 'gd';
            }
        }
        throw new InvalidConfigException('Either GD PHP extension with FreeType support or ImageMagick PHP extension with PNG support is required.');
    }
}