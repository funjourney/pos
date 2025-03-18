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
     * @return string Hasil enkripsi dalam format Hex
     * @throws Exception
     */
    public static function encrypt($keyInput, $plaintextInput)
    {
        if (strlen($keyInput) < 16) {
            throw new Exception("Key must be at least 16 characters");
        }

        $key = substr($keyInput, 0, 32);
        $plaintext = self::pkcs7Padding($plaintextInput);

        $iv = random_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($ciphertext === false) {
            throw new Exception("Encryption failed");
        }

        // Menggunakan Hex agar aman di URL
        $finalCiphertext = bin2hex($iv . $ciphertext);

        // Log::info("Encryption Debug:", [
        //     'key' => bin2hex($key),
        //     'iv' => bin2hex($iv),
        //     'plaintext' => $plaintextInput,
        //     'padded_plaintext' => bin2hex($plaintext),
        //     'ciphertext' => bin2hex($ciphertext),
        //     'final_ciphertext' => $finalCiphertext,
        // ]);

        return $finalCiphertext;
    }

    /**
     * Dekripsi teks yang telah dienkripsi menggunakan AES-CBC dengan IV
     *
     * @param string $keyInput Kunci dekripsi (16 atau 32 karakter)
     * @param string $ciphertextInput Ciphertext dalam format Hex
     * @return string Hasil dekripsi dalam bentuk string
     * @throws Exception
     */
    public static function decrypt($keyInput, $ciphertextInput)
    {
        if (strlen($keyInput) < 16) {
            throw new Exception("Key must be at least 16 characters");
        }

        $key = substr($keyInput, 0, 32);
        
        // Dekode dari Hex ke Binary
        $ciphertextDecoded = hex2bin($ciphertextInput);

        if (!$ciphertextDecoded) {
            throw new Exception("Invalid ciphertext format");
        }

        $ivLength = openssl_cipher_iv_length('AES-256-CBC');
        if (strlen($ciphertextDecoded) < $ivLength) {
            throw new Exception("Ciphertext too short");
        }

        $iv = substr($ciphertextDecoded, 0, $ivLength);
        $ciphertext = substr($ciphertextDecoded, $ivLength);

        // Log::info("Decryption Debug:", [
        //     'key' => bin2hex($key),
        //     'iv' => bin2hex($iv),
        //     'ciphertext' => bin2hex($ciphertext),
        // ]);

        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new Exception("Decryption failed: " . openssl_error_string());
        }

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
        if ($length == 0) {
            throw new Exception("Decrypted data is empty");
        }

        $padding = ord($data[$length - 1]); // Ambil nilai padding terakhir

        // Validasi padding
        if ($padding > 16 || $padding > $length) {
            throw new Exception("Invalid PKCS7 padding");
        }

        // Pastikan semua karakter padding benar
        for ($i = 1; $i <= $padding; $i++) {
            if (ord($data[$length - $i]) !== $padding) {
                throw new Exception("Corrupt padding detected");
            }
        }

        return substr($data, 0, -$padding);
    }
}