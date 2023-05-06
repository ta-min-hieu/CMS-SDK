<?php

namespace common\helpers;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Yii;

class S3Service
{
    const SUCCESS = 0;
    const FAILED = 1;


    /**
     * Generate link Web tren CDN: jpg, png...
     * @param $bucketName
     * @param $path
     * @return string
     */
    public static function generateWebUrl($bucketName, $path)
    {
        $mapping = Yii::$app->params['s3']['cdn.mapping'];

        $args = array(
            '%domain_name%' => isset($mapping[$bucketName]) ? $mapping[$bucketName] : '',
            '%path%' => $bucketName . '/' . $path,
        );

        return strtr(Yii::$app->params['s3']['webcdn.template'], $args);
    }

    public static function gen_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function uploadImage($filePath)
    {
        if (!file_exists($filePath)) {
            Yii::error('uploadImage:file not exist' . '|filePath:' . $filePath, 's3');
            return array(
                'errorCode' => self::FAILED,
                'message' => 'Thất bại',
            );
        }
        $bucket = Yii::$app->params['s3']['static.bucket'];
        $randomStr = self::gen_uuid();
        $keyName = date("Y/m/d/H", time()) . "/" . substr($randomStr, 0, strpos($randomStr, "-")) . "/" . $randomStr . "." .
            pathinfo($filePath, PATHINFO_EXTENSION);
        // 1. Instantiate the client.
        $sharedConfig = [
            'endpoint' => Yii::$app->params['s3']['endPoint'],
            'region' => 'us-west-2',
            'version' => 'latest',
            'signature_version' => 'v4',
            'credentials' => array(
                'key' => Yii::$app->params['s3']['accessKey'],
                'secret' => Yii::$app->params['s3']['secretKey']
            ),
            'request.options' => [
                'proxy' => Yii::$app->params['s3']['proxy.host']
            ],
            'debug' => true
        ];
        $s3 = S3Client::factory($sharedConfig);
        // 2. Create a new multipart upload and get the upload ID.
        $result = $s3->createMultipartUpload(array(
            'Bucket' => $bucket,
            'Key' => $keyName,
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'PathStyle' => Yii::$app->params['s3']['PathStyle'],
            'ACL' => 'public-read',
        ));
        $uploadId = $result['UploadId'];

        // 3. Upload the file in parts.
        $parts = array();
        try {
            $file = fopen($filePath, 'r');
            $partNumber = 1;
            while (!feof($file)) {
                $result = $s3->uploadPart(array(
                    'Bucket' => $bucket,
                    'Key' => $keyName,
                    'UploadId' => $uploadId,
                    'PartNumber' => $partNumber,
                    'Body' => fread($file, 5 * 1024 * 1024),
                    'PathStyle' => Yii::$app->params['s3']['PathStyle'],
                ));
                $parts[] = array(
                    'PartNumber' => $partNumber++,
                    'ETag' => $result['ETag'],
                );
                Yii::info("Uploading part " . $partNumber . " of " . $filePath . ".", "s3");
            }
            fclose($file);
        } catch (S3Exception $e) {
            $result = $s3->abortMultipartUpload(array(
                'Bucket' => $bucket,
                'Key' => $keyName,
                'PathStyle' => Yii::$app->params['s3']['PathStyle'],
                'UploadId' => $uploadId
            ));
            Yii::info("Upload of " . $filePath . " failed. S3Exception=" . $e->getTraceAsString(), "s3");
            return array(
                'errorCode' => self::FAILED,
                'message' => 'Upload thất bại',
            );
        }

        // 4. Complete multipart upload.
        $result = $s3->completeMultipartUpload(array(
            'Bucket' => $bucket,
            'Key' => $keyName,
            'UploadId' => $uploadId,
            'PathStyle' => Yii::$app->params['s3']['PathStyle'],
            'Parts' => $parts,
        ));
        Yii::info("Upload of " . $filePath . " success to " . $keyName . ".", "s3");
        return array(
            'errorCode' => self::SUCCESS,
            'message' => 'Upload thành công',
            'image_path' => [
                "bucket" => $bucket,
                "path" => "/" . $keyName,
                "domain" => Yii::$app->params['storage-url']['image']['show_url'],
            ]
        );
    }

    public static function deleteObject($bucketName, $key)
    {
        $sharedConfig = [
            'endpoint' => Yii::$app->params['s3']['endPoint'],
            'region' => 'us-west-2',
            'version' => 'latest',
            'signature_version' => 'v4',
            'credentials' => array(
                'key' => Yii::$app->params['s3']['accessKey'],
                'secret' => Yii::$app->params['s3']['secretKey']
            ),
            'request.options' => [
                'proxy' => Yii::$app->params['s3']['proxy.host']
            ],
            'debug' => false

        ];
        Yii::info("Delete " . $key . " success from " . $bucketName . ".", "s3");
        try {
            $s3 = S3Client::factory($sharedConfig);
            $result = $s3->deleteObject(array(
                'Bucket' => $bucketName,
                'Key' => $key,
                'PathStyle' => Yii::$app->params['s3']['PathStyle'],
            ));
        } catch (S3Exception $e) {
            Yii::info("Delete " . $key . " failed from " . $bucketName . ".", "s3");
            Yii::error("Trace: " . $e->getTraceAsString());
        }
    }

