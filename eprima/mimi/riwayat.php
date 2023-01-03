<?php
$url = "https://simpeg-api.jogjaprov.go.id/pres/riwayat";
$tai = file_get_contents(".asuik", true);

$a = '{"token';
$b = '":"';
$c = '","tgl_awal":"2021-12-31","tgl_akhir":"2023-12-31"}';
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
// var_dump($resp);
$cek = json_decode($resp, true);
// print_r($cek);

$data = $cek['data'];

?>

<!DOCTYPE html>
<html>
	<head>
		<title> Riwayat Presensi </title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="Riwayat Presensi">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col">
					<table class="table table-hover">
						<thead>
							<tr>
							    <th>Nomor</th>
								<th>Tanggal</th>
								<th>Waktu</th>
								<th>Keterangan</th>
								<th>Jenis</th>
								<th>Status</th>
								<th>Foto</th>
								<th> <a href="index.php" class="btn btn-info" role="button">Home</a></th>
							</tr>
						</thead>
						<tbody>
							
							<?php foreach($data as $key => $value): 
							$lokasi = "https://simpeg-api.jogjaprov.go.id/pub/pres_foto?file-foto=";
							$filex = $value['foto'];
							?>
							<tr class="<?php 
										if($key%2 ==0) echo "table-info";
										elseif($key%2 ==1) echo "table-warning";?>">
								<td><?php echo $key+1; ?></td>
								<td><?php echo $value['tanggal']; ?></td>
								<td><?php echo $value['waktu']; ?></td>
								<td><?php echo $value['kat']; ?></td>
								<td><?php echo $value['jenis']; ?></td>
								<td><?php echo $value['pres_stat']; ?></td>
								<td><?php echo '<img src="'.$lokasi.$filex.'">'; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
