<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/18/2016
 * Time: 11:19 AM
 */

namespace common\helpers;


class NetworkHelper
{
    public static function getRemoteIp($name) {
//        $ip = '--unknow--';
//        if (isset($_SERVER['REMOTE_ADDR'])
//                && $_SERVER['REMOTE_ADDR'] != "10.58.50.125"
//                && $_SERVER['REMOTE_ADDR'] != "127.0.0.1") {
//            $ip = $_SERVER['REMOTE_ADDR'];
//        Yii::info('recognized IP: REMOTE_ADDR: '.$ip);
//        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//            Yii::info('recognized IP: HTTP_X_FORWARDED_FOR: '.$ip);
//        }
//        return $ip;
        return $_SERVER[$name];
    }
}