<?php

namespace App\Security;

final class OpenSSL
{
    public const KEY = 'qwerty';

    public static function encrypt(string $plaintext): string
    {
        $ivLen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
        $iv = openssl_random_pseudo_bytes($ivLen);
        $ciphertextRaw = openssl_encrypt($plaintext, $cipher, self::KEY, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertextRaw, self::KEY, true);

        return base64_encode( $iv.$hmac.$ciphertextRaw);
    }

    public static function decrypt(string $ciphertext): ?string
    {
        $c = base64_decode($ciphertext);
        $ivLen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivLen);
        $hmac = substr($c, $ivLen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivLen+$sha2len);
        $originalPlaintext = openssl_decrypt($ciphertext_raw, $cipher, self::KEY, OPENSSL_RAW_DATA, $iv);
        $calcMac = hash_hmac('sha256', $ciphertext_raw, self::KEY, true);

        // timing attack safe comparison
        if (hash_equals($hmac, $calcMac)) {
            return $originalPlaintext;
        }

        return null;
    }
}
