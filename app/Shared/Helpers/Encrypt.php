<?php

namespace App\Shared\Helpers;

use Exception;

final class Encrypt
{

  public static function encrypt($var, $httpQuery = true)
  {
    $method = "AES-256-CBC";
    $iv = openssl_random_pseudo_bytes(16);
    // $iv = openssl_random_pseudo_bytes($method);
    $param = openssl_encrypt($var, $method, env('API_KEY'), 0, $iv);
    $ivBase64 = base64_encode($iv);
    $parametrosGET = http_build_query(['token' => $param, 'iv' => $ivBase64]);

    if ($httpQuery) {
      return $parametrosGET;
    }

    if (!$httpQuery) {
      return ['token' => $param, 'iv' => $ivBase64];
    }
  }

  public static function decrypParams($data)
  {
    $method = "AES-256-CBC";
    $iv = base64_decode($data['iv']);
    // try {
    $value = openssl_decrypt($data['token'], $method, env('API_KEY'), 0, $iv);
    // } catch (Exception $e) {
    //   $value = [
    //     "status" => "401",
    //     "message" => "Las variables no cumplen las condiciones o son incorrectas"
    //   ];
    // }

    return $value;
  }

  public static function secure_encrypt($data)
  {
    $first_key = base64_decode('Lk5Uz3slx3BrAghS1aaW5AYgWZRV0tIX5eI0yPchFz4=');
    $second_key = base64_decode('EZ44mFi3TlAey1b2w4Y7lVDuqO+SRxGXsa7nctnr/JmMrA2vN6EJhrvdVZbxaQs5jpSe34X3ejFK/o9+Y5c83w==');

    $method = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);

    $first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, $iv);
    $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

    $output = base64_encode($iv . $second_encrypted . $first_encrypted);
    $encryptedData = strtr($output, ['+' => '-', '/' => '_']);
    return $encryptedData;
  }

  public static function secured_decrypt($input)
  {
    $urlSafeData = strtr($input, ['-' => '+', '_' => '/']);
    $first_key = base64_decode('Lk5Uz3slx3BrAghS1aaW5AYgWZRV0tIX5eI0yPchFz4=');
    $second_key = base64_decode('EZ44mFi3TlAey1b2w4Y7lVDuqO+SRxGXsa7nctnr/JmMrA2vN6EJhrvdVZbxaQs5jpSe34X3ejFK/o9+Y5c83w==');
    $mix = base64_decode($urlSafeData);

    $method = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($method);

    $iv = substr($mix, 0, $iv_length);
    $second_encrypted = substr($mix, $iv_length, 64);
    $first_encrypted = substr($mix, $iv_length + 64);

    $data = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);
    $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

    if (hash_equals($second_encrypted, $second_encrypted_new))
      return $data;

    return false;
  }
}
