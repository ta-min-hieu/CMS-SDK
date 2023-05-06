<?php

namespace backend\models;

use Yii;
use common\models\OABase;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
class OA extends OABase {
    public function uploadMedia()
    {
        if ($this->validate() && $this->video) {
            $absSavePath = $this->getAbsAnhPath();
            $fileName = md5($this->id.'_'. time(). Inflector::camel2id($this->video->name)).'.'. $this->video->extension;
            $fullFilePath = Yii::$app->params['media_path'] . $absSavePath;
            if (!file_exists($fullFilePath)) {
                FileHelper::createDirectory($fullFilePath);
            }
            $this->video->saveAs($fullFilePath . DIRECTORY_SEPARATOR . $fileName);
            $this->video = '/medias/cms_upload'.$absSavePath . $fileName;
            $this->update(false, ['video']);
            return true;
        } else {
            return false;
        }
    }
    public function getAbsAnhPath() {
        return '/oa/audio/'. date('Y/m/d/');
    }
    public function getMediaPathUrl() {
        if (strpos($this->video, 'http') === 0) {
            return $this->video;
        } else {
            return Yii::$app->params['domain_file']. $this->video;
        }
    }
    public function uploadExcel()
    {
        if ($this->validate() && $this->excel) {
            $absSavePath = $this->getAbsExcelPath();
            // var_dump($this->excel->name);
            // die();
            $fileName = md5($this->id.'_'. time(). Inflector::camel2id($this->excel->name)).'.'. $this->excel->extension;
            $fullFilePath = Yii::$app->params['media_path'] . $absSavePath;
            if (!file_exists($fullFilePath)) {
                FileHelper::createDirectory($fullFilePath);
            }
            $this->excel->saveAs($fullFilePath . DIRECTORY_SEPARATOR . $fileName);
            $this->excel = '/medias/cms_upload'.$absSavePath . $fileName;
            $this->update(false, ['excel']);
            return true;
        } else {
            return false;
        }
    }
    public function getAbsExcelPath() {
        return '/oa/excel/'. date('Y/m/d/');
    }
    public function getExcelPathUrl() {
        if (strpos($this->excel, 'http') === 0) {
            return $this->excel;
        } else {
            return Yii::$app->params['domain_file']. $this->excel;
        }
    }
    public function rules()
    {
        $rules = [
            
            [['type','id_official_account','time'], 'required'],
            [['type','id_official_account'], 'string', 'max' => 45],
            [['text'], 'string', 'max' => 200],
            [['time','image'], 'string', 'max'=>500],
            [['excel'], 'file','skipOnEmpty' => true, 'maxSize' => 104857600, 'tooBig' => Yii::t('backend', 'File is too big, maximun is 100Mb') ],
            ['text','required','when'=>function($model) {
                return $model->type == 'text';
            }, 'whenClient' => "function (attribute, value) {
                return $('#oa-type').val() == 'text';
            }"],
            ['image','required','when'=>function($model) {
                return $model->type == 'image';
            }, 'whenClient' => "function (attribute, value) {
                return $('#oa-type').val() == 'image';
            }"],
        ];
        if ($this->isNewRecord) {
            $rules = array_merge($rules, [
                [['video'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4', 'maxSize' => 104857600, 'tooBig' => Yii::t('backend', 'File is too big, maximun is 100Mb') ],
                ['video', 'required', 'when' => function ($model) {
                    return $model->type == "video";
                }, 'whenClient' => "function (attribute, value) {
                    return $('#oa-type').val() == 'video';
                }"],
            ]);
        } else {
            $rules = array_merge($rules, [
                [['video'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4', 'maxSize' => 104857600, 'tooBig' => Yii::t('backend', 'File is too big, maximun is 100Mb') ],
            ]);
        }
        return $rules;
    }
}
