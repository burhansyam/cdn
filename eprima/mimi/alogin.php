<?php


$url = "https://simpeg-api.jogjaprov.go.id/auth";
$data = '{"username":"3403104805850006","password":"3403104805850006","imei":"93081be6-2cd5-412d-8060-d62f2e2b71e5","andid":"a559fb6f00664723","apk_version":"1.2.0+3","inc_version":"4"}';
$leng = strlen($data);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING , "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "user-agent: Dart/2.13 (dart:io)",
   "Content-Type: application/json",
   "accept-encoding: gzip",
   "content-length:" . " " . $leng,
   "host: simpeg-api.jogjaprov.go.id",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
$decode = json_decode($resp, TRUE);
$toket = $decode['data']['token'];
print_r($decode);
curl_close($curl);
$file = fopen(".asuik","w+");
$simpen = fwrite($file,"$toket");
?>

