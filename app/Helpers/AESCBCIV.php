<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;

class AESCBCIV
{
    /**
     * Enkripsi teks menggunakan AES-CBC dengan IV
     *
     * @param string $keyInput Kunci enkripsi (16 atau 32 karakter)
     * @param string $plaintextInput Teks yang akan dienkripsi
     * @return string Hasil enkripsi dalam format base64
     * @throws Exception
     */
    public static function encrypt($keyInput, $plaintextInput)
    {
        if (strlen($keyInput) < 16) {
            throw new Exception("Key must be at least 16 characters");
        }

        $key = substr($keyInput, 0, 32); // Menggunakan 128-bit atau 256-bit key
        $plaintext = self::pkcs7Padding($plaintextInput);

        $iv = random_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $ciphertext);
    }

    /**
     * Dekripsi teks yang telah dienkripsi menggunakan AES-CBC dengan IV
     *
     * @param string $keyInput Kunci dekripsi (16 atau 32 karakter)
     * @param string $ciphertextInput Ciphertext dalam format base64
     * @return string Hasil dekripsi dalam bentuk string
     * @throws Exception
     */
    public static function decrypt($keyInput, $ciphertextInput)
    {
        if (strlen($keyInput) < 16) {
            throw new Exception("Key must be at least 16 characters");
        }

        $key = substr($keyInput, 0, 32);
        $ciphertextDecoded = base64_decode($ciphertextInput);

        if (!$ciphertextDecoded || strlen($ciphertextDecoded) < openssl_cipher_iv_length('AES-256-CBC')) {
            throw new Exception("Ciphertext too short");
        }

        $ivLength = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($ciphertextDecoded, 0, $ivLength);
        $ciphertext = substr($ciphertextDecoded, $ivLength);

        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return self::pkcs7Unpadding($decrypted);
    }

    /**
     * Menambahkan padding PKCS7 ke plaintext sebelum dienkripsi
     *
     * @param string $data Data yang akan diproses
     * @return string Data dengan padding PKCS7
     */
    private static function pkcs7Padding($data)
    {
        $blockSize = 16;
        $padding = $blockSize - (strlen($data) % $blockSize);
        return $data . str_repeat(chr($padding), $padding);
    }

    /**
     * Menghapus padding PKCS7 setelah dekripsi
     *
     * @param string $data Data yang telah didekripsi
     * @return string Data tanpa padding
     */
    private static function pkcs7Unpadding($data)
    {
        $length = strlen($data);
        $padding = ord($data[$length - 1]);
        return substr($data, 0, -$padding);
    }
}
