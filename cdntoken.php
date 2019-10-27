<?php

// Source: https://stackoverflow.com/questions/19934422/encrypt-string-in-php-and-decrypt-in-node-js

function getUrl()
{
    $temp = explode("input=", $_SERVER["REQUEST_URI"])[1];
    return rawurldecode($temp);
}


//------------------ Encryption --------------------


date_default_timezone_set('UTC');
$textToEncrypt = getUrl();
$encryptionMethod = "AES-256-CBC";
$secret = "im72charPasswordofdInitVectorStm"; //must be 32 char length
$iv = substr($secret, 0, 16);


//------------------End Encryption --------------------

function curPageURL() {
    $pageURL = 'http';
    if(isset($_SERVER["HTTPS"]))
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
// http://103.47.132.164/PLTV/88888888/224/3221227035/index.m3u8
// echo "http://api.ipify.org?authenticationtoken="."\n<br>";

$encryptedMessage = str_replace('/', '$', str_replace('+', '@', openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv)))  ;
// $decryptedMessage = openssl_decrypt($encryptedMessage, $encryptionMethod, $secret,0,$iv);

echo "$encryptedMessage";

?>