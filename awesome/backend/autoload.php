<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/11/2016
 * Time: 10:05 AM
 */

define('AWS_PATH', __DIR__);

Yii::$classMap['awesome\backend\datetimePicker\AwsDatetimeAsset'] = AWS_PATH . '/datetimePicker/AwsDatetimeAsset.php';
Yii::$classMap['awesome\backend\datetimePicker\AwsDatetimePicker'] = AWS_PATH . '/datetimePicker/AwsDatetimePicker.php';
Yii::$classMap['awesome\backend\actionBar\ActionBar'] = AWS_PATH . '/actionBar/ActionBar.php';
Yii::$classMap['awesome\backend\actionBar\ActionBarAsset'] = AWS_PATH . '/actionBar/ActionBarAsset.php';
Yii::$classMap['awesome\backend\actionBar\DeleteMultipleAction'] = AWS_PATH . '/actionBar/DeleteMultipleAction.php';
Yii::$classMap['awesome\backend\captcha\AwsCaptcha'] = AWS_PATH . '/captcha/AwsCaptcha.php';
Yii::$classMap['awesome\backend\captcha\AwsCaptchaAsset'] = AWS_PATH . '/captcha/AwsCaptchaAsset.php';
Yii::$classMap['awesome\backend\captcha\AwsCaptchaAction'] = AWS_PATH . '/captcha/AwsCaptchaAction.php';
Yii::$classMap['awesome\backend\form\AwsActiveField'] = AWS_PATH . '/form/AwsActiveField.php';
Yii::$classMap['awesome\backend\form\AwsActiveForm'] = AWS_PATH . '/form/AwsActiveForm.php';
Yii::$classMap['awesome\backend\form\AwsActiveFormAsset'] = AWS_PATH . '/form/AwsActiveFormAsset.php';
Yii::$classMap['awesome\backend\grid\AwsBaseListView'] = AWS_PATH . '/grid/AwsBaseListView.php';
Yii::$classMap['awesome\backend\grid\AwsColumn'] = AWS_PATH . '/grid/AwsColumn.php';
Yii::$classMap['awesome\backend\grid\AwsDataColumn'] = AWS_PATH . '/grid/AwsDataColumn.php';
Yii::$classMap['awesome\backend\grid\AwsFormatter'] = AWS_PATH . '/grid/AwsFormatter.php';
Yii::$classMap['awesome\backend\grid\AwsGridView'] = AWS_PATH . '/grid/AwsGridView.php';
Yii::$classMap['awesome\backend\grid\AwsGridViewAsset'] = AWS_PATH . '/grid/AwsGridViewAsset.php';
Yii::$classMap['awesome\backend\grid\AwsPager'] = AWS_PATH . '/grid/AwsPager.php';
Yii::$classMap['awesome\backend\grid\AwsPageSize'] = AWS_PATH . '/grid/AwsPageSize.php';
Yii::$classMap['awesome\backend\grid\MatchIndexColumn'] = AWS_PATH . '/grid/MatchIndexColumn.php';
Yii::$classMap['awesome\backend\helpers\CommonHelper'] = AWS_PATH . '/helpers/CommonHelper.php';
Yii::$classMap['awesome\backend\helpers\FileHelper'] = AWS_PATH . '/helpers/FileHelper.php';
Yii::$classMap['awesome\backend\toast\AwsAlertToast'] = AWS_PATH . '/toast/AwsAlertToast.php';
Yii::$classMap['awesome\backend\toast\AwsAlertToastAsset'] = AWS_PATH . '/toast/AwsAlertToastAsset.php';
Yii::$classMap['awesome\backend\widgets\AwsBaseHtml'] = AWS_PATH . '/widgets/AwsBaseHtml.php';
Yii::$classMap['awesome\backend\widgets\AwsLayoutMenu'] = AWS_PATH . '/widgets/AwsLayoutMenu.php';
Yii::$classMap['awesome\backend\assets\AssetBundle'] = AWS_PATH . '/assets/AssetBundle.php';
Yii::$classMap['awesome\backend\behavior\ManyToManyBehavior'] = AWS_PATH . '/behavior/ManyToManyBehavior.php';