    /**
     * Upload file len S3 Storage
     * @param $bucketName
     * @param $orgPath
     * @param $newPath
     * @return array
     */
    public static function putObject($bucketName, $orgPath, $newPath)
    {
        if (!file_exists($orgPath)) {
            Yii::error('putObject:file not exist' . '|bucketName:' . '|' . $orgPath . '|' . $newPath, 's3');
            return array(
                'errorCode' => self::FAILED,
                'message' => 'Thất bại',
            );
        }


        $sharedConfig = [
            'endpoint' => Yii::$app->params['s3']['endPoint'],
            'region' => 'us-west-2',
            'version' => 'latest',
            'signature_version' => 'v4',
            'credentials' => array(
                'key' => Yii::$app->params['s3']['accessKey'],
                'secret' => Yii::$app->params['s3']['secretKey']
            ),
            'request.options' => [
                'proxy' => Yii::$app->params['s3']['proxy.host']
            ],
            'debug' => false

        ];

        $s3 = S3Client::factory($sharedConfig);

        try {
            $result = $s3->putObject(
                array(
                    'Bucket' => $bucketName,
                    'Key' => $newPath,
                    'SourceFile' => $orgPath,
                    'ContentType' => mime_content_type($orgPath),
                    'PathStyle' => Yii::$app->params['s3']['PathStyle'],
                    'ACL' => 'public-read',
                )
            );

            if (!$result) {
                Yii::error('putObject:' . $result . '|bucketName:' . '|' . $orgPath . '|' . $newPath, 's3');
                return array(
                    'errorCode' => self::FAILED,
                    'message' => 'Thất bại',
                );
            } else {
                Yii::info('putObject:' . $result . '|bucketName:' . '|' . $orgPath . '|' . $newPath, 's3');
                return array(
                    'errorCode' => self::SUCCESS,
                    'message' => 'Thành công',
                );
            }
        } catch (S3Exception $e) {
            Yii::error('putObject:Exception|bucketName:' . '|' . $orgPath . '|' . $newPath . ': ' . $e, 's3');
            return array(
                'errorCode' => self::FAILED,
                'message' => 'Thất bại',
            );
        }

    }


    /**
     * Upload file len S3 Storage
     * @param $bucketName
     * @param $keyName
     * @param $localPath
     * @return array
     */
    public static function getObject($bucketName, $keyName, $localPath)
    {
        try {
            $sharedConfig = [
                'endpoint' => Yii::$app->params['s3']['endPoint'],
                'region' => 'us-west-2',
                'version' => 'latest',
                'signature_version' => 'v4',
                'credentials' => array(
                    'key' => Yii::$app->params['s3']['accessKey'],
                    'secret' => Yii::$app->params['s3']['secretKey']
                ),
                'http' => [
                    'proxy' => Yii::$app->params['s3']['proxy.host']
                ],
                'debug' => false
            ];
            $s3 = S3Client::factory($sharedConfig);

            $result = $s3->getObject(array(
                'Bucket' => $bucketName,
                'Key' => $keyName,
                'SaveAs' => $localPath,
                'PathStyle' => Yii::$app->params['s3']['PathStyle'],
            ));

            if (!$result) {
                Yii::error('getObject:' . $result . '|bucketName:' . '|' . $bucketName . '|keyName' . $keyName . '|localPath' . $localPath, 's3');
                return array(
                    'errorCode' => self::FAILED,
                    'message' => 'Thất bại',
                );
            } else {
                Yii::info('getObject:' . $result . '|bucketName:' . '|' . $bucketName . '|keyName' . $keyName . '|localPath' . $localPath, 's3');
                return array(
                    'errorCode' => self::SUCCESS,
                    'message' => 'Thành công',
                );
            }
        } catch (S3Exception $e) {
            Yii::error('getObject:Exception|bucketName:' . '|' . $bucketName . '|keyName' . $keyName . '|localPath' . $localPath . ': ' . $e, 's3');
            return array(
                'errorCode' => self::FAILED,
                'message' => 'Thất bại',
            );
        }
    }

    /**
     * List danh sach object cua folder
     * @param $bucketName
     * @param $folder
     * @return array
     */
    public static function listObjects($bucketName, $folder)
    {
        try {
            $sharedConfig = [
                'endpoint' => Yii::$app->params['s3']['endPoint'],
                'region' => 'us-west-2',
                'version' => 'latest',
                'signature_version' => 'v4',
                'credentials' => array(
                    'key' => Yii::$app->params['s3']['accessKey'],
                    'secret' => Yii::$app->params['s3']['secretKey']
                ),
                'http' => [
                    'proxy' => Yii::$app->params['s3']['proxy.host']
                ],
                'debug' => false
            ];
            $s3 = S3Client::factory($sharedConfig);

            $result = $s3->listObjects(array(
                // Bucket is required
                'Bucket' => $bucketName,
                'PathStyle' => Yii::$app->params['s3']['PathStyle'],
                'Prefix' => $folder,
            ));
            return array(
                'errorCode' => self::SUCCESS,
                'message' => 'Thành công',
                'data' => $result['Contents']
            );

        } catch (S3Exception $e) {
            Yii::error('listObjects:Exception|bucketName:' . '|' . $bucketName . '|folder' . $folder . ': ' . $e, 's3');
            return array(
                'errorCode' => self::FAILED,
                'message' => 'Thất bại',
                'data' => []
            );
        }
    }

    public static function getFileSize($bucketName, $filePath)
    {
        $folder = dirname($filePath);

        $result = S3Service::listObjects($bucketName, $folder);
        foreach ($result['data'] as $object) {
            if ($filePath == $object['Key']) {
                return $object['Size'];
            }
        }
        return 0;
    }
}
