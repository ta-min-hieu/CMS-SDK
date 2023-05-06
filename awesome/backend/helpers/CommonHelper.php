<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/11/2016
 * Time: 6:05 PM
 */

namespace awesome\backend\helpers;


class CommonHelper
{
    /**
     * Convert a language string in yii\i18n format to a ISO-639 format (2 or 3 letter code).
     *
     * @param string $language the input language string
     *
     * @return string
     */
    public static function getLang($language)
    {
        $pos = strpos($language, "-");
        return $pos > 0 ? substr($language, 0, $pos) : $language;
    }
}