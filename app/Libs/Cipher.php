<?php

namespace App\Libs;

use App\Libs\Config;
use Exception;

class Cipher
{
    private static $key;
    private static $cipher = 'AES-256-CBC';

    protected static function getKey()
    {
        // Get the key from APP_KEY and decode it from base64
        if (!self::$key) {
            self::$key = base64_decode(explode(':', Config::get('app', 'key'))[1]);
        }
        return self::$key;
    }

    public static function encrypt($data)
    {
        $key = self::getKey();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher));
        $encrypted = openssl_encrypt($data, self::$cipher, $key, 0, $iv);
        
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($data)
    {
        $key = self::getKey();
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, self::$cipher, $key, 0, $iv);
    }

    public static function isEqual($data, $encryptedData)
    {
        return $data === self::decrypt($encryptedData);
    }
}
