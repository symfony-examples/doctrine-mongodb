<?php

namespace App\Security;

use App\Exception\RuntimeScriptException;

final class OpenSSL
{
    public const KEY = 'qwerty';
    public const CIPHER = 'AES-128-CBC';
    public const HASH_ALGO = 'sha256';

    public static function encrypt(string $plaintext): string
    {
        $ivLen = openssl_cipher_iv_length(self::CIPHER);

        if (!is_int($ivLen)) {
            throw new RuntimeScriptException('Invalid type of ivLen');
        }

        $iv = openssl_random_pseudo_bytes($ivLen);
        $ciphertextRaw = openssl_encrypt(
            $plaintext,
            self::CIPHER,
            self::KEY,
            OPENSSL_RAW_DATA, $iv
        );

        if (!is_string($ciphertextRaw)) {
            throw new RuntimeScriptException('Invalid type of ciphertextRaw');
        }

        $hmac = hash_hmac(self::HASH_ALGO, $ciphertextRaw, self::KEY, true);

        return base64_encode($iv.$hmac.$ciphertextRaw);
    }

    public static function decrypt(string $ciphertext): ?string
    {
        try {
            $c = base64_decode($ciphertext);
            $ivLen = openssl_cipher_iv_length(self::CIPHER);

            if (!is_int($ivLen)) {
                throw new RuntimeScriptException('Invalid type of ivLen');
            }

            $iv = substr($c, 0, $ivLen);
            $hmac = substr($c, $ivLen, $sha2len = 32);
            $ciphertextRaw = substr($c, $ivLen + $sha2len);
            $originalPlaintext = openssl_decrypt(
                $ciphertextRaw,
                self::CIPHER,
                self::KEY,
                OPENSSL_RAW_DATA,
                $iv
            );

            if (false === $originalPlaintext) {
                throw new RuntimeScriptException('Decrypt failed');
            }

            $calcMac = hash_hmac(self::HASH_ALGO, $ciphertextRaw, self::KEY, true);

            // timing attack safe comparison
            if (hash_equals($hmac, $calcMac)) {
                return $originalPlaintext;
            }

            return null;
        } catch (RuntimeScriptException) {
            return null;
        }
    }
}
