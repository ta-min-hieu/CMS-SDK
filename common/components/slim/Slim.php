<?php

namespace common\components\slim;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

abstract class SlimStatus {
    const Failure = 'failure';
    const Success = 'success';
}

class Slim {

    public static function getImages($inputName = 'slim') {

        $values = Slim::getPostData($inputName);

        // test for errors
        if ($values === false) {
            return false;
        }

        // determine if contains multiple input values, if is singular, put in array
        $data = array();
        if (!is_array($values)) {
            $values = array($values);
        }

        // handle all posted fields
        foreach ($values as $value) {
            $inputValue = Slim::parseInput($value);
            if ($inputValue) {
                $inputValue['input_name'] = $inputName;
            }

            if (isset($_FILES['ORG_IMAGE_'. $inputName])) {
                $inputValue[$inputName.'_org_image'] = $_FILES['ORG_IMAGE_'. $inputName];
                $inputValue[$inputName.'_org_image']['data'] = file_get_contents($_FILES['ORG_IMAGE_'. $inputName]['tmp_name']);
            }

            if ($inputValue) {
                array_push($data, $inputValue);
            }
        }

        // return the data collected from the fields

        return $data;

    }

    // $value should be in JSON format
    private static function parseInput($value) {

        // if no json received, exit, don't handle empty input values.
        if (empty($value)) {return null;}

        // The data is posted as a JSON String so to be used it needs to be deserialized first
        $data = json_decode($value);

        // shortcut
        $input = null;
        $actions = null;
        $output = null;
        $meta = null;

        if (isset ($data->input)) {
            $inputData = isset($data->input->image) ? Slim::getBase64Data($data->input->image) : null;
            $input = array(
                'data' => $inputData,
                'name' => $data->input->name,
                'type' => $data->input->type,
                'size' => $data->input->size,
                'width' => $data->input->width,
                'height' => $data->input->height,
            );
        }

        if (isset($data->output)) {
            $outputData = isset($data->output->image) ? Slim::getBase64Data($data->output->image) : null;
            $output = array(
                'data' => $outputData,
                'width' => $data->output->width,
                'height' => $data->output->height
            );
        }

        if (isset($data->actions)) {
            $actions = array(
                'crop' => $data->actions->crop ? array(
                    'x' => $data->actions->crop->x,
                    'y' => $data->actions->crop->y,
                    'width' => $data->actions->crop->width,
                    'height' => $data->actions->crop->height,
                    'type' => $data->actions->crop->type
                ) : null,
                'size' => $data->actions->size ? array(
                    'width' => $data->actions->size->width,
                    'height' => $data->actions->size->height
                ) : null
            );
        }

        if (isset($data->meta)) {
            $meta = $data->meta;
        }

        // We've sanitized the base64data and will now return the clean file object
        return array(
            'input' => $input,
            'output' => $output,
            'actions' => $actions,
            'meta' => $meta
        );
    }

    // $path should have trailing slash
    public static function saveFile($data, $name, $path = 'tmp/', $uid = true) {

        // Add trailing slash if omitted
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        // Test if directory already exists
        if(!is_dir($path)){
            mkdir($path, 0755);
        }

        // Let's put a unique id in front of the filename so we don't accidentally overwrite older files
        if ($uid) {
            $name = uniqid() . '_' . $name;
        }
        $path = $path . $name;

        // store the file
        Slim::save($data, $path);

        // return the files new name and location
        return array(
            'name' => $name,
            'path' => $path
        );
    }

    public static function outputJSON($status, $fileName = null, $filePath = null) {

        header('Content-Type: application/json');

        if ($status !== SlimStatus::Success) {
            echo json_encode(array('status' => $status));
            return;
        }

        echo json_encode(
            array(
                'status' => $status,
                'name' => $fileName,
                'path' => $filePath
            )
        );
    }

    /**
     * Gets the posted data from the POST or FILES object. If was using Slim to upload_remove it will be in POST (as posted with hidden field) if not enhanced with Slim it'll be in FILES.
     * @param $inputName
     * @return array|bool
     */
    private static function getPostData($inputName) {

        $values = array();

        if (isset($_POST[$inputName])) {
            $values = $_POST[$inputName];
        }
        else if (isset($_FILES[$inputName])) {
            // Slim was not used to upload_remove this file
            return false;
        }

        return $values;
    }

    /**
     * Saves the data to a given location
     * @param $data
     * @param $path
     */
    private static function save($data, $path) {
        file_put_contents($path, $data);
    }

    /**
     * Strips the "data:image..." part of the base64 data string so PHP can save the string as a file
     * @param $data
     * @return string
     */
    private static function getBase64Data($data) {
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
    }


    /**
     *
     * @param $inputName
     * @return null | image data
     */
    public static function getImagesFromSlimRequest($inputName) {
        $images = Slim::getImages($inputName);

        if($images != false && sizeof($images) > 0) {
            $image = $images[0];
            $image_data = null;
            if (isset($image['input'])) {
                $image_data = $image['input'];
                $image_data['tmp_name'] = $image['input']['name'];
            }

            return $image_data;
        } else {
            return null;
        }

    }

