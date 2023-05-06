<?php

namespace backend\models;

use Yii;
use common\models\ListExcelBase;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
class ListExcel extends ListExcelBase {
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
        return '/list/excel/'. date('Y/m/d/');
    }
    public function getExcelPathUrl() {
        if (strpos($this->excel, 'http') === 0) {
            return $this->excel;
        } else {
            return Yii::$app->params['domain_file']. $this->excel;
        }
    }    
}
