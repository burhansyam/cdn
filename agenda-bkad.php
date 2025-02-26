<?php

header("refresh: 300"); 
// URL ke Google Spreadsheet yang sudah dipublish
$spreadsheet_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSv3YhTQ2dza-F2E23SHc1cEHq3P5yaw0kJftTRGfHJvFKd0DS33iowWo2TpVdy2H_7X6q2m8wSzBXe/pub?output=csv';


// Mengambil data dari Google Spreadsheet
if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
    $agenda = [];
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $agenda[] = $data;
    }
    fclose($handle);
} else {
    die("Gagal mengambil data dari Google Spreadsheet.");
}

// Menghapus header dari array
$header = array_shift($agenda);

// Mengurutkan agenda berdasarkan Tanggal dan Jam/Waktu (terbaru di atas)
usort($agenda, function($a, $b) {
    // Gabungkan Tanggal dan Jam/Waktu untuk pengurutan
    $dateTimeA = strtotime($a[8] . ' ' . $a[9]);
    $dateTimeB = strtotime($b[8] . ' ' . $b[9]);

    // Urutkan dari yang terbaru ke terlama
    return $dateTimeB - $dateTimeA;
// print_r($joh);
    
});

// Paginasi
$rows_per_page = 30; // Jumlah baris per halaman
$total_rows = count($agenda); // Total data
$total_pages = ceil($total_rows / $rows_per_page); // Total halaman

// Ambil nomor halaman dari URL (default: halaman 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $total_pages)); // Pastikan halaman valid

// Hitung offset data
$offset = ($page - 1) * $rows_per_page;
$paginated_data = array_slice($agenda, $offset, $rows_per_page);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Kegiatan Pimpinan BKAD</title>
    <style>
        /* Background animasi gelembung air */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(-45deg, #1e3c72, #2a5298, #1c92d2, #7db9e8);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            overflow-x: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .bubbles {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            overflow: hidden;
        }

        .bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.2);
            bottom: -150px;
            animation: bubble 25s infinite;
            border-radius: 50%;
        }

        .bubbles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .bubbles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .bubbles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .bubbles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .bubbles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .bubbles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .bubbles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .bubbles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .bubbles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .bubbles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

        @keyframes bubble {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }

        /* Container dan Tabel */
        .container {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 12px;
            text-align: left;
        }

        th {
            padding: 12px;            
            background-color: #2a5298;
            color: #fff;
            font-weight: bold;
            text-align: center;            
        }

        tr:nth-child(even) {
            background-color: rgba(242, 242, 242, 0.8);
        }

        tr:hover {
            background-color: rgba(221, 221, 221, 0.8);
        }

        td {
            color: #333;
        }

        /* Paginasi */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            background-color: #2a5298;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #1c92d2;
        }

        .pagination a.active {
            background-color: #1c92d2;
            cursor: default;
        }        
    </style>
</head>
<body>
    <!-- Background gelembung air -->
    <ul class="bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>

    <!-- Container untuk tabel -->
    <div class="container">
        <h1>ðŸ“‘ Agenda Kegiatan Pimpinan BKAD</h1>
        <table>
            <thead>
                <tr><center>
                    <th>No</th>
                    <th>Asal Surat</th>
                    <th>Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Tempat</th>
                    <th>Keterangan</th>
               </center> </tr>
            </thead>
            <tbody>
                <?php foreach ($agenda as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row[0]); ?></td>
                    <td><?php echo htmlspecialchars($row[3]); ?></td>
                    <td><?php echo htmlspecialchars($row[2]); ?></td>
                    <td><?php echo htmlspecialchars($row[9]); ?></td>
                    <td><?php echo htmlspecialchars($row[8]); ?></td>
                    <td><?php echo htmlspecialchars($row[6]); ?></td>
                    <td><?php echo htmlspecialchars($row[7]); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <!-- Paginasi -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>"><<=</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" <?php echo ($i == $page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">=>></a>
            <?php endif; ?>
        </div>
    </div>    
</body>
</html>
