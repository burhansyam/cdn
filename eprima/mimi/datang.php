<?php
// include 'cek.php';

$url = "https://simpeg-api.jogjaprov.go.id/pres/do";
$tai = file_get_contents(".asuik", true);
$a = '{"token';
$b = '":"';
$c = '"';
$d = ',"imei":"93081be6-2cd5-412d-8060-d62f2e2b71e5","plat":"ANDROID","andid":"a559fb6f00664723","lat":"-7.9668223","long":"110.6003809","kat":"datang","jenis":"normal","catatan":"","foto":"';
$file = "mimi.txt";
$file_arr = file($file);
$num_lines = count($file_arr);
$last_arr_index = $num_lines - 1;
$rand_index = rand(0, $last_arr_index);
$acakadut = $file_arr[$rand_index];
$foto = preg_replace('/\s/', '', $acakadut);
$e = '","flag_wfh":0}';
$kode = "$a$b$tai$c$d$foto$e";

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
$data = json_decode($resp, TRUE);
// $data = $decode['data'];
// print_r ($data);
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Datang | E Prima </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.5">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	<body>

		<div class="container">
		      <p>Laporan Presensi Kehadiran </p>
		  <blink>    <h2><?php echo $data['pesan']['deskripsi']; ?> </h2></blink> 
		                      
			<div class="row">
				<div class="col">
					<table class="table table-hover">
						<thead>
							
							<tr>
							    <th> <a href="index.php" class="btn btn-info" role="button">Home</a></th>
							    <th> <a href="riwayat.php" class="btn btn-warning" role="button">Riwayat</a></th>
								<th> <a href="#" class="btn btn-danger" role="button">Pulang</a></th>
							</tr>
									
							
							
						</thead>
						<tbody>
							

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>