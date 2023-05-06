<?php

namespace common\helpers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use backend\components\common\TelesaleSoapCaller;
use backend\models\TelesaleAccount;
use backend\models\TelesaleCallLog;
use backend\models\TelesaleHobby;
use backend\models\UserOaFeedback;
use common\libs\RemoveSign;
use common\libs\Search;
use Exception;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\db\Exception as Exception2;
use yii\helpers\HtmlPurifier;
use yii\imagine\Image;
use yii\web\UploadedFile;

class Helpers
{

    private static $hasSign = array(
        "à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ",
        "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
        "ì", "í", "ị", "ỉ", "ĩ",
        "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",
        "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
        "ỳ", "ý", "ỵ", "ỷ", "ỹ",
        "đ",
        "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
        "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
        "Ì", "Í", "Ị", "Ỉ", "Ĩ",
        "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
        "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
        "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
        "Đ",
    );
    private static $noSign = array(
        "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
        "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
        "i", "i", "i", "i", "i",
        "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
        "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
        "y", "y", "y", "y", "y",
        "d",
        "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
        "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
        "i", "i", "i", "i", "i",
        "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
        "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
        "y", "y", "y", "y", "y",
        "d");
    private static $noSignOnly = array(
        "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
        "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
        "i", "i", "i", "i", "i",
        "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
        "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
        "y", "y", "y", "y", "y",
        "d",
        "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
        "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
        "I", "I", "I", "I", "I",
        "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
        "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
        "Y", "Y", "Y", "Y", "Y",
        "D");

    public static function generateStructurePath()
    {
        $uniqueFileName = uniqid();
        $mash = 255;
        $hashCode = crc32($uniqueFileName);
        $firstDir = $hashCode & $mash;
        $firstDir = vsprintf("%02x", $firstDir);
        $secondDir = ($hashCode >> 8) & $mash;
        $secondDir = vsprintf("%02x", $secondDir);
        $thirdDir = ($hashCode >> 4) & $mash;
        $thirdDir = vsprintf("%02x", $thirdDir);
        return $firstDir . "/" . $secondDir . "/" . $thirdDir . '/' . uniqid();
    }

    public static function removeJstag($str)
    {

        $stripArr = array(
            'script', 'onblur', 'onchange', 'alert', 'onclick', 'ondblclick', 'onfocus', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onkeydown', 'onkeypress', 'onkeyup', 'onselect', 'object', 'embed'
        );
        foreach ($stripArr as $tag) {
            $str = str_replace($tag, '', $str);
            $tag = strtoupper($tag);
            $str = str_replace($tag, '', $str);
        }
        return $str;
    }

    public static function getOnlyMediaImage($path)
    {
        return Yii::$app->params['media_path'] . $path;
    }

    public static function safeInput($input)
    {
        if (!is_array($input)) {
            $p = new HtmlPurifier();
            if (preg_match('/^[0-9]*$/', $input)) {
                return intval($input);
            } else {
                return $p->process($input);
            }
        } else {
            foreach ($input as &$item) {
                $item = Helpers::safeInput($item);
            }
        }
        return $input;
    }

    /**
     *
     * @param $tonename
     * @param $tonesingername
     */
    public static function getListRBT($tonename, $tonesingername, $limit)
    {
        try {
            $result = array();
            $queryName = strtolower(RemoveSign::removeSign($tonename . " " . $tonesingername));
            $lenName = strtolower(RemoveSign::removeSignAndSpace($tonename . $tonesingername));

            $listRbt1 = Search::searchDismax($queryName . ' ' . $lenName, $limit); //tim chuoi dua len + chuoi remove space

            $array = array();
            if ($listRbt1['full_items']) {//neu co ket qua
                foreach ($listRbt1['full_items'] as $rbt1) {
                    array_push($array, array(
                        'huawei_tone_id' => $rbt1->huawei_tone_id,
                        'huawei_tone_code' => $rbt1->huawei_tone_code,
                        'huawei_tone_name' => $rbt1->huawei_tone_name,
                        'huawei_singer_name' => $rbt1->huawei_singer_name,
                        'huawei_order_times' => $rbt1->huawei_order_times,
                        'huawei_price' => $rbt1->huawei_price,
                        'score' => $rbt1->score,
                        'vt_link' => $rbt1->vt_link,
                    ));
                }
            }
            usort($array, function ($a, $b) {
                return $b['score'] - $a['score'];
            });
            $return = array_slice($array, 0, $limit);

            $result['errorCode'] = 0;
            //sap xep lai theo luot tai
            usort($return, function ($a, $b) {
                return $b['huawei_order_times'] - $a['huawei_order_times'];
            });
            //check xem trong 3 ket qua co cai nao phu hop

            return $return;
        } catch (Exception $e) {
            return false;
        }
    }

