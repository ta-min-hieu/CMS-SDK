<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/10/2016
 * Time: 4:11 PM
 */

namespace awesome\backend\toast;

use Yii;
use yii\bootstrap\Widget;

/**
 * 1. Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->session->setFlash('error', 'This is the message');
 * \Yii::$app->session->setFlash('success', 'This is the message');
 * \Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * Used in template
 *   AwsAlertToast::widget([
 *   ]);
 *
 * 2. Customize content
 *   AwsAlertToast::widget([
 *      'getFlash' => false,
 *      'title' => 'abc',
 *      'message' => 'xyz'
 *   ]);
 *
 *
 *
 */
class AwsAlertToast extends Widget
{
    const TYPE_INFO = 'info';
    const TYPE_DANGER = 'error';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_PRIMARY = 'info';
    const TYPE_DEFAULT = 'info';
    const TYPE_CUSTOM = 'info';

    const POS_TOP_RIGHT = 'toast-top-right';
    const POS_BOT_RIGHT = 'toast-bottom-right';
    const POS_BOT_LEFT = 'toast-bottom-left';
    const POS_TOP_LEFT = 'toast-top-left';
    const POS_TOP_CENTER = 'toast-top-center';
    const POS_BOT_CENTER = 'toast-bottom-center';
    const POS_TOP_FWIDTH = 'toast-top-full-width';
    const POS_BOT_FWIDTH = 'toast-bottom-full-width';

    public $alertTypes = [
        'error' => self::TYPE_DANGER,
        'danger' => self::TYPE_DANGER,
        'success' => self::TYPE_SUCCESS,
        'info' => self::TYPE_INFO,
        'warning' => self::TYPE_WARNING
    ];

    /**
     * @var string Toast Type. One of the `TYPE_` constants.
     * Defaults to `TYPE_INFO`
     */
    public $type = self::TYPE_INFO; // shortCutFunction

    /**
     * @var string Toast Position. One of the `POS_` constants.
     */
    public $position = self::POS_TOP_RIGHT; // positionClass

    /**
     * @var string Show Easing function name
     */
    public $showEasing = 'swing';

    /**
     * @var string Hide Easing function name
     */
    public $hideEasing = 'linear';

    /**
     * @var string Show Method function name
     */
    public $showMethod = 'fadeIn';

    /**
     * @var string Hide Method function name
     */
    public $hideMethod = 'fadeOut';

    /**
     * @var int Show Duration
     */
    public $showDuration = 1000;

    /**
     * @var int Hide Duration
     */
    public $hideDuration = 1000;

    /**
     * @var int Time out
     */
    public $timeOut = 5000;

    /**
     * @var int Extended time out
     */
    public $extendedTimeOut = 1000;

    /**
     * @var bool Display Close Button
     */
    public $closeButton = true;

    /**
     * @var bool get from message flash session Yii::$app->session->setFlash('error', 'This is the message');
     */
    public $getFlash = true;

    /**
     * @var string title of message
     */
    public $title = null;

    /**
     * @var string body of message
     */
    public $message = null;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->registerAssets();
        $view = $this->getView();
        if ($this->getFlash) {
            // get from session
            $session = Yii::$app->session;
            $flashes = $session->getAllFlashes();
            $js = '$(function() {
                        toastr.options.positionClass = \'' . $this->position . '\';
                        toastr.options.showEasing = \'' . $this->showEasing . '\';
                        toastr.options.hideEasing = \'' . $this->hideEasing . '\';
                        toastr.options.showMethod = \'' . $this->showMethod . '\';
                        toastr.options.hideMethod = \'' . $this->hideMethod . '\';
                        toastr.options.showDuration = ' . $this->showDuration . ';
                        toastr.options.hideDuration = ' . $this->hideDuration . ';                            
                        toastr.options.timeOut = ' . $this->timeOut . ';                            
                        toastr.options.extendedTimeOut = ' . $this->extendedTimeOut . ';                            
                        toastr.options.closeButton = ' . $this->closeButton . ';                            
                    });';
            $view->registerJs($js);
            foreach ($flashes as $type => $data) {
                if (isset($this->alertTypes[$type])) {
                    $data = (array)$data;
                    foreach ($data as $i => $message) {
                        $js = '$(function() {
                            toastr[\'' . $this->alertTypes[$type] . '\'](\'' . $message . '\');                          
                        });';
                        $view->registerJs($js);
                    }
                    $session->removeFlash($type);
                }
            }
        } else {
            $js = '$(function() {
                        toastr.options.positionClass = \'' . $this->position . '\';
                        toastr.options.showEasing = \'' . $this->showEasing . '\';
                        toastr.options.hideEasing = \'' . $this->hideEasing . '\';
                        toastr.options.showMethod = \'' . $this->showMethod . '\';
                        toastr.options.hideMethod = \'' . $this->hideMethod . '\';
                        toastr.options.showDuration = ' . $this->showDuration . ';
                        toastr.options.hideDuration = ' . $this->hideDuration . ';                            
                        toastr.options.timeOut = ' . $this->timeOut . ';                            
                        toastr.options.extendedTimeOut = ' . $this->extendedTimeOut . ';                            
                        toastr.options.closeButton = ' . $this->closeButton . ';       
                        toastr[\'' . $this->type . '\'](\'' . $this->message . '\');
                    });';
            $view->registerJs($js);
        }
    }

    /**
     * Register client assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        AwsAlertToastAsset::register($view);
    }
}