    /**
     * Xu ly luu anh va tra ve duong dan tuong doi de luu vao DB
     * @param $image
     * @param $savePath
     * @param bool $isUpdate
     * @return string
     */
    public static function saveSlimImage($appName, $folderSave, $image, $savePath = '', $fileName = null) {

        if (!$savePath) {
            // Sinh duong dan luu file anh
            $currentMediaFolder = 'medias' ? 'medias': 'cms_upload';
            $uploadFolder = (Yii::$app->params[$folderSave. '_image_folder'])? Yii::$app->params[$folderSave. '_image_folder']: $folderSave;

            $savePath = Yii::getAlias('@'. $appName. '_web') . DIRECTORY_SEPARATOR .  $currentMediaFolder. DIRECTORY_SEPARATOR. $uploadFolder.  DIRECTORY_SEPARATOR. date('Y/m/d');;
            \yii\helpers\FileHelper::createDirectory($savePath, 0777);
        } elseif (!file_exists($savePath)) {
            var_dump($savePath);die;
            FileHelper::createDirectory($savePath);
        }

        // Generate file name


        $pathInfo = pathinfo($image['input']['name']);

        $fileNameClean = preg_replace('/[^A-Za-z0-9\-]/', '', md5($pathInfo['filename'])); // Removes special chars.


        if ($fileNameClean) {
            $nameSlug = Inflector::camel2id($fileNameClean). '-'. time();
        }

        $fileName = (($fileName)? $fileName: $nameSlug). '.' . $pathInfo['extension'];

        @unlink($savePath. DIRECTORY_SEPARATOR. $fileName);

        if (!file_exists($savePath))
            mkdir($savePath, 0775, true);

        $saveImageData = ($image['input']['type'] == 'image/gif')? $image[$image['input_name'].'_org_image']['data']: $image['output']['data'];

        $file = Slim::saveFile($saveImageData, $fileName, $savePath, false);
        chmod($file['path'], 0777);
        unset($file);

        // tra ve duong dan tuong doi
        $altPath = str_replace([\Yii::getAlias('@'. $appName. '_web'), '\\'], ['', '/'], $savePath. DIRECTORY_SEPARATOR. $fileName);

        return $altPath;
    }

    /**
     *
     * Tra ve duong dan tuong doi sau khi bo di media_path
     * @param $appName
     * @param $item
     * @param $image
     * @param string $savePath
     * @param null $fileName
     * @return mixed
     * @throws \yii\base\Exception
     */
    public static function saveSlimImage2($item, $image, $savePath = '', $fileName = null) {

        if (!$savePath) {
            // Sinh duong dan luu file anh
            $currentMediaFolder = basename(Yii::$app->params['media_path'])? basename(Yii::$app->params['media_path']): 'cms_upload';
            $uploadFolder = (Yii::$app->params[$item. '_image_folder'])? Yii::$app->params[$item. '_image_folder']: $item;

            $savePath = Yii::$app->params['media_path'] . DIRECTORY_SEPARATOR .  $currentMediaFolder. DIRECTORY_SEPARATOR. $uploadFolder.  DIRECTORY_SEPARATOR. date('Y/m/d');;
            FileHelper::createDirectory($savePath, 0777);
        } elseif (!file_exists($savePath)) {

            FileHelper::createDirectory($savePath, 0777);
        }

        // Generate file name
        $pathInfo = pathinfo($image['input']['name']);

        $fileNameClean = preg_replace('/[^A-Za-z0-9\-]/', '', $pathInfo['filename']); // Removes special chars.


        if ($fileNameClean) {
            $nameSlug = Inflector::camel2id($fileNameClean). '-'. time();
        }

        $fileName = (($fileName)? $fileName: $nameSlug). '.' . $pathInfo['extension'];

        @unlink($savePath. DIRECTORY_SEPARATOR. $fileName);

        if (!file_exists($savePath))
            mkdir($savePath, 0775, true);

        $saveImageData = ($image['input']['type'] == 'image/gif')? $image[$image['input_name'].'_org_image']['data']: $image['output']['data'];

        $file = Slim::saveFile($saveImageData, $fileName, $savePath, false);
        chmod($file['path'], 0777);
        unset($file);

        // tra ve ten file
        return $fileName;

    }
    public static function processUploadNewImage($model, $imageFieldName,$folderSave)
    {

        $images = Slim::getImages($imageFieldName); // lay du lieu anh

        if ($images != false && sizeof($images) > 0) {
            $imagePath = Slim::saveSlimImage('backend', $folderSave, $images[0]);
            $model->$imageFieldName = $imagePath;
        }
    }

    public static function processUpdateImage($model, $form_values, $imageFieldName,$folderSave)
    {
        if ($form_values['change_' . $imageFieldName] == '1') {

            $oldImagePath = ($model->$imageFieldName) ?
                Yii::getAlias('@backend_web') . DIRECTORY_SEPARATOR . $model->$imageFieldName :
                null;
            if ($oldImagePath && file_exists($oldImagePath))
                unlink($oldImagePath);
            $model[$imageFieldName] = null;
            Slim::processUploadNewImage($model,$imageFieldName,$folderSave);
        }

    }
}