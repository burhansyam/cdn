<?php
date_default_timezone_set("Asia/Jakarta");

$dev = isset($_GET['id']) ? $_GET['id'] : '';
$siap = isset($_GET['ayo']) ? $_GET['ayo'] : '';

#$jancik = file_get_contents("https://mpresensi.gunungkidulkab.go.id/api/index.php/cekdevice/$dev",true);

$asal = "https://mpresensi.gunungkidulkab.go.id/api/index.php/cekdevice/$dev";
$ch = curl_init($asal);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
$customHeaders = array(
    'Host: mpresensi.gunungkidulkab.go.id',
    'Connection: keep-alive',
    'Accept: application/json, text/javascript, */*; q=0.01',
    'User-Agent: Mozilla/5.0 (Linux; Android 7.1.2; Redmi 5A Build/N2G47H; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/84.0.4147.125 Mobile Safari/537.36',
    'X-Requested-With: id.go.gunungkidul.mobsi',
    'Sec-Fetch-Site: cross-site',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Dest: empty',
    'Accept-Encoding: gzip, deflate',
    'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $customHeaders);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$jancik = curl_exec($ch);


$jancik = str_replace('{"message":"success","data":','', $jancik);
$jancik = str_replace(',"result":"ready"}','', $jancik);

$cek = json_decode($jancik, true);

// print_r ($jancik);

$digits = 2;
$a = (rand(5,25));
$b = (rand(5,25));
$c = (rand(5,25));
$m = "ﾠm";
$jarak = "$c$m";

$nama = $cek['namapns'];
$nip = $cek['biodata_nip'];
$token = $cek['token'];
$opd = $cek['namalokasi'];
$lat = $cek['lat'];
$lon = $cek['lon'];
$jam = $cek['saiki'];


$urlx = "https://mpresensi.gunungkidulkab.go.id/api/index.php/presensi/$nip/$siap/$lat$a,$lon$b/$jarak/-/$token";

$ch2 = curl_init($urlx);
curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "POST");
$customHeaders = array(
    'Host: mpresensi.gunungkidulkab.go.id',
    'Connection: keep-alive',
    'Content-Length: 7',
    'Accept: */*',
    'User-Agent: Mozilla/5.0 (Linux; Android 7.1.2; Redmi 5A Build/N2G47H; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/84.0.4147.125 Mobile Safari/537.36',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Origin: mpresensi.gunungkidulkab.go.id',
    'X-Requested-With: id.go.gunungkidul.mobsi',
    'Sec-Fetch-Site: cross-site',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Dest: empty',
    'Referer: https://mpresensi.gunungkidulkab.go.id',
    'Accept-Encoding: gzip, deflate',
    'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7'
);
curl_setopt($ch2, CURLOPT_HTTPHEADER, $customHeaders);
curl_setopt($ch2, CURLOPT_POSTFIELDS, 
                              "value=1");
// curl_setopt($ch2, CURLOPT_REFERER, 'https://mpresensi.gunungkidulkab.go.id/');
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, FALSE); 
$response = curl_exec($ch2);
$lap = json_decode($response, true);
$pesan = $lap['message'];

// echo $crutx =  " Mobsi $siap Atas Nama : $nama dengan NIP :$nip telah $pesan dari jarak : $jarak dengan titik koordinat :$lat$a,$lon$b tercatat di OPD :$opd pada pukul :$jam";


echo "{\"result\":{\"presensi\":\"$siap\",\"status\":\"$pesan\",\"waktu\":\"$jam\",\"nama\":\"$nama\",\"nip\":\"$nip\",\"opd\":\"$opd\",\"jarak\":\"$c m\",\"koordinat\":\"$lat$a,$lon$b\"}}";

curl_close($ch2);
?>