    /*
     *
     */

    public static function textCompare($t1, $t2)
    {
        similar_text($t1, $t2, $per);
        return $per;
    }


    /**
     * Kiem tra moi gia tri mang $childArray co thuoc mang $parentArray ko?
     * @param $childArray
     * @param $parentArray
     * @return bool
     */
    public static function checkChildArray($childArray, $parentArray)
    {
        foreach ($childArray as $child) {
            if (!in_array($child, $parentArray)) {
                return false;
            }
        }
        return true;
    }

    public static function moneyFormat($money, $delimiter = '.')
    {
        $return = '';
        $len = strlen($money);
        while ($len > 3) {
            if ($return == '') {
                $return = substr($money, $len - 3);
            } else {
                $return = substr($money, $len - 3) . $delimiter . $return;
            }
            $money = substr($money, 0, $len - 3);
            $len = strlen($money);
        }
        return $money . $delimiter . $return;
    }

    public static function getFirstWordSingerByAlias($alias)
    {
        $alias = trim($alias);
        $alias = trim($alias, '"');
        $alias = trim($alias, "'");
        if ($alias) {
            $first = substr(static::vi2en($alias), 0, 1);
            if (preg_match('/[A-Za-z]/', $first)) {
                return strtoupper($first);
            }
        }
        return '#';
    }

    public static function vi2en($str)
    {
        return str_replace(RemoveSign::$hasSign, RemoveSign::$noSignOnly, $str);
    }

    public static function getArrayColumn($array, $column_name)
    {
        if (!function_exists("array_column")) {
            return array_map(function ($element) use ($column_name) {
                return $element[$column_name];
            }, $array);
        }
        return array_column($array, $column_name);
    }

    public static function getPriceInfoSub($subId, $price)
    {
        $name = 'đ';
        switch ($subId) {
            case SUB_DAILY:
                $name = 'đ/ngày';
                break;
            case SUB_WEEKLY:
                $name = 'đ/tuần';
                break;
            case SUB_MONTHLY:
                $name = 'đ/tháng';
                break;
        }
        return Helpers::moneyFormat($price) . $name;
    }

    public static function imagePath($path, $type = "album")
    {
        try {
            if (strlen($path) == 0) {
                return Yii::$app->params[$type . '_default_media_path'];
            } else {
                $filename = Yii::$app->params['upload_path'] . $path;
                if (\is_file($filename)) {
                    return Yii::$app->params['media_path'] . $path;
                } else {
                    return Yii::$app->params[$type . '_default_media_path'];
                }
            }
        } catch (Exception2 $e) {
            return Yii::$app->params[$type . '_default_media_path'];
        }
    }

    /**
     * Upload file
     * @return mixed the uploaded image instance
     */
    public function uploadFile($fileName, $fileNameType, $filePath, $fileField)
    {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $file = UploadedFile::getInstance($this, $fileField);

        // if no file was uploaded abort the upload
        if (empty($file)) {
            return false;
        } else {
            // set fileName by fileNameType
            switch ($fileNameType) {
                case "original":
                    // get original file name
                    $name = $file->name;
                    break;
                case "casual":
                    // generate a unique file name
                    $name = Yii::$app->security->generateRandomString();
                    break;
                default:
                    // get item title like filename
                    $name = $fileName;
                    break;
            }
            // file extension
            $fileExt = end(explode(".", $file->name));
            // purge filename
            $fileName = $this->generateFileName($name);
            // set field to filename.extensions
            $this->$fileField = $fileName . ".{$fileExt}";
            // update file->name
            $file->name = $fileName . ".{$fileExt}";
            // save images to imagePath
            $file->saveAs($filePath . $fileName . ".{$fileExt}");

            // the uploaded file instance
            return $file;
        }
    }

