<?php
/**
 * Created by PhpStorm.
 * Date: 8/19/20
 * Time: 23:19
 */

namespace common\core;

use common\helpers\Helpers;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii;

class SuperAppApiGw
{

    /**
     * Gui MT cho nhieu thue bao voi cung content
     * @param $from
     * @param $to - array of msisdn
     * @param $content
     */
    public static function sendMt($from, $to, $content)
    {
        try {
            // Day vao queue rabit
            $mqConfig = Yii::$app->params['rabbitmq_config'];
            $connection = new AMQPStreamConnection($mqConfig['host'], $mqConfig['port'], $mqConfig['user'], $mqConfig['password']);
            $channel = $connection->channel();

            $channel->queue_declare($mqConfig['mt_queue_name'], false, true, false, false);

            // {"from" : "9005", "to": ["+51927247599"], "content" : "Nội dung tin nhắn"}
            if (is_string($to)) {
                $to = [$to => $to];
            }

            $toArr = [];
            if (count($to)) {
                foreach ($to as $number) {
                    $toArr[] = Helpers::convertMsisdn($number, '+84x');
                }
            }

            $data = [
                'from' => $from,
                'to' => $toArr,
                'content' => $content,
            ];

            Yii::info('[SEND MT] DATA=' . json_encode($data), 'app_api');
            $msg = new AMQPMessage(json_encode($data), array('delivery_mode' => 2));
            $channel->basic_publish($msg, '', $mqConfig['mt_queue_name']);

            return true;
        } catch (\Exception $e) {
            Yii::error('[SEND MT] error=' . $e->getMessage(), 'app_api');
        }

        return false;
    }

    public static function blockUser($username, $status)
    {
        $apiUrl = 'http://125.234.172.178/RingMeBiz-DEV/privateapi/block';
        $timeout = 30;
        /**
         * URL: http://125.234.172.178/RingMeBiz-DEV/privateapi/block
         * Method: POST
         * Params:
         * - username: Số điện thoại cần block/unblock
         * - block: 0. unblock, 1. block
         */

        $client = new yii\httpclient\Client();

        $headers = new yii\web\HeaderCollection();
        //$headers->add('Content-Type', 'application/json');
        // $headers->add('Authorization', 'Bearer ' . $token);
        $headers->add('Accept', '*/*');

        try {
            $request = $client->createRequest()
                ->setMethod('POST')
                ->setHeaders($headers)
                //->setFormat(yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ])
                ->setData([
                    'username' => urlencode(Helpers::convertMsisdn($username, '+84x')),
                    'block' => $status,
                ]);

            Yii::info('[CALL BLOCK USER API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[CALL BLOCK USER API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');

            if ($response && $response->isOk) {
                $json = json_decode($response->getContent(), true);
                if (isset($json['code'])) {
                    // Thanh cong
                    $responseArr['errorCode'] = $json['code'];
                    $responseArr['message'] = (isset($json['desc'])) ? $json['desc'] : '';
                    return $responseArr;
                } else {
                    $responseArr['errorCode'] = (isset($json['status'])) ? $json['status'] : 9997;
                    $responseArr['message'] = (isset($json['message'])) ? $json['message'] : Yii::t(Yii::$app->id, 'Unsuccessful!');
                    return $responseArr;
                }

            } else {

                Yii::error('[CALL BLOCK USER API] unknown error: ' . json_encode($response), 'app_api');

                $responseArr['errorCode'] = 9998;
                $responseArr['message'] = Yii::t(Yii::$app->id, 'Response is not OK!');
                return $responseArr;

            }
        } catch (\Exception $e) {
            Yii::error('[CALL BLOCK USER API] error: ' . $e->getMessage(), 'app_api');

            $responseArr['errorCode'] = 9999;
            $responseArr['message'] = $e->getMessage();
            return $responseArr;
        }
    }

