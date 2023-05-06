<?php
/**
 * Created by PhpStorm.
 *
 * Date: 11/22/2016
 * Time: 1:43 PM
 */

namespace awesome\backend\grid;

use mdm\admin\models\searchs\User;
use Yii;
use yii\i18n\Formatter;

class AwsFormatter extends Formatter
{
    public function asBool($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        return $value ? "<span class=\"glyphicon glyphicon-ok\"> </span>" : "<span class=\"glyphicon glyphicon-remove\"> </span>";
    }

    public function asVnDatetime($value)
    {
        return $value;
    }

    public function asBlameable($value)
    {
        $user = User::findOne(["id" => $value]);
        if ($user) {
            return $user->username;
        }
        return "Unknown";
    }

    public function asImageStorage($value)
    {
        if ($value) {
            $decode = json_decode($value, true);
            $url = $decode['domain'] . '/' . $decode['bucket'] . $decode['path'];
            return "<img width='80' src='$url' />";
        }
        return "";
    }

    public function asJsonName($value)
    {
        if ($value) {
            $decode = json_decode($value, true);
            if (count($decode)) {
                $res = [];
                foreach ($decode as $obj) {
                    $res[] = $obj['name'];
                }
                return implode(", ", $res);
            } else if ($decode) {
                return $decode['name'];
            }
        }
        return "";
    }

    public function asJsonNameType($value)
    {
        if ($value) {
            $decode = json_decode($value, true);
            if (count($decode)) {
                $res = [];
                foreach ($decode as $obj) {
                    $text = $obj['name'];
                    switch ($obj['type']) {
                        case TYPE_ATTRIBUTE_CATEGORY:
                            $text = Yii::t('backend', 'Category') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_FILM_SERIES:
                            $text = Yii::t('backend', 'Film Series') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_TV_SHOW:
                            $text = Yii::t('backend', 'TV Show') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_TV_SHOW_SERIES:
                            $text = Yii::t('backend', 'TV Show Series') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_FILM:
                            $text = Yii::t('backend', 'Film') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_SITCOM:
                            $text = Yii::t('backend', 'Sitcom') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_COMPOSER:
                            $text = Yii::t('backend', 'Composer') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_SINGER:
                            $text = Yii::t('backend', 'Singer') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_ACTOR:
                            $text = Yii::t('backend', 'Actor') . ":" . $text;
                            break;
                        case TYPE_ATTRIBUTE_DIRECTOR:
                            $text = Yii::t('backend', 'Director') . ":" . $text;
                            break;
                    }
                    $res[] = $text;
                }
                return implode("; ", $res);
            } else if ($decode) {
                return $decode['name'];
            }
        }
        return "";
    }

    public function asMediaDuration($value)
    {
        if ($value) {
            return gmdate("H:i:s", $value);
        }
        return "";
    }
}