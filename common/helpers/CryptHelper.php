<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10-Dec-15
 * Time: 11:09
 */

namespace common\helpers;


use yii\base\Exception;

class CryptHelper
{
    /**
     * Generate public & private key
     * @return array
     */
    public static function generatePubPriKey() {
        $priKey = '';
        $pubKey = '';
        try {
            $config = array(
//                "config" => "/home/ths/run/openssl-1.0.2c/ssl/openssl.cnf",
                "digest_alg" => "sha512",
                "private_key_bits" => 4096,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            );
            // Create the private and public key
            $res = openssl_pkey_new($config);
            // Extract the private key from $res to $priKey
            openssl_pkey_export($res, $priKey);
            // Extract the public key from $res to $pubKey
            $pubKey = openssl_pkey_get_details($res);
            $pubKey = $pubKey["key"];
        } catch (Exception $ex) {
            $priKey = 'error';
            $pubKey = 'error';
        }
        return [
            'public_key' => $pubKey,
            'private_key' => $priKey,
        ];
    }

    public static function encryptData($data, $pubKey) {
        $encrypted = '';
        openssl_public_encrypt($data, $encrypted, $pubKey);
        return $encrypted;
    }

    public static function decryptData($data, $priKey) {
        $decrypted = '';
//        $res = openssl_get_privatekey($priKey);
//        openssl_private_decrypt($data, $decrypted, $priKey);
        if(!openssl_private_decrypt($data, $decrypted, $priKey)){
            echo openssl_error_string();
        } else {
            var_dump("success");
        }

        return $decrypted;
    }
}