    /**
     * createThumbImages files
     * @return mixed the uploaded image instance
     */
    public function createThumbImages($image, $imagePath, $imgOptions, $thumbPath)
    {
        $imageName = $image->name;
        $imageLink = $imagePath . $image->name;

        // Save Image Thumbs
        Image::thumbnail($imageLink, $imgOptions['small']['width'], $imgOptions['small']['height'])
            ->save($thumbPath . "small/" . $imageName, ['quality' => $imgOptions['small']['quality']]);
        Image::thumbnail($imageLink, $imgOptions['medium']['width'], $imgOptions['medium']['height'])
            ->save($thumbPath . "medium/" . $imageName, ['quality' => $imgOptions['medium']['quality']]);
        Image::thumbnail($imageLink, $imgOptions['large']['width'], $imgOptions['large']['height'])
            ->save($thumbPath . "large/" . $imageName, ['quality' => $imgOptions['large']['quality']]);
        Image::thumbnail($imageLink, $imgOptions['extra']['width'], $imgOptions['extra']['height'])
            ->save($thumbPath . "extra/" . $imageName, ['quality' => $imgOptions['extra']['quality']]);
    }

    /**
     * Generate fileName
     * @return string fileName
     */
    public function generateFileName($name)
    {
        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $name);
        // lowercase and trim
        $str = trim(strtolower($str));

