<?php
$bun = isset($_GET['blk']) ? $_GET['blk'] : 'UD';
$bun = strtolower($bun);
$nom = isset($_GET['no']) ? $_GET['no'] : '1056';
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://samsatsleman.jogjaprov.go.id//cek/pages/getpajak');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "nomer=$nom&kode_belakang=$bun");

$headers = array();
$headers[] = 'Accept: text/html, */*; q=0.01';
$headers[] = 'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
$headers[] = 'Cookie: _ga=GA1.1.1149497912.1720610866; _ga_LFQNSB7LN4=GS1.1.1720610866.1.1.1720611390.0.0.0';
$headers[] = 'Origin: https://samsatsleman.jogjaprov.go.id';
$headers[] = 'Referer: https://samsatsleman.jogjaprov.go.id/cek/pajak';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Sec-Ch-Ua: \"Not)A;Brand\";v=\"99\", \"Google Chrome\";v=\"127\", \"Chromium\";v=\"127\"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$hasil = curl_exec($ch);
$hasil = str_replace('<table class="table table-bordered">','',$hasil);
$hasil = str_replace('<tr>','',$hasil);
$hasil = str_replace('<td colspan="2" class="text-center">DATA KENDARAAN</td>','',$hasil);
$hasil = str_replace('</tr>','',$hasil);
$hasil = str_replace('<td>','',$hasil);
$hasil = str_replace('<b>','',$hasil);
$hasil = str_replace('</b>','',$hasil);
$hasil = str_replace('</table>','',$hasil);
$hasil = str_replace('</td>','',$hasil);
$hasil = str_replace('<td align="center">','',$hasil);
$hasil = trim(preg_replace('/\s+/', ' ', $hasil));

$hasil = str_replace('Nopol','\n🚧 Nopol : ',$hasil);
$hasil = str_replace('Merk','\n⛽️ Merk : ',$hasil);
$hasil = str_replace('Model','\n🛵 Model : ',$hasil);
$hasil = str_replace('Tahun','\n🚏 Tahun : ',$hasil);
$hasil = str_replace('POKOK','POKOK : ',$hasil);
$hasil = str_replace('DENDA','DENDA : ',$hasil);
$hasil = str_replace('TOTAL PAJAK','\n💰 TOTAL PAJAK : ',$hasil);
$hasil = str_replace('PKB','\n💸 PKB',$hasil);
$hasil = str_replace('SWDKLLJ','\n💵 SWDKLLJ',$hasil);
$hasil = str_replace('TGL AKHIR \n💸 PKB','\n📆 TGL AKHIR PKB : ',$hasil);

    echo "{\"result\":\"*Rincian Data Kendaraan*$hasil\"}";
?>
