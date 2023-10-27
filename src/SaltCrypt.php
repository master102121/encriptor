<?php
namespace Master102121\Encriptor;

class SaltCrypt {
  private $key = 'CUSTOM_MY_SALT_KEY';
  private $plaintext = "String_to_be_encrypted";
  
  public function __construct($key, $plaintext) {
    $this->key = $key;
    $this->plaintext = $plaintext;
  }
  
  public function encrypt(){
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($this->plaintext, $cipher, $this->key, $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary=true);
    return base64_encode($iv.$hmac.$ciphertext_raw);
  }
  
  
  public function decrypt($ciphertext){
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->key, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary=true);
    
    if(hash_equals($hmac, $calcmac)){
      return $original_plaintext;
    }else{
      return 'Chave incorreta!';
    }
  }
}
