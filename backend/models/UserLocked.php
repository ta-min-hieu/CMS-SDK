<?php

namespace backend\models;

use Yii;

class UserLocked extends \common\models\UserLockedBase {
    public static function isUsernameLocked($username, $timeStart) {
        return UserLocked::find()
            ->where(['username' => $username])
            ->andWhere('created_at >= :created_at', ['created_at' => $timeStart])
            ->count();
    }

    public static function isIpLocked($ip, $timeStart) {
        return UserLocked::find()
            ->where(['ip' => $ip])
            ->andWhere('created_at >= :created_at', ['created_at' => $timeStart])
            ->count();
    }

    public static function lockUsername($username, $createdAt = null)
    {
        if (!$createdAt) $createdAt = time();
        $loginFailed = null;
        $loginFailed = UserLocked::find()->where(['username' => $username])->one();
        if (!$loginFailed) {
            $loginFailed = new UserLocked;
            $loginFailed->username = $username;
        }
        $loginFailed->created_at = $createdAt;
        $loginFailed->save();
    }

    public static function lockIp($ip, $createdAt = null)
    {
        if (!$createdAt) $createdAt = time();
        $loginFailed = null;
        $loginFailed = UserLocked::find()->where(['ip' => $ip])->one();
        if (!$loginFailed) {
            $loginFailed = new UserLocked;
            $loginFailed->ip = $ip;
        }
        $loginFailed->created_at = $createdAt;
        $loginFailed->save();
    }

    public static function unlockUsername($username) {
        UserLocked::deleteAll(['username' => $username]);
    }

    public static function unlockIp($ip) {
        UserLocked::deleteAll(['ip' => $ip]);
    }
}