<?php
/**
 * Created by PhpStorm.
 *
 * Date: 09-Jul-16
 * Time: 11:02
 */

namespace awesome\backend\datetimePicker;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * DatePicker renders a `datepicker` jQuery UI widget.
 *
 * For example to use the datepicker with a [[yii\base\Model|model]]:
 *
 * ```php
 * echo DatePicker::widget([
 *     'model' => $model,
 *     'attribute' => 'from_date',
 *     //'language' => 'ru',
 *     //'dateFormat' => 'yyyy-MM-dd',
 * ]);
 * ```
 *
 * @see http://www.malot.fr/bootstrap-datetimepicker/
 *
 * @since 2.0
 */
class AwsDatetimePicker extends InputWidget
{
    /**
     * @var string The two-letter code of the language to use for month and day names.
     * These will also be used as the input's value (and subsequently sent to the server in the case of form submissions).
     * Currently ships with English ('en'), German ('de'), Brazilian ('br'), and Spanish ('es') translations,
     * but others can be added (see I18N below). If an unknown language code is given, English will be used.
     *
     * Default: 'en'
     */
    public $language;

    public $jsOptions;

    public $value;

    public $dateFormat;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->language) {
            $this->language = "vn";
        }
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo $this->renderWidget() . "\n";

        $containerID = $this->options['id'];
        $language = $this->language;

        $view = $this->getView();
        if ($language != 'en') {
            $view->registerJsFile("plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.$language.js");
        }
        $options = Json::encode($this->jsOptions);
        $view->registerJs("$('#{$containerID}').datetimepicker({$options});");
        AwsDatetimeAsset::register($this->getView());
    }

    /**
     * Renders the DatePicker widget.
     * @return string the rendering result.
     */
    protected function renderWidget()
    {
        $contents = [];

        // get formatted date value
        if ($this->hasModel()) {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $value = $this->value;
        }
        if ($value !== null && $value !== '') {
            // format value according to dateFormat
            try {
                $value = Yii::$app->formatter->asDate($value, $this->dateFormat);
            } catch (InvalidParamException $e) {
                // ignore exception and keep original value if it is not a valid date
            }
        }
        $options = $this->options;
        $options['value'] = $value;

        // render a text input
        if ($this->hasModel()) {
            $contents[] = Html::activeTextInput($this->model, $this->attribute, $options);
        } else {
            $contents[] = Html::textInput($this->name, $value, $options);
        }
        //$contents[] = '<i class="fa fa-calendar"></i>';

        return implode("\n", $contents);
    }
}