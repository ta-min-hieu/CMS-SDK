<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/1/2016
 * Time: 5:54 PM
 */

namespace common\components\captcha;


use Yii;
use yii\base\InvalidConfigException;
use yii\captcha\CaptchaAction;

class BackendCaptcha extends CaptchaAction
{
    public $libfont = '@wap/web/css/fonts/vavobi.ttf,@wap/web/css/fonts/momtype.ttf';
    public $chars = 'abcdefhjkmnpqrstuxyz2345678';
    public $backColor = 0x333333; //#333
    public $height = 32;
    public $width = 100;

    /**
     * Initializes the action.
     * @throws InvalidConfigException if the font file does not exist.
     */
    public function init()
    {
        $num = rand(0, count($this->libfont) - 1);
        $this->fontFile = Yii::getAlias($this->libfont[$num]);

        if (!is_file($this->fontFile)) {
            $this->fontFile = '@wap/web/css/fonts/momtype.ttf';
        }
    }


    /**
     * Generates a new verification code.
     * @return string the generated verification code
     */
    protected function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        if ($this->minLength < 3) {
            $this->minLength = 3;
        }
        if ($this->maxLength > 20) {
            $this->maxLength = 20;
        }
        $length = mt_rand($this->minLength, $this->maxLength);
        $size = strlen($this->chars) - 1;
        $code = '';
        for ($i = 0; $i < $length; ++$i) {
            $code .= $this->chars[mt_rand(0, $size)];
        }
        return $code;
    }
}