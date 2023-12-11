<?php

$chan = isset($_GET['ch']) ? $_GET['ch'] : '';

// https://cdn3-reg2.mm.oxygen.id/hls/nettv-hd/index.m3u8

$ambil = file_get_contents("https://cdn3-reg2.mm.oxygen.id/hls/$chan/index.m3u8",true);

$filter = str_replace(":MTExMTExMTExMTExMTExMQ==","",$ambil);

$encryption = "$filter";

// Store the cipher method
$ciphering = "AES-128-CBC";
 
// Use OpenSSl Encryption method
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;

$decryption_iv = '1111111111111111';
 
// decryption key
$decryption_key = "oxygenmultimedia";
 
// Use openssl_decrypt() function to decrypt the data
$decryption=openssl_decrypt ($encryption, $ciphering, 
        $decryption_key, $options, $decryption_iv);
 
// decrypted string
$decryption;
echo $final = str_replace("2023","https://cdn3-reg2.mm.oxygen.id/hls/$chan/2023",$decryption);

?>
