Helper to encrypt strings with custom key with php

In composer
composer require master102121/encryptor
use Master102121\Encryptor\SaltCrypt;

$mykey = "mykey";
$secret = "password";
$scp = new SaltCrypt($mykey, $secret);
$encrypted = $scp->encrypt();

$decrypted = $scp->decrypt($mykey);