        return $str;
    }

    public static function AntiSQLInjection($cautruyvan)
    {
        $cautruyvan = strtolower($cautruyvan);
        $tukhoa = array('update', 'insert', 'drop', 'create');

        $kiemtra = str_replace($tukhoa, '*', $cautruyvan);
        if ($cautruyvan != $kiemtra) {
            Yii::warning("SQL Injection: " . $cautruyvan);
            die("Không hiểu truy vấn!!!");
        }
    }

    /**
     * Tao anh thumb
     * @param $imagePath
     * @param null $width
     * @param null $height
     * @param bool $crop
     * @return bool|string
     */
    public static function getThumb($imagePath, $width = null, $height = null, $crop = false)
    {
        try {
            $imgInfo = pathinfo($imagePath);

            $thumbPath = 'thumbs/' . $imgInfo['dirname'];
            $savedPath = $thumbPath . '/' . $width . '_' . $height . '-' . $imgInfo['basename'];

            if (!file_exists(Yii::getAlias('@webroot/' . $savedPath))) {

                // Tao folder rieng cho anh thumbs
                \yii\helpers\BaseFileHelper::createDirectory(Yii::getAlias('@webroot/' . $thumbPath));
                Image::thumbnail('@webroot' . $imagePath, $width, $height)
                    ->save(Yii::getAlias('@webroot/' . $savedPath), ['quality' => 100]);
            }
            return '/' . $savedPath;
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    public static function replace_unicode_characters($str)
    {
        $return = str_replace(self::$hasSign, self::$noSign, $str);
        if (!$return) {
            return Yii::$app->security->generateRandomString();
        } else
            return $return;
    }

    public static function does_file_exist($file_path)
    {
        $upload_path = Yii::$app->basePath . DIRECTORY_SEPARATOR;
        return is_file($upload_path . $file_path);
    }

    public static function file_full_path($file_path)
    {
        if ($file_path == null || empty($file_path)) {
            return '';
        }

        return $file_path;
    }

    /**
     * Tra ve mang cac nam
     * @param $from : Ford Model T
     * @since Nov-15-2017
     * @author sj
     * */
    public function generate_prev_years($from = 1915)
    {
        for ($year = date('Y'); $year >= $from; $year--) {
            $years[$year] = $year;
        }

        return $years;
    }

    /**
     * Lay ds hobby cua user hien tai
     * Note: ham nay da duoc chuyen sang class User
     *
     * @return array
     */
//  public static function getUserHobbies() {
//
//    if (Yii::$app->user->identity->isAdmin()) {
//      // Admin
//
//      return TelesaleHobby::find()->orderBy(['name' => SORT_ASC])->all();
//
//    } else if (Yii::$app->user->identity->isPartnerAdmin()) {
//      // Partner Admin
//      $hobbyConcat = TelesaleAccount::find()
//          ->select(["GROUP_CONCAT(hobbies SEPARATOR ', ') as hobbies"])
//          ->where([
//              'partner_id' => Yii::$app->user->identity->getPartnerId(),
//          ])
//
//          ->asArray()
//          ->one();
//      $hobbies = $hobbyConcat['hobbies'];
//      return TelesaleHobby::find()->where(['in', 'id', explode(',', $hobbies)])->all();
//
//    } elseif (Yii::$app->user->identity->isNormalTelesaleUser()) {
//      // User thuong
//      $ta = Yii::$app->user->identity->getTelesaleAccount();
//      return TelesaleHobby::find()
//          ->where(['in', 'id', explode(',', $ta->hobbies)])
//          ->orderBy(['name' => SORT_ASC])
//          ->all();
//
//    } else {
//
//      return [];
//    }
//  }

    public static function canCallToSubAtTime($sub_id, $time)
    {
        $called_log = TelesaleCallLog::find()
            ->where([
                'sub_id' => $sub_id,
                'return_code' => TelesaleSoapCaller::SUCCESS,
            ])->orderBy(['created_at' => SORT_DESC])->one();
        $allowed_days = Yii::$app->params['call_frequency'];

        if ($called_log) {
            $called_time = date_create($called_log->created_at);
            $diff = date_diff($called_time, $time);
            return intval($diff->format('%a')) >= $allowed_days;
        } else {
            return true;
        }
    }

    public static function formatCallTime($callTime)
    {
        return number_format(floor($callTime / 60));
    }

    public static function telesaleBalanceToMinute($money)
    {
        $basePrice = Yii::$app->params['telesale_call_base_price'];
        $time = intval($money / $basePrice);
        return number_format($time);
    }

    /**
     * Xu ly dinh dang ngay cua daterange picker ve dinh dang datetime cua mysql
     * dung trong tim kiem
     * @param $date
     * @param $display_format
     * @return array
     */
    public static function splitDate($date, $display_format)
    {
        $dates = explode(' - ', $date);
        $dates[0] = date_create_from_format($display_format, $dates[0])->format('Y-m-d 00:00:00');
        $dates[1] = date_create_from_format($display_format, $dates[1])->format('Y-m-d 23:59:59');
        return $dates;
    }

    public static function splitDateNew($date, $display_format)
    {
        $dates = explode(' - ', $date);
        $dates[0] = date_create_from_format($display_format, $dates[0])->format('Y-m-d');
        $dates[1] = date_create_from_format($display_format, $dates[1])->format('Y-m-d');
        return $dates;
    }

    public static function minuteDisplay($seconds)
    {
        if ($seconds <= 0) {
            return $seconds;
        } elseif ($seconds < 60) {
            return $seconds . " " . Yii::t('backend', 'second');
        } else {
            $m = floor($seconds / 60) . " " . Yii::t('backend', 'minute');
            $s = $seconds % 60;
            $s = $s > 0 ? $s . " " . Yii::t('backend', 'second') : '';
            return $m . ' ' . $s;
        }
    }

    /**
     * Format sdt hien thi cho dep
     * @param $phonenumber
     * @return string
     */
    public static function formatTelephone($phonenumber)
    {
        $cleaned = preg_replace('/[^[:digit:]]/', '', $phonenumber);
        preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
        return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
    }

    public static function convertMsisdn($msisdn, $type = '9x')
    {
        $msisdn = trim($msisdn);
        if (preg_match(Yii::$app->params['phonenumber_pattern'], $msisdn, $matches)) {
            switch ($type) {
                case '9x':
                    return isset($matches[2]) ? $matches[2] : $msisdn;
                    break;
                case '84x':
                    return isset($matches[2]) ? Yii::$app->params['country_code'] . $matches[2] : $msisdn;
                    break;
                case '+84x':
                    return isset($matches[2]) ? '+' . Yii::$app->params['country_code'] . $matches[2] : $msisdn;
                    break;
                case '09x':
                    return isset($matches[2]) ? '0' . $matches[2] : $msisdn;
                    break;
            }
        }
        return $msisdn;
    }

    public static function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }


    public static function timeago($time, $tense = 'ago')
    {
        if (empty($time))
            return '';
        // declaring periods as static function var for future use
        static $periods = array('year', 'month', 'day', 'hour', 'minute', 'second');

        // checking time format
        if (!(strtotime($time) > 0)) {
            return trigger_error("Wrong time format: '$time'", E_USER_ERROR);
        }

        // getting diff between now and time
        $now = new \DateTime('now');
        $time = new \DateTime($time);
        $diff = $now->diff($time)->format('%y %m %d %h %i %s');
        // combining diff with periods
        $diff = explode(' ', $diff);
        $diff = array_combine($periods, $diff);
        // filtering zero periods from diff
        $diff = array_filter($diff);
        // getting first period and value
        $period = key($diff);
        $value = current($diff);

        // if input time was equal now, value will be 0, so checking it
        if (!$value) {
            $period = 'seconds';
            $value = 0;
        } else {
            // converting days to weeks
            if ($period == 'day' && $value >= 7) {
                $period = 'week';
                $value = floor($value / 7);
            }
            // adding 's' to period for human readability
            if ($value > 1) {
                $period .= 's';
            }
        }

        // returning timeago
        $period = Yii::t('backend', $period);
        $tense = Yii::t('backend', $tense);
        return "$value $period $tense";
    }

    /**
     * push message vao queue rabbit mq
     * @param $officialAccountId
     * @param $username
     * @param $message
     */
    public static function pushRabbitQueue($officialAccountId, $username, $message, $msgType = 'text')
    {
        // Day vao queue rabit
        $mqConfig = Yii::$app->params['rabbitmq_config'];
        $connection = new AMQPConnection($mqConfig['host'], $mqConfig['port'], $mqConfig['user'], $mqConfig['password']);
        $channel = $connection->channel();

        $channel->queue_declare($mqConfig['feedback_queue_name'], false, false, false, false);

        $xmlData = UserOaFeedback::buildMessage($officialAccountId, Helpers::convertMsisdn($username, '+84x'), $message, $msgType);

        $msg = new AMQPMessage($xmlData, array('delivery_mode' => 2));
        $channel->basic_publish($msg, '', $mqConfig['feedback_queue_name']);
    }

    public static function array_change_key_case_recursive($arr, $case = CASE_LOWER)
    {
        return array_map(function ($item) use ($case) {
            if (is_array($item))
                $item = self::array_change_key_case_recursive($item, $case);
            return $item;
        }, array_change_key_case($arr, $case));
    }

    /**
     * Ghi log cms
     * @param $function
     * @param $action
     * @param $beforeItem - object
     * @param $afterItem - object
     */
    public static function writeCmsActionLog($function, $action, $beforeItem, $afterItem, $itemName = '')
    {

        try {
            $cmsActionLog = new \backend\models\CmsActionLog();
            $cmsActionLog->ACTION_DATE = date('Y-m-d H:i:s');
            $cmsActionLog->ACTION_FUNC = $function;
            $cmsActionLog->ACTION_NAME = $action;
            $cmsActionLog->ACTOR = Yii::$app->user->identity->USERNAME;
            $cmsActionLog->CONTENT_BEFORE = (is_object($beforeItem) && $beforeItem) ? json_encode($beforeItem->toArray()) :
                ((is_array($beforeItem)) ? json_encode($beforeItem) : null);
            $cmsActionLog->CONTENT_AFTER = (is_object($afterItem) && $afterItem) ? json_encode($afterItem->toArray()) :
                ((is_array($beforeItem)) ? json_encode($beforeItem) : null);
            $cmsActionLog->ITEM_NAME = ($itemName) ? $itemName : ((is_object($beforeItem) && $beforeItem) ? StringHelper::truncate($beforeItem->__toString(), 240) : null);
            $cmsActionLog->save(false);

            // goi api cache

            if ($action !== 'update_order') {
                if ($action == 'delete') {
                    $itemId = (is_object($beforeItem) && $beforeItem) ? $beforeItem->getId() : null;
                    $content = (is_object($beforeItem) && $beforeItem) ? json_encode($beforeItem->toArray()) : null;
                } else {
                    $itemId = (is_object($afterItem) && $afterItem) ? $afterItem->getId() : null;
                    $content = (is_object($afterItem) && $afterItem) ? json_encode($afterItem->toArray()) : null;
                }
                // Yii::info("CONTENT: " . json_encode($afterItem->toArray()));
                SuperAppApiGw::updateCache($function, $action, $itemId, $content);
            }


        } catch (\yii\base\Exception $e) {
            //var_dump($e->getMessage());die;
            Yii::error($e);
        }
    }

    /**
     * Xu ly tra ve media url cho cac du an superapp :(
     */
    public static function getMediaUrl($absMediaPath)
    {
        if (!$absMediaPath) {
            return '#';
        } elseif (strpos($absMediaPath, 'http') === 0) {
            return $absMediaPath;
        } else {
            return Yii::$app->params['domain_file'] . $absMediaPath;
        }
    }

    /**
     * Dung cho viec xuat PDF
     * @param $absMediaPath
     * @return string
     */
    public static function getLocalMediaUrl($absMediaPath)
    {
        if (!$absMediaPath) {
            return '#';
        } elseif (strpos($absMediaPath, 'http') === 0) {
            return $absMediaPath;
        } else {
            return Yii::$app->params['local_media_url'] . $absMediaPath;
        }
    }

    public static function getThumbUrl($absImagePath, $w = 200, $h = 200)
    {

        if ($absImagePath) {
            $pathInfo = pathinfo($absImagePath);


            $cacheDir = Yii::getAlias('@webroot') . '/cache/' . $pathInfo['dirname'];
            $absThumbPath = '/cache' . $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "-$w-" . ($h ? $h : 'auto') . '.' . $pathInfo['extension'];
            $fullThumbPath = Yii::getAlias('@webroot') . '/' . $absThumbPath;
            if (file_exists($fullThumbPath)) {
                return $absThumbPath;
            } elseif (file_exists(Yii::getAlias('@webroot') . '/' . $absImagePath)) {
                FileHelper::createDirectory($cacheDir, $mode = 0775, $recursive = true);

                try {

                    Image::thumbnail(Yii::getAlias('@webroot') . '/' . $absImagePath, $w, $h)
                        ->save($fullThumbPath, ['quality' => 90]);

                    return $absThumbPath;

                } catch (\Exception $e) {
                    Yii::error('Loi khi tao anh thumb' . $e);
                    return '';
                }
            } else {
                return '';
            }

        }


        return $absImagePath;
    }

    public static function commonStatusArr($category = 'backend')
    {
        return [

            '0' => Yii::t($category, 'Draft'),
            '1' => Yii::t($category, 'Approved'),
            '-1' => Yii::t($category, 'Delete'),

        ];
    }

    public static function commonUserStatusArr($category = 'backend')
    {
        return [
            '-2' => Yii::t($category, 'Disapproved'),
            '0' => Yii::t($category, 'Draft'),
            '1' => Yii::t($category, 'Approved'),
            '-1' => Yii::t($category, 'Delete'),
            '10' => Yii::t($category, 'User mới upload'),
            '11' => Yii::t($category, 'Hệ thống đang xử lý'),
            '12' => Yii::t($category, 'Hệ thống đã xử lý xong'),

        ];
    }
    public static function commonStaffStatusArr($category = 'backend')
    {
        return [
            '1' => Yii::t($category, 'Active'),
            '0' => Yii::t($category, 'Not active yet'),
        ];
    }
    public static function commonQuickQuestionStatusArr($category = 'backend')
    {
        return [
            '1' => Yii::t($category, 'Questions with answers'),
            '2' => Yii::t($category, 'Questions has no answer'),
        ];
    }
    public static function commonQueueStatusArr($category = 'backend')
    {
        return [
            'Bưu Cục' => Yii::t($category, 'Bưu Cục'),
            'CSKH' => Yii::t($category, 'CSKH'),
            'Miss Queue' => Yii::t($category, 'Miss Queue'),
        ];
    }

    public static function commonAdminStatusArr($category = 'backend')
    {
        return [
            '-2' => Yii::t($category, 'Disapproved'),
            '0' => Yii::t($category, 'Draft'),
            '1' => Yii::t($category, 'Approved'),
            '-1' => Yii::t($category, 'Delete'),

        ];
    }

    public static function commonBelongArr($category = 'backend')
    {
        return [
            'cms' => Yii::t($category, 'Cms'),
            'user' => Yii::t($category, 'User'),
            

        ];
    }

    public static function toIsoDate($timestamp){
       return new \MongoDB\BSON\UTCDateTime($timestamp * 1000);
    }
}
