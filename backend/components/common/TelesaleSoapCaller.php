<?php

namespace backend\components\common;
use common\libs\Helpers;
use Yii;
class TelesaleSoapCaller {
	const SYSTEM_FAULT = '99';
	const SUCCESS = '0';
	const ALREADY_REGISTER = '3';

	public static function getGeneralErrorCodes() {
		return [
			self::SYSTEM_FAULT => Yii::t('backend', 'Lỗi không xác định'),
			'-1' => Yii::t('backend', 'Lỗi hệ thống'),
			'0' => Yii::t('backend', 'Thành công'),
			'1' => Yii::t('backend', 'Lỗi xác thực'),
		];
	}

	public static function getSmsErrorCodes() {
		return self::getGeneralErrorCodes() + [
			'2' => Yii::t('backend', 'Callid không tồn tại'),
			'3' => Yii::t('backend', 'Alias hoặc nội dung không hợp lệ'),
			'4' => Yii::t('backend', 'Thuê bao không đủ điều kiện nhận tin'),
			'5' => Yii::t('backend', 'Khung giờ gửi tin không hợp lệ'),
			'6' => Yii::t('backend', 'Số dư không đủ để thực hiện'),
			'7' => Yii::t('backend', 'Thuê bao này đã có yêu cầu gửi tin rồi'),
		];
	}

	public static function getCallErrorCodes() {
		return self::getGeneralErrorCodes() + [
			'2' => Yii::t('backend', 'Định dạng tham số không hợp lệ (sai calling hoặc số tiền)'),
			'3' => Yii::t('backend', 'Cuộc gọi không tồn tại'),
			'4' => Yii::t('backend', 'Cuộc gọi đã thực hiện rồi'),
			'5' => Yii::t('backend', 'Không thể thực hiện cuộc gọi do chính sách hệ thống'),
			'6' => Yii::t('backend', 'Hết tiền Hết tiền'),
			'9' => Yii::t('backend', 'Gọi tới SIM data'),
		];
	}

	public static function getRegisterPhoneErrorCodes() {
		return self::getGeneralErrorCodes() + [
			'2' => Yii::t('backend', 'Số thuê bao không đúng định dạng'),
			'3' => Yii::t('backend', 'Số thuê bao đã được đăng ký từ trước'),
		];
	}

	public static function getConfirmCodes() {
		return self::getGeneralErrorCodes() + [
			'2' => Yii::t('backend', 'Số thuê bao hoặc mã xác thực không đúng'),
			'3' => Yii::t('backend', 'Yêu cầu không tồn tại'),
		];
	}

	public static function getCheckUrlCodes() {
		$generalErrCode = self::getGeneralErrorCodes();
		$generalErrCode['0'] = Yii::t('backend', 'Đã được add lên hệ thống TelesaleGW');
		return $generalErrCode + [
			'10' => Yii::t('backend', 'Chưa được add lên hệ thống TelesaleGW'),
			'11' => Yii::t('backend', 'Chưa được add lên hệ thống DataMon'),
		];
	}

	protected static function tele_call($method, $params) {
		$params = array_merge([
			'username' => Yii::$app->params['telesale_gw']['username'],
			'password' => Yii::$app->params['telesale_gw']['password'],
		], $params);
		Yii::info(sprintf('Call telesale method "%s" with params: ', $method) . json_encode($params), 'telesale');

		try {
			$client = new \SoapClient(Yii::$app->params['telesale_gw']['wsdl'], [
				'trace' => 1,
				'exceptions' => true,
				'cache_wsdl' => WSDL_CACHE_NONE,
				// 'typemap' => [
				// 	'type_ns' => 'http://ws.hathanh.com.vn',
				// 	'type_name' => 'ws',
				// ],
			]);
			$soap_result = $client->$method($params);
			$error_code = $soap_result->result->code;
			Yii::info($client->__getLastRequest(), 'telesale');
			Yii::info($client->__getLastResponse(), 'telesale');
		} catch (\SoapFault $e) {
			Yii::info($e, 'telesale');
			$error_code = self::SYSTEM_FAULT;
		}

		return $error_code;
	}

	public static function sendSms($sub_id, $content, $fake_code = false) {
		if($fake_code !== false) {
			$error_code = $fake_code;
			Yii::info(sprintf('FAKING with error code %s ', $error_code), 'telesale');
		} else {
			$params = [
				'callid' => $sub_id,
				'alias' => Yii::$app->params['telesale_gw']['alias'],
				'content' => $content,
			];
			$error_code = self::tele_call('sendsms', $params);
		}

		Yii::info(sprintf('Send sms to sub_id "%s" with error code %s ', $sub_id, $error_code), 'telesale');
		return $error_code;
	}

