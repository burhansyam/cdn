<?php
require('/home/beetvmyi/api.beetv.my.id/bot/toto/dom/simple_html_dom.php');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://gilabola.com/internasional/jadwal-bola-malam-ini/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7';
$headers[] = 'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7';
$headers[] = 'Cache-Control: max-age=0';
$headers[] = 'Cookie: _ga=GA1.2.1948664559.1720957853; _gid=GA1.2.105900301.1720957853; _ga_DJX1DWTQYG=GS1.2.1720957853.1.1.1720958027.0.0.0';
$headers[] = 'Priority: u=0, i';
$headers[] = 'Sec-Ch-Ua: \"Not/A)Brand\";v=\"8\", \"Chromium\";v=\"126\", \"Google Chrome\";v=\"126\"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
$headers[] = 'Sec-Fetch-Dest: document';
$headers[] = 'Sec-Fetch-Mode: navigate';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'Sec-Fetch-User: ?1';
$headers[] = 'Upgrade-Insecure-Requests: 1';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$jalan = curl_exec($ch);



$dom = new DomDocument;
$internalErrors = libxml_use_internal_errors(true);
$dom->loadHTML($jalan);
libxml_use_internal_errors($internalErrors);
$tags = $dom->getElementsByTagName('input');
for($i = 0; $i < $tags->length; $i++) {
	$grab = $tags->item($i);
	if($grab->getAttribute('type') === 'hidden') {
		$token = $grab->getAttribute('value'); 
	}
}


$html = new simple_html_dom();
$html->load($jalan);
$table = $html->find('table', 0);
$asu = $table;

$asu = str_replace("<tbody>","", $asu);
$asu = str_replace('<table border="1">','', $asu);
$asu = str_replace('<thead>','', $asu);
$asu = str_replace(', ',' ', $asu);
$asu = str_replace('<b>','', $asu);
$asu = str_replace('</b>','', $asu);
$asu = str_replace('<br>','', $asu);
$asu = str_replace('<br/>','', $asu);
$asu = str_replace("Liga Jerman","Liga Jerman: ", $asu);
$asu = str_replace("Liga Italia","Liga Italia: ", $asu);
$asu = str_replace("Liga Spanyol","Liga Spanyol: ", $asu);
$asu = str_replace("Liga Australia","Liga Australia: ", $asu);
$asu = str_replace("Liga 1 Indonesia","Liga 1 Indonesia: ", $asu);
$asu = str_replace("Liga Inggris","Liga Inggris: ", $asu);
$asu = str_replace("Liga Belanda","Liga Belanda: ", $asu);
$asu = str_replace("Liga Perancis","Liga Perancis: ", $asu);
$asu = str_replace("Saudi Pro League","Saudi Pro League: ", $asu);
$asu = str_replace("Liga Champions Asia Elite","Liga Champions Asia Elite: ", $asu);
$asu = str_replace("DFB-Pokal","DFB-Pokal: ", $asu);
$asu = str_replace("Coppa Italia","Coppa Italia: ", $asu);
$asu = str_replace("Copa Libertadores","Copa Libertadores: ", $asu);
$asu = str_replace("Copa del Rey","Copa del Rey: ", $asu);
$asu = str_replace("ASEAN Club Championship","ASEAN Club Championship: ", $asu);
$asu = str_replace("Piala FA","Piala FA: ", $asu);
$asu = str_replace("</tbody>","", $asu);

$asu;

$time_start = microtime(true);

$All = [];

$jsonData   = $asu;

//echo $jsonData;

$dom = new DOMDocument;
$dom->loadHTML($jsonData);

$tables = $dom->getElementsByTagName('table');
$tr     = $dom->getElementsByTagName('tr'); 

foreach ($tr as $data) {        
    for ($i = 0; $i < count($data); $i++) {

        //Not able to fetch the user's link :(

        $hari      = $data->getElementsByTagName('td')->item(0)->textContent;                  // To fetch name
        $liga     = $data->getElementsByTagName('td')->item(1)->textContent;                  // To fetch height
        $chanel     = $data->getElementsByTagName('td')->item(2)->textContent;                  // To fetch weight

        array_push($All, array(
            "hari"      => $hari,
            "liga"    => $liga,
            "chanel"    => $chanel
        ));
    }
}



header("Access-Control-Allow-Origin: *");                                                                            
header('Content-Type: application/json');

echo $sogok = json_encode($All, JSON_PRETTY_PRINT);



?>
