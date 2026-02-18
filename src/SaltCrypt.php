<?php
namespace Master102121\Encriptor;

class SaltCrypt {
  private $key;
  private $plaintext;
  private $cipher = "AES-128-CBC";
  
  public function __construct($key, $plaintext = "") {
    $this->key = $key;
    $this->plaintext = $plaintext;
  }
  
  public function encrypt() {
    $ivlen = openssl_cipher_iv_length($this->cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    
    // Removido o "=" dentro da função para evitar o Deprecated
    $ciphertext_raw = openssl_encrypt($this->plaintext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
    
    $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, true);
    return base64_encode($iv . $hmac . $ciphertext_raw);
  }
  
  public function decrypt($ciphertext) {
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length($this->cipher);
    
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, 32); // sha2len é 32
    $ciphertext_raw = substr($c, $ivlen + 32);
    
    $original_plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, true);
    
    if (hash_equals($hmac, $calcmac)) {
      return $original_plaintext;
    }
    
    return 'Chave incorreta!';
  }
}