	public static function call($request_id, $callid, $calling, $display_number, $money_limit, $fake_code = false) {
		if($fake_code !== false) {
			$error_code = $fake_code;
			Yii::info(sprintf('FAKING with error code %s ', $error_code), 'telesale');
		} else {
			$params = [
				'requestid' => $request_id,
				'callid' => $callid,
				'calling' => $calling,
				'displaynumber' => $display_number,
				'money' => $money_limit,
			];
			$error_code = self::tele_call('sendCall', $params);
		}

		Yii::info(sprintf('Call to callid "%s" with error code %s ', $callid, $error_code), 'telesale');
		return $error_code;
	}

	public static function confirm($msisdn, $code, $fake_code = false) {
		if($fake_code !== false) {
			$error_code = $fake_code;
			Yii::info(sprintf('FAKING with error code %s ', $error_code), 'telesale');
		} else {
			$params = [
				'msisdn' => $msisdn,
				'code' => $code,
			];
			$error_code = self::tele_call('confirm', $params);
		}

		Yii::info(sprintf('Verify phone number "%s" with error code %s ', $msisdn, $error_code), 'telesale');
		return $error_code;
	}

	public static function registerPhone($msisdn, $fake_code = false) {
		if($fake_code !== false) {
			$error_code = $fake_code;
			Yii::info(sprintf('FAKING with error code %s ', $error_code), 'telesale');
		} else {
			$params = [
				'msisdn' => $msisdn,
			];
			$error_code = self::tele_call('registerPhone', $params);
		}

		Yii::info(sprintf('Verify phone number "%s" with error code %s ', $msisdn, $error_code), 'telesale');
		return $error_code;
		/*$params = [
			'msisdn' => Helpers::convertMsisdn($msisdn, '84')
		];

		$response = self::tele_call('registerPhone', $params);

		return $response;*/
	}

	public static function checkUrl($url) {
		$params = [
			'url' => $url,
		];
		$error_code = self::tele_call('checkUrl', $params);
		Yii::info(sprintf('Check url "%s" with error code %s ', $url, $error_code), 'telesale');
		return $error_code;
	}

	/**
	 * Kiem tra thong tin tai khoan
	 * @return array
	 */
	public static function checkBalance() {
		$method = 'getBalance';
		$params = [
			'username' => Yii::$app->params['telesale_gw']['username'],
			'password' => Yii::$app->params['telesale_gw']['password'],
		];
		Yii::info(sprintf('Call telesale method "%s" with params: ', $method) . json_encode($params), 'telesale');

		try {
			$client = new \SoapClient(Yii::$app->params['telesale_gw']['wsdl'], [
				'trace' => 1,
				'exceptions' => true,
				'cache_wsdl' => WSDL_CACHE_NONE,
			]);
			$soap_result = $client->$method($params);
			$result = [
				'code' => $soap_result->result->code,
//				'value' => $soap_result->result->value,
			];
			if($result['code'] == self::SUCCESS) {
				list(, $tsCallBalance, ) = explode(';', $soap_result->result->value);
				$result['call'] = explode('=', $tsCallBalance)[1];
				$result['sms'] = explode('=', $tsCallBalance)[2];
			}
			Yii::info($client->__getLastRequest(), 'telesale');
			Yii::info($client->__getLastResponse(), 'telesale');
		} catch (\SoapFault $e) {
			Yii::info($e, 'telesale');
			$result = [
				'code' => self::SYSTEM_FAULT,
			];
		}

		Yii::info(sprintf('check balance with error code %s ', $result['code']), 'telesale');
		return $result;
	}

	/**
	 * @param $method
	 * @param $params
	 * @return null
	 */
	protected static function callTelesaleGwWs($method, $params) {
		$params = array_merge([
			'username' => Yii::$app->params['telesale_gw']['username'],
			'password' => Yii::$app->params['telesale_gw']['password'],
		], $params);

		Yii::info(sprintf('Call telesale method "%s" with params: ', $method) . json_encode($params), 'telesale');
		$response = null;
		try {
			//var_dump(Yii::$app->params['telesale_gw']);die;
			$client = new \SoapClient(Yii::$app->params['telesale_gw']['wsdl'], [
				'trace' => 1,
				'exceptions' => true,
				'cache_wsdl' => WSDL_CACHE_NONE,
				// 'typemap' => [
				// 	'type_ns' => 'http://ws.hathanh.com.vn',
				// 	'type_name' => 'ws',
				// ],
			]);
			$response = $client->$method($params);

			return $response;
		} catch (SoapFault $e) {
			Yii::info(sprintf('Call telesale method "%s" error: ', $method) . $e->getMessage(), 'telesale');
			return $response;
		} finally {
			Yii::info(sprintf('Call telesale method "%s" response: ', $method) . json_encode($response), 'telesale');
			return $response;
		}
	}
}