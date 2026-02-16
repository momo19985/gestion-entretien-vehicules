<?php

/*
 * PHP OpenSSL - Class to provide 2 way encryption of data
 */

class Crypt {

    private $secretkey = 'esqesqqsfqzgeg';
    private $cipher = 'AES-128-ECB';

    //Encrypts a string
    public function encrypt($text) {
        // Pad key to 16 bytes (AES-128)
        $key = str_pad($this->secretkey, 16, "\0");
        $data = openssl_encrypt($text, $this->cipher, $key, OPENSSL_RAW_DATA);
        return base64_encode($data);
    }

    //Decrypts a string
    public function decrypt($text) {
        $key = str_pad($this->secretkey, 16, "\0");
        $text = base64_decode($text);
        return openssl_decrypt($text, $this->cipher, $key, OPENSSL_RAW_DATA);
    }

}

?>