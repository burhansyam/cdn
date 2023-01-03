<?php

$url = "https://simpeg-api.jogjaprov.go.id/auth2/login";
$data = '{"username":"3403104805850006","password":"3403104805850006","antarmuka":"memayu"}';
$leng = strlen($data);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_ENCODING , "gzip");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "user-agent: Dart/2.12 (dart:io)",
   "Content-Type: text/plain; charset=utf-8",
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
// print_r($decode);

$data = $decode['data'];
curl_close($curl);


$toket = $decode['data']['token'];
$file = fopen(".asuik","w+");
$simpen = fwrite($file,"$toket");
?>

<!DOCTYPE html>
<html>
	<head>
		<title> E Prima </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.5">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	<body>

		<div class="container">
		      <h2><?php echo $data['nama_with_title']; ?></h2>
<p><?php echo $data['unor']; ?> </p> 
		                       <p>ID Lokasi : <?php echo $data['lokasi'][0]['lokasi_id']; ?> | Koordinat = lat : <?php echo $data['lokasi'][0]['lat']; ?> long : <?php echo $data['lokasi'][0]['long']; ?> | Radius : <?php echo $data['lokasi'][0]['radius']; ?>m  | <?php echo $data['jenis_pegawai']; ?> 
		                       <p>waktu server : </p>
                 <h2><?php echo $data['clock']; ?></h2>
			<div class="row">
				<div class="col">
					<table class="table table-hover">
						<thead>
							<tr>
							    <th>Jam Berangkat <?php echo $data['jadwal_berangkat']; ?></th>
							    <th>:</th>
								<th>Jam Pulang <?php echo $data['jadwal_pulang']; ?></th>
							</tr>
							
							<tr>
							    <th> <a href="datang.php" class="btn btn-success" role="button">Berangkat</a></th>
							    <th> <a href="riwayat.php" class="btn btn-warning" role="button">Riwayat</a></th>
								<th> <a href="pulang.php" class="btn btn-danger" role="button">Pulang</a></th>
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
