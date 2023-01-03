<?php
$url = "https://simpeg-api.jogjaprov.go.id/pres/do";
$tai = file_get_contents(".asuik", true);
$a = '{"token';
$b = '":"';
$c = '"';
$d = ',"imei":"93081be6-2cd5-412d-8060-d62f2e2b71e5","plat":"ANDROID","andid":"a559fb6f00664723","lat":"-7.9668223","long":"110.6003809","kat":"pulang","jenis":"normal","catatan":"","foto":"/9j/4AAQSkZJRgABAQEAYABgAAD//gA7Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBxdWFsaXR5ID0gOTAK/9sAQwADAgIDAgIDAwMDBAMDBAUIBQUEBAUKBwcGCAwKDAwLCgsLDQ4SEA0OEQ4LCxAWEBETFBUVFQwPFxgWFBgSFBUU/9sAQwEDBAQFBAUJBQUJFA0LDRQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQU/8AAEQgACgBaAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A/R74veO9W+HXg2TWNG8NXHii8WaOL7LCLgrErHmSRbaC4uCvAXEFvM250LKkYklj+dNZ/b+uNL8P6FrK/D7NpqPhltekguNUmjnRx4fm1lAmLNomtnED2qTvJG7zQXYSFhbSGvqrxT4T0PxzoV1oniTRtP8AEGi3W37Rp2qWqXNvNtYOu+NwVbDKrDI4Kg9RWtQB8a+MP22/EXhH46aJ4W1Xwxb6I8Ph/UtQvvDt7qgjn1dkv4LSAaXvthJd3by2upC2tQIluYZraUujOY4eq8V/to33hr4jan4ah+HlxqaW8wtreKLUWg1CWUazp2lmN4poEt42k/tKO6hX7UxeBraSTyFuY2H1BRQBk+FtR1TVdCtbjWtI/sLVTuS5sVuVuUR1YqWjlXG+Jtu9GZUcoy74433IvzW/7Rni/wAL/s2fGHxF4xi0/TPGfg691YPFHqZtkhgN5OLKJLqfT/J+0+T5fkRtDIZkexlkVReLj6qooA+X/iR+1ffaZ8WvCOkeGtLuNZ8MedcXl1Loyte3uv2o8P3uoQCxiWExPBM6RrDN9pjeaa0uYkjZIpXHK2/7bfiK78TXV8fDFvNoWh6Z4hfUk0TVBc6bItpBoV1Dqpuri2gnNosWpTDNtBO0iuJIY7lTGa+yqKAOU+KXjr/hWvgTU/EX2H7f9k8pdjy+TBF5kqR+fcTbW8m2i3+bNNtbyoY5X2ts2n5q1L9svxJ4j0TwGmm+F/8AhF9R1nxNYafqEtzJcvsgHiU6XcpbJLYq0nywMs5uFtDbfb7NctNNGlfYFFAHinwD/aLuPjb4q8WaTN4Z/wCEfj0ey0/UIGe6mknZLp7tDBcRyW8SxXMDWbxzRxPOkcwliMheFwPa6KKAP//Z","flag_wfh":0}';
$kode = "$a$b$tai$c$d";
// $kode = "$a$b$tai$c$d";

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
$data = json_decode($resp, TRUE);

?>

<!DOCTYPE html>
<html>
	<head>
		<title> Pulang | E Prima </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.5">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	<body>

		<div class="container">
		      <p>Laporan Presensi Pulang </p>
		  <blink>    <h2><?php echo $data['pesan']['deskripsi']; ?> </h2></blink> 
		                      
			<div class="row">
				<div class="col">
					<table class="table table-hover">
						<thead>
						
							<tr>
							    <th> <a href="datang.php" class="btn btn-info" role="button">Datang</a></th>
							    <th> <a href="riwayat.php" class="btn btn-warning" role="button">Riwayat</a></th>
								<th> <a href="index.php" class="btn btn-danger" role="button">Home</a></th>
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
