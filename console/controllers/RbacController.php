<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;

class RbacController extends Controller {

    public function actionInit() {
        $user = new User();
        $user->username = "admin";
        $user->email = "admin@viettel.com.vn";
        $user->status = 1;
        $user->setPassword("123456a@");
        $user->generatePasswordResetToken();
        $user->generateAuthKey();
        $user->save(false);
    }

}
