<?php

namespace backend\models;

use Yii;

class UserLoginFailed extends \common\models\UserLoginFailedBase
{
    public static function countByUsername($username, $timeStart)
    {
        return UserLoginFailed::find()
            ->where(['username' => $username])
            ->andWhere('created_at >= :created_at', ['created_at' => $timeStart])
            ->count();
    }

    public static function countByIp($ip, $timeStart)
    {
        return UserLoginFailed::find()
            ->where(['ip' => $ip])
            ->andWhere('created_at >= :created_at', ['created_at' => $timeStart])
            ->count();
    }

    public static function log($username, $userId, $ip, $createdAt = null)
    {
        if (!$createdAt) $createdAt = time();
        $loginFailed = new UserLoginFailed;
        $loginFailed->username = $username;
        $loginFailed->user_id = $userId;
        $loginFailed->ip = $ip;
        $loginFailed->created_at = $createdAt;
        $loginFailed->save();
    }

    public static function clearUsername($username)
    {
        UserLoginFailed::deleteAll(['username' => $username]);
    }

    public static function clearIp($ip)
    {
        UserLoginFailed::deleteAll(['ip' => $ip]);
    }
}