    /**
     * gui ma otp
     * @param $username
     * @return mixed
     */
    public static function sendOtp($username)
    {
        $apiUrl = 'http://125.234.172.178/RingMeBiz-DEV/privateapi/resendotp';
        $timeout = 30;
        /**
         * URL: http://125.234.172.178/RingMeBiz-DEV/privateapi/resendotp
         * Method: POST
         * Params:
         * - username: Số điện thoại cần gửi OTP
         */

        $client = new yii\httpclient\Client();

        $headers = new yii\web\HeaderCollection();
        //$headers->add('Content-Type', 'application/json');
        // $headers->add('Authorization', 'Bearer ' . $token);
        $headers->add('Accept', '*/*');

        try {
            $request = $client->createRequest()
                ->setMethod('POST')
                ->setHeaders($headers)
                //->setFormat(yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ])
                ->setData([
                    'username' => urlencode(Helpers::convertMsisdn($username, '+84x')),
                ]);

            Yii::info('[SEND OTP API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[SEND OTP API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');

            if ($response && $response->isOk) {
                $json = json_decode($response->getContent(), true);
                if (isset($json['code'])) {
                    // Thanh cong
                    $responseArr['errorCode'] = $json['code'];
                    $responseArr['message'] = (isset($json['desc'])) ? $json['desc'] : '';
                    return $responseArr;
                } else {
                    $responseArr['errorCode'] = (isset($json['status'])) ? $json['status'] : 9997;
                    $responseArr['message'] = (isset($json['message'])) ? $json['message'] : Yii::t(Yii::$app->id, 'Unsuccessful!');
                    return $responseArr;
                }

            } else {

                Yii::error('[SEND OTP API] unknown error: ' . json_encode($response), 'app_api');

                $responseArr['errorCode'] = 9998;
                $responseArr['message'] = Yii::t(Yii::$app->id, 'Response is not OK!');
                return $responseArr;

            }
        } catch (\Exception $e) {
            Yii::error('[SEND OTP API] error: ' . $e->getMessage(), 'app_api');

            $responseArr['errorCode'] = 9999;
            $responseArr['message'] = $e->getMessage();
            return $responseArr;
        }
    }

    /**
     * @param $videoId
     * @return mixed
     */
    public static function convertVideo($videoId)
    {
        $apiUrl = Yii::$app->params['convert_video_api'];
        $timeout = 30;
        $client = new yii\httpclient\Client();

        $headers = new yii\web\HeaderCollection();
        //$headers->add('Content-Type', 'application/json');
        // $headers->add('Authorization', 'Bearer ' . $token);
        $headers->add('Accept', '*/*');

        try {
            $request = $client->createRequest()
                ->setMethod('POST')
                ->setHeaders($headers)
                //->setFormat(yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ])
                ->setData([
                    'videoId' => $videoId,
                ]);

            Yii::info('[CALL CONVERT VIDEO API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[CALL CONVERT VIDEO API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');
            return true;
        } catch (\Exception $e) {
            Yii::error('[CALL CONVERT VIDEO API] error: ' . $e->getMessage(), 'app_api');

            return false;
        }
    }

    /**
     * Goi api update cache
     * @param $action  insert|update|delete
     * @param $item
     * @param $itemIds (string | array)
     * @return bool
     */
    public static function updateCache($action, $item, $itemIds)
    {
        $apiInfo = Yii::$app->params['update_cache_api'];
        $apiUrl = $apiInfo['url'] . $item;

        $data = [
            'security' => $apiInfo['security'],
            'func' => $action,
            $item . 'Id' => (is_array($itemIds)) ? implode(',', $itemIds) : $itemIds
        ];


        $timeout = 30;
        $client = new yii\httpclient\Client();

        $headers = new yii\web\HeaderCollection();
        $headers->add('Accept', '*/*');

        try {
            $request = $client->createRequest()
                ->setMethod('GET')
                ->setHeaders($headers)
                //->setFormat(yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ])
                ->setData($data);

            Yii::info('[CALL UPDATE CACHE API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[CALL UPDATE CACHE API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');
            return true;
        } catch (\Exception $e) {
            Yii::error('[CALL UPDATE CACHE API] error: ' . $e->getMessage(), 'app_api');

            return false;
        }
    }

    /**
     * Goi api update media sang cac cdn
     * @param $action  insert|update|delete
     * @param $item
     * @param $itemIds (string | array)
     * @return bool
     */
    public static function updateMedia($type, $contentId)
    {
        $apiInfo = Yii::$app->params['update_media_api'];
        $apiUrl = $apiInfo['url'];

        $data = [
            'security' => $apiInfo['security'],
            'type' => $type,
            'contentId' => $contentId
        ];


        $timeout = 30;
        $client = new yii\httpclient\Client();

        $headers = new yii\web\HeaderCollection();
        $headers->add('Accept', '*/*');

        try {
            $request = $client->createRequest()
                ->setMethod('POST')
                ->setHeaders($headers)
                //->setFormat(yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ])
                ->setData($data);

            Yii::info('[CALL UPDATE MEDIA API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[CALL UPDATE MEDIA API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');
            return true;
        } catch (\Exception $e) {
            Yii::error('[CALL UPDATE MEDIA API] error: ' . $e->getMessage(), 'app_api');

            return false;
        }
    }

