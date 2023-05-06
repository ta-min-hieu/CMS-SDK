<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/24/2015
 * Time: 11:05 AM
 */

namespace common\helpers;


use Imagine\Image\Box;
use Imagine\Imagick\Imagine;
use yii\imagine\Image;
use wap\components\WapExtension;
use Yii;
use yii\base\Exception;
use yii\helpers\BaseHtmlPurifier;

class MusicHelper
{
    public static function getSingerNameJson($strSingerName)
    {
        if (!$strSingerName) {
            return "V.A";
        }
        if ($strSingerName) {
            $objSingerName = json_decode($strSingerName);
            $arrName = array();
            foreach ($objSingerName as $item) {
                $arrName[] = $item->alias;
            }
            if ($arrName)
                return implode($arrName, ', ');
        }
        return "V.A";
    }

    public static function getImagePathBySong($strImagePath)
    {
        $objImagePath = json_decode($strImagePath);
        $imagePath = '';
        if (!$objImagePath) {
            return \Yii::$app->params['album_default_media_path'];
        }
        foreach ($objImagePath as $item) {
            if (property_exists($item, 'image_path')) {
                $imagePath = $item->image_path;
            } else {
                $imagePath = '';
            }
            if ($imagePath) break;
        }

        if ($imagePath) {
            try {
                if (strlen($imagePath) == 0) {
                    return \Yii::$app->params['album_default_media_path'];
                } else {
                    $filename = \Yii::$app->params['upload_path'] . $imagePath;
                    if (is_file($filename)) {
                        return \Yii::$app->params['media_path'] . $imagePath;
                    } else {
                        return \Yii::$app->params['album_default_media_path'];
                    }
                }
            } catch (Exception $e) {
                return \Yii::$app->params['album_default_media_path'];
            }
        }
        return \Yii::$app->params['album_default_media_path'];
    }

    public static function getImageThumbPathBySong($strImagePath, $width, $height = 0)
    {
        $objImagePath = json_decode($strImagePath);
        $imagePath = '';
        if (!$objImagePath) {
            return WapExtension::generateDefaultThumb($width, $height);
        }
        foreach ($objImagePath as $item) {
            if (property_exists($item, 'image_path')) {
                $imagePath = $item->image_path;
            } else {
                $imagePath = '';
            }
            if ($imagePath) break;
        }

        if ($imagePath) {
            $type = 'album';
            try {
                if (strlen($imagePath) == 0) {
                    return WapExtension::generateDefaultThumb($width, $height, $type);
                } else {
                    $thumbnail = Yii::$app->params['image_thumb_path'] . DIRECTORY_SEPARATOR . $width . $imagePath;
                    if (is_file($thumbnail)) {
                        return Yii::$app->params['media_thumb_path'] . DIRECTORY_SEPARATOR . $width . $imagePath;
                    } else {
                        $filename = Yii::$app->params['upload_path'] . $imagePath;
                        if (is_file($filename)) {
//                            $imagine = Image::getImagine();
//                            $imagine = new Imagine();
                            $image = new \Imagick($filename);
//                            $image = $imagine->open($filename);
                            if ($height == 0) {
                                $size = $image->getSize();
//                                $height = round($width * $size->getHeight() / $size->getWidth());
                                $height = round($width * $size['rows'] / $size['columns']);
                            }
                            //create folder
                            $pathToFile = $width . $imagePath;
                            $fileName = basename($pathToFile);
                            $folders = explode(DIRECTORY_SEPARATOR, str_replace(DIRECTORY_SEPARATOR . $fileName, '', $pathToFile));
                            $currentFolder = Yii::$app->params['image_thumb_path'] . DIRECTORY_SEPARATOR;
                            foreach ($folders as $folder) {
                                $currentFolder .= $folder . DIRECTORY_SEPARATOR;
                                if (!file_exists($currentFolder)) {
                                    mkdir($currentFolder, 0775);
                                }
                            }
//                            $image->resize(new Box($width, $height))->save($thumbnail, ['quality' => 75]);
                            $image->scaleImage($width, $height);
                            $image->writeimage($thumbnail);
                            if (is_file($thumbnail)) {
                                return Yii::$app->params['media_thumb_path'] . DIRECTORY_SEPARATOR . $width . $imagePath;
                            }
                            return Yii::$app->params['media_path'] . $imagePath;
                        }
                    }
                }
            } catch (Exception $e) {
            }
        }
        return WapExtension::generateDefaultThumb($width, $height);
    }

    public static function getImageBlurPathBySong($strImagePath)
    {
        $objImagePath = json_decode($strImagePath);
        $imagePath = '';
        if (!$objImagePath) {
            return \Yii::$app->params['default_bg_blur_song'];
        }
        foreach ($objImagePath as $item) {
            if (property_exists($item, 'image_blur_path')) {
                $imagePath = $item->image_blur_path;
            } else {
                $imagePath = '';
            }
            if ($imagePath) break;
        }

        if ($imagePath) {
            try {
                if (strlen($imagePath) == 0) {
                    return \Yii::$app->params['default_bg_blur_song'];
                } else {
                    $filename = \Yii::$app->params['upload_path'] . $imagePath;
                    if (is_file($filename)) {
                        return \Yii::$app->params['media_path'] . $imagePath;
                    } else {
                        return \Yii::$app->params['default_bg_blur_song'];
                    }
                }
            } catch (Exception $e) {
                return \Yii::$app->params['default_bg_blur_song'];
            }
        }
        return \Yii::$app->params['default_bg_blur_song'];
    }

    public static function getImageBlurPathByVideo($strImagePath)
    {
        $imagePath = $strImagePath;
        if ($imagePath) {
            try {
                if (strlen($imagePath) == 0) {
                    return \Yii::$app->params['default_bg_blur_video'];
                } else {
                    $filename = \Yii::$app->params['upload_path'] . $imagePath;
                    if (is_file($filename)) {
                        return \Yii::$app->params['media_path'] . $imagePath;
                    } else {
                        return \Yii::$app->params['default_bg_blur_video'];
                    }
                }
            } catch (Exception $e) {
                return \Yii::$app->params['default_bg_blur_video'];
            }
        }
        return \Yii::$app->params['default_bg_blur_video'];
    }

    /**
     * dung number_format cua PHP. Sua dau ngan phan nghin thanh ( . )
     * @param $value
     * @return int|string
     */
    public static function formatNumber($value)
    {
        if (!$value)
            return 0;
        return number_format($value, 0, ',', '.');
    }

    /**
     * @param $path
     * @param string $type
     * @return string
     */
    public static function imagePath($path, $type = "album")
    {
//        return \Yii::$app->params['media_path'] . $path;
        try {
            if (strlen($path) == 0) {
                return \Yii::$app->params[$type . '_default_media_path'];
            } else {
                $filename = \Yii::$app->params['upload_path'] . $path;
                if (is_file($filename)) {
                    return \Yii::$app->params['media_path'] . $path;
                } else {
                    return \Yii::$app->params[$type . '_default_media_path'];
                }
            }
        } catch (Exception $e) {
            return \Yii::$app->params[$type . '_default_media_path'];
        }
    }

    /**
     * @param $str
     */
    public static function purify($str)
    {
        return BaseHtmlPurifier::process($str);
    }
}