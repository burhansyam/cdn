<?php
$url = "https://simpeg-api.jogjaprov.go.id/auth/cek_token";
$tai = file_get_contents(".asuik", true);

$a = '{"token';
$b = '":"';
$c = '"}';
$kode = "$a$b$tai$c";
$datapost = "$kode";
$leng = strlen($datapost);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "user-agent: Dart/2.13 (dart:io)",
   "Content-Type: application/json",
   "accept-encoding: gzip",
   "content-length:" . " " . $leng,
   "host: simpeg-api.jogjaprov.go.id",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, $datapost);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_ENCODING , "gzip");
$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);

?>