    /**
     * Goi api approve comment report
     * @param $reportId
     * @param $commentId
     * @return bool
     */
    public static function approveCommentReport($reportId, $commentId)
    {
        $apiInfo = Yii::$app->params['comment_report_api'];
        $apiUrl = $apiInfo['url'];

        $data = [
            'security' => $apiInfo['security'],
            'commentId' => $commentId,
            'reportId' => $reportId,
        ];


        $timeout = 30;
        $client = new yii\httpclient\Client();

        $headers = new yii\web\HeaderCollection();
        $headers->add('Accept', '*/*');

        try {
            $request = $client->createRequest()
                ->setMethod('POST')
                ->setHeaders($headers)
                //->setFormat(yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ])
                ->setData($data);

            Yii::info('[CALL APPROVE COMMENT REPORT API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[CALL APPROVE COMMENT REPORT API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');
            return true;
        } catch (\Exception $e) {
            Yii::error('[CALL APPROVE COMMENT REPORT API] error: ' . $e->getMessage(), 'app_api');

            return false;
        }
    }


    public static function clearCache()
    {
        $apiUrl = 'http://kakoakapi.ringme.vn:8080/notification-camid/welcome/clearCache';
        $timeout = 30;
        $client = new yii\httpclient\Client();

        $headers = new yii\web\HeaderCollection();
        //$headers->add('Content-Type', 'application/json');
        // $headers->add('Authorization', 'Bearer ' . $token);
        $headers->add('Accept', '*/*');

        try {
            $request = $client->createRequest()
                ->setMethod('GET')
                ->setHeaders($headers)
                //->setFormat(yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ]);

            Yii::info('[CALL CLEAR CACHE API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[CALL CLEAR CACHE API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');
            return true;
        } catch (\Exception $e) {
            Yii::error('[CALL CLEAR CACHE API] error: ' . $e->getMessage(), 'app_api');

            return false;
        }
    }

    public static function clearCacheHomeMenu()
    {
        $apiUrl = 'http://kakoakapi.ringme.vn:8080/notification-camid/api/v1/clear';
        $timeout = 30;
        $client = new yii\httpclient\Client();

        $headers = new yii\web\HeaderCollection();
        $headers->add('Accept', '*/*');

        try {
            $request = $client->createRequest()
                ->setMethod('GET')
                ->setHeaders($headers)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ]);

            Yii::info('[CALL CLEAR CACHE HOME MENU  API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[CALL CLEAR CACHE HOME MENU API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');
            return true;
        } catch (\Exception $e) {
            Yii::error('[CALL CLEAR CACHE HOME MENU API] error: ' . $e->getMessage(), 'app_api');

            return false;
        }
    }

    public static function clearCacheDigitalService()
    {
        $apiUrl = Yii::$app->params['clear_cache_digital_service'];
        $timeout = 30;
        $client = new yii\httpclient\Client();
        $data = '{
        "language": "en",
        "sessionId": "dab828d6-2214-476c-9fff-f677d19ba0bb",
        "token": "EaTjJyqzFTst663DgKxu9Q==",
        "wsCode": "wsDigitalService",
        "wsRequest": {
                "timestamp": "1656035011979"
        }
          }';
        $headers = new yii\web\HeaderCollection();
        $headers->add('Accept', '*/*');
        $headers->add('Content-Type', 'application/json ');

        try {
            $request = $client->createRequest()
                ->setMethod('POST')
                ->setHeaders($headers)
                ->setUrl($apiUrl)
                ->setOptions([
                    'timeout' => $timeout
                ])
                ->setContent($data);
            Yii::info('[CALL CLEAR CACHE Degital Service  API] REQUEST URL=' . $request->getFullUrl() . json_encode($headers) . '; DATA=' . json_encode($request->getData()), 'app_api');
            $response = $request->send();

            Yii::info('[CALL CLEAR CACHE Degital Service API] RESPONSE: ' . json_encode($response->getContent()), 'app_api');
            return true;
        } catch (\Exception $e) {
            Yii::error('[CALL CLEAR CACHE Degital Service API] error: ' . $e->getMessage(), 'app_api');

            return false;
        }
    }
}