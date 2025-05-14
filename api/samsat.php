<?php
// Kode Wilayah:
// - Banten: (kode: A, B)
// - DIY: (kode: AB)
// - Jawa Barat: (kode: D, Z, E, F, T)
// - Jawa Tengah: (kode: AD, H, R, K, AA, G)

require('/home/beetvmyi/api.beetv.my.id/bot/toto/dom/simple_html_dom.php');


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

/**
 * Fungsi untuk memeriksa informasi kendaraan berdasarkan kode wilayah.
 *
 * @param string $kode Kode wilayah kendaraan (misal: AB, AD, D).
 * @param string $nomor Nomor polisi kendaraan.
 * @param string $seri Seri atau akhiran nomor polisi kendaraan.
 * @return array Hasil pemeriksaan kendaraan dalam bentuk array.
 */
function checkVehicle(string $kode, string $nomor, string $seri): array
{
    $kode = strtoupper($kode); // Konversi ke uppercase untuk case-insensitivity
    $seri = strtoupper($seri); // Konversi ke uppercase untuk case-insensitivity
    switch ($kode) {
        case 'A':
        case 'B':
            return checkBantenVehicle($kode, $nomor, $seri);
        case 'AB':
            return checkDiyVehicle($seri, $nomor);
        case 'D':
        case 'Z':
        case 'E':
        case 'F':
        case 'T':
            return checkJabarVehicle($kode, $nomor, $seri);
        case 'AD':
        case 'H':
        case 'R':
        case 'K':
        case 'AA':
        case 'G':
            return checkJatengVehicle($kode, $nomor, $seri);
        default:
            return [
                'status' => false,
                'message' => 'Kode wilayah tidak dikenali.',
                'kode' => $kode,
            ];
    }
}

/**
 * Fungsi untuk memeriksa informasi kendaraan di Banten.
 *
 * @param string $kode Kode wilayah kendaraan.
 * @param string $nomor Nomor polisi kendaraan.
 * @param string $seri Seri atau akhiran nomor polisi kendaraan.
 * @return array Hasil pemeriksaan kendaraan.
 */
function checkBantenVehicle(string $kode, string $nomor, string $seri): array
{
    $url = 'https://infopkb.bantenprov.go.id/p_infopkb.php';
    $currentDate = date('d-m-Y');
    $postData = http_build_query([
        'kode' => strtoupper($kode),
        'nomor' => $nomor,
        'seri' => strtoupper($seri),
        'tgl' => $currentDate,
        'index' => 'index.php',
    ]);

    $headers = [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/png,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cache-Control: max-age=0',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://infopkb.bantenprov.go.id',
        'Referer: https://infopkb.bantenprov.go.id/',
        'Sec-Fetch-Dest: document',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: same-origin',
        'Sec-Fetch-User: ?1',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return [
            'status' => false,
            'message' => 'Koneksi gagal',
            'error' => $error,
        ];
    }

    if ($httpCode !== 200) {
        return [
            'status' => false,
            'message' => 'Server merespon dengan kode ' . $httpCode,
        ];
    }

    $html = new simple_html_dom();
    $html->load($response);

    if ($error_div = $html->find('div.alert-danger', 0)) {
        return [
            'status' => false,
            'message' => trim($error_div->plaintext),
        ];
    }

    $result = [];
    $rows = $html->find('div.row.px-1.border');

    foreach ($rows as $row) {
        $label = $row->find('div.col-4 span.d-none.d-md-block.fs-5.fw-bold', 0);
        if (!$label) {
            $label = $row->find('div.col-4 span.fw-bold', 0);
        }

        $value = $row->find('div.col-8 span.d-none.d-md-block', 0);
        if (!$value) {
            $value = $row->find('div.col-8', 0);
        }

        if ($label && $value) {
            $clean_label = trim($label->plaintext);
            $clean_value = trim($value->plaintext);
            $clean_value = preg_replace('/\s+/', ' ', $clean_value);
            $clean_value = str_replace(['-', ','], '', $clean_value);
            $result[$clean_label] = $clean_value;
        }
    }

    if (empty($result)) {
        return [
            'status' => false,
            'message' => 'Data tidak ditemukan dalam response',
            'debug_html' => substr($response, 0, 500),
        ];
    }

    $formatted_result = [];
    $emoji_map = [
        'NO. POLISI' => '🚗',
        'NAMA PEMILIK' => '🧑‍💼',
        'ALAMAT' => '🏠',
        'TIPE/MODEL' => '🚚',
        'JENIS' => '🛵',
        'MEREK' => '🏭',
        'NO. RANGKA/MESIN' => '🔩',
        'TAHUN / CC / BBM' => '📅',
        'TGL. AKHIR PKB yl.' => '📅',
        'TGL. AKHIR PKB yad.' => '📅',
        'TGL. AKHIR STNK yl.' => '📅',
        'TGL. DAFTAR' => '📅',
        'KETERANGAN' => '❗',
        'WARNA' => '🎨',
        'PLAT' => '🚧️',
        'PKB Pokok' => '💰',
        'PKB Denda' => '💰',
        'OPSEN PKB Pokok' => '💰',
        'OPSEN PKB Denda' => '💰',
        'KAB/KOTA' => '🪙',
        'SWDKLLJ Pokok' => '💰',
        'SWDKLLJ Denda' => '💰',
        'STNK' => '💰',
        'TNKB' => '💰',
        'PNBP NOPIL' => '💰',
        'JUMLAH' => '💸',
    ];
    foreach ($result as $label => $value) {
        $emoji = $emoji_map[$label] ?? '';
        $formatted_result[] = "$emoji $label: $value";
    }

    return [
        'status' => true,
        'data' => implode("\n", $formatted_result),
    ];
}

/**
 * Fungsi untuk memeriksa informasi kendaraan di DIY.
 *
 * @param string $seri Seri atau akhiran nomor polisi kendaraan.
 * @param string $nomor Nomor polisi kendaraan.
 * @return array Hasil pemeriksaan kendaraan.
 */
function checkDiyVehicle(string $seri, string $nomor): array
{
    $url = 'https://samsatsleman.jogjaprov.go.id/cek/pages/getpajak';

    $headers = [
        'Accept: text/html, */*; q=0.01',
        'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Origin: https://samsatsleman.jogjaprov.go.id',
        'Referer: https://samsatsleman.jogjaprov.go.id/cek/pajak',
        'Sec-Fetch-Dest: empty',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Site: same-origin',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest',
        'Sec-Ch-Ua: "Not A(Brand";v="8", "Chromium";v="132", "Google Chrome";v="132"',
        'Sec-Ch-Ua-Mobile: ?0',
        'Sec-Ch-Ua-Platform: "Windows"',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "nomer=$nomor&kode_belakang=" . strtolower($seri));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $hasil = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return [
            'status' => false,
            'message' => 'Koneksi gagal',
            'error' => $error,
        ];
    }

    if ($httpCode !== 200) {
        return [
            'status' => false,
            'message' => 'Server merespon dengan kode ' . $httpCode,
        ];
    }

// $hasil = $response;
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

$o = "\n";
$hasil = str_replace("Nopol","🚧 Nopol : ",$hasil);
$hasil = str_replace("Merk","$o ⛽️ Merk : ",$hasil);
$hasil = str_replace("Model","$o 🛵 Model : ",$hasil);
$hasil = str_replace("Tahun","$o 🚏 Tahun : ",$hasil);
$hasil = str_replace("POKOK","POKOK : ",$hasil);
$hasil = str_replace("DENDA","DENDA : ",$hasil);
$hasil = str_replace("TOTAL PAJAK","$o 💰 TOTAL PAJAK : ",$hasil);
$hasil = str_replace("PKB","$o 💸 PKB",$hasil);
$hasil = str_replace("SWDKLLJ","$o 💵 SWDKLLJ",$hasil);
$hasil = str_replace("TGL AKHIR $o 💸 PKB","$o 📆 TGL AKHIR PKB : ",$hasil);
$response = $hasil;

    if (empty($response)) {
        return [
            'status' => false,
            'message' => 'Data tidak ditemukan dalam response',
            'debug_html' => substr($response, 0, 500)
        ];
    }
    
    return [
        "status" => true,
        "data" => $response
    ];
}

/**
 * Fungsi untuk memeriksa informasi kendaraan di Jawa Barat.
 *
 * @param string $kode Kode wilayah kendaraan.
 * @param string $nomor Nomor polisi kendaraan.
 * @param string $seri Seri atau akhiran nomor polisi kendaraan.
 * @return array Hasil pemeriksaan kendaraan.
 */
function checkJabarVehicle(string $kode, string $nomor, string $seri): array
{
    // jajali serine
        // Format URL based on the length of $seri
    if (strlen($seri) == 3) {
        $url = "https://sambara-v2.bapenda.jabarprov.go.id/api/renew-sambara/v2/get-info-pkb?no_polisi=$kode+$seri$nomor&kd_plat=1";
    } else {
        $url = "https://sambara-v2.bapenda.jabarprov.go.id/api/renew-sambara/v2/get-info-pkb?no_polisi=$kode+$seri+$nomor&kd_plat=1";
    }

    $headers = [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        'Authorization: Basic c2FtYmFyYTp5U2Y0cnozZTlk',
        'Priority: u=1, i',
        'Connection: keep-alive',
        'Referer: https://sambara-v2.bapenda.jabarprov.go.id/',
        'Sec-Fetch-Dest: empty',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Site: same-origin',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
        'Sec-Ch-Ua: "Chromium";v="124", "Google Chrome";v="124", "Not-A.Brand";v="99"',
        'Sec-Ch-Ua-Mobile: ?0',
        'Sec-Ch-Ua-Platform: "Windows"',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return [
            'status' => false,
            'message' => 'Koneksi gagal',
            'error' => $error,
        ];
    }

    if ($httpCode !== 200) {
        return [
            'status' => false,
            'message' => 'Server merespon dengan kode ' . $httpCode,
        ];
    }

    $data = json_decode($response, true);

    if (!isset($data['data'])) {
        return [
            'status' => false,
            'message' => 'Data tidak ditemukan dalam response',
            'debug' => substr($response, 0, 500),
        ];
    }

    // Ekstrak data
    $umum = $data['data']['informasi-umum'] ?? [];
    $pkb = $data['data']['informasi-pkb-pnbp'] ?? [];
    $tarif = $data['data']['pembayaran-pkb-pnbp'] ?? [];
    $proses = $data['data']['tanggal-proses'] ?? '';
    $keterangan = $data['data']['keterangan'] ?? '';
    
    // Format data dengan emoji
    $emoji_map = [
        'NO. POLISI' => '🚗',
        'MERK' => '🏭',
        'MODEL' => '🚚',
        'WARNA' => '🎨',
        'MILIK KE' => '🧑‍💼',
        'TGL AKHIR STNK' => '📅',
        'TGL AKHIR PAJAK' => '📅',
        'WILAYAH' => '📍',
        'PKB POKOK' => '💰',
        'SWDKLLJ POKOK' => '💰',
        'SWDKLLJ DENDA' => '💰',
        'TARIF STNK' => '💰',
        'TARIF BPKB' => '💰',
        'TOTAL' => '💸',
        'TANGGAL PROSES' => '⏱️',
        'KETERANGAN' => '❗'
    ];
    
    $result = [];
    if (!empty($umum)) {
        $result[] = $emoji_map['NO. POLISI'] . " NO. POLISI: " . ($umum['nomor-polisi'] ?? '-');
        $result[] = $emoji_map['MERK'] . " MERK: " . ($umum['merk'] ?? '-');
        $result[] = $emoji_map['MODEL'] . " MODEL: " . ($umum['model'] ?? '-');
        $result[] = $emoji_map['WARNA'] . " WARNA: " . ($umum['warna'] ?? '-');
        $result[] = $emoji_map['MILIK KE'] . " MILIK KE: " . ($umum['milik-ke'] ?? '-');
    }
    
    if (!empty($pkb)) {
        $result[] = $emoji_map['TGL AKHIR STNK'] . " TGL AKHIR STNK: " . ($pkb['tanggal-stnk'] ?? '-');
        $result[] = $emoji_map['TGL AKHIR PAJAK'] . " TGL AKHIR PAJAK: " . ($pkb['tanggal-pajak'] ?? '-');
        $result[] = $emoji_map['WILAYAH'] . " WILAYAH: " . ($pkb['wilayah'] ?? '-');
    }
    
    if (!empty($tarif)) {
        $result[] = $emoji_map['PKB POKOK'] . " PKB POKOK: Rp " . number_format($tarif['pkb-pokok'] ?? 0, 0, ',', '.');
        $result[] = $emoji_map['SWDKLLJ POKOK'] . " SWDKLLJ POKOK: Rp " . number_format($tarif['swdkllj-pokok'] ?? 0, 0, ',', '.');
        $result[] = $emoji_map['SWDKLLJ DENDA'] . " SWDKLLJ DENDA: Rp " . number_format($tarif['swdkllj-denda'] ?? 0, 0, ',', '.');
        $result[] = $emoji_map['TARIF STNK'] . " TARIF STNK: Rp " . number_format($tarif['pnbp-stnk'] ?? 0, 0, ',', '.');
        $result[] = $emoji_map['TARIF BPKB'] . " TARIF BPKB: Rp " . number_format($tarif['pnbp-tnkb'] ?? 0, 0, ',', '.');
        $result[] = $emoji_map['TOTAL'] . " TOTAL: Rp " . number_format($tarif['total'] ?? 0, 0, ',', '.');
    }
    
    if (!empty($proses)) {
        $result[] = $emoji_map['TANGGAL PROSES'] . " TANGGAL PROSES: " . $proses;
    }
    
    if (!empty($keterangan)) {
        $result[] = $emoji_map['KETERANGAN'] . " KETERANGAN: " . $keterangan;
    }
    
    if (empty($result)) {
        return [
            'status' => false,
            'message' => 'Tidak ada data yang ditemukan'
        ];
    }
    
    return [
        'status' => true,
        'data' => implode("\n", $result),
    ];
}

/**
 * Fungsi untuk memeriksa informasi kendaraan di Jawa Tengah.
 *
 * @param string $kode Kode wilayah kendaraan.
 * @param string $nomor Nomor polisi kendaraan.
 * @param string $seri Seri atau akhiran nomor polisi kendaraan.
 * @return array Hasil pemeriksaan kendaraan.
 */
function checkJatengVehicle(string $kode, string $nomor, string $seri): array
{
    $url = "https://samsat.jatengprov.go.id/info/kendaraan/api/api_req_info_kbm";

    $data = [
        "na" => strtoupper($kode),
        "nb" => $nomor,
        "nc" => strtoupper($seri),
        "key" => "TkVXIFNBS1BPTEU=",
        "token" => "666",
    ];

    $payload = json_encode($data);

    $headers = [
        'user-agent: Dart/3.6 (dart:io)',
        'content-type: application/json',
        'accept-encoding: gzip',
        'host: samsat.jatengprov.go.id',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    
    // print_r($response);
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return [
            'status' => false,
            'message' => 'Koneksi gagal',
            'error' => $error,
        ];
    }

    if ($httpCode !== 200) {
        return [
            'status' => false,
            'message' => 'Server merespon dengan kode ' . $httpCode,
            'http_code' => $httpCode,
        ];
    }

    $data = json_decode($response, true);

    if (!isset($data['Status']) || $data['Status'] !== "000") {
        return [
            'status' => false,
            'message' => 'Data kendaraan tidak ditemukan',
            'api_response' => $data,
        ];
    }

    // Format data dengan emoji
    $emoji_map = [
        'merek' => '🏭',
        'tipe' => '🚚',
        'model' => '🚙',
        'WarnaKB' => '🎨',
        'warna_tnkb' => '🚦',
        'thn_buat' => '📅',
        'cylinder' => '⚙️',
        'tgl_jatuh_tempo' => '📆',        
        'tgl_stnk' => '📆',
        'status_pajak' => '💰',        
        'total_pkb_pokok' => '💰',
        'total_pkb_denda' => '💰',
        'total_jr_pokok' => '💵',
        'total_jr_denda' => '💵',
        'total' => '💸',
        'milikke' => '🧑‍💼',
        'lokasi_samsat' => '📍'
    ];
    
    $formatted_data = [];
    
    // Info dasar kendaraan
    $formatted_data[] = "🚗 NOMOR POLISI: " . $kode . " " . $nomor . " " . $seri;
    $formatted_data[] = $emoji_map['merek'] . " MERK: " . ($data['merek'] ?? '-');
    $formatted_data[] = $emoji_map['tipe'] . " TIPE: " . ($data['tipe'] ?? '-');
    $formatted_data[] = $emoji_map['model'] . " MODEL: " . ($data['model'] ?? '-');
    $formatted_data[] = $emoji_map['WarnaKB'] . " WARNA: " . ($data['WarnaKB'] ?? '-');
    $formatted_data[] = $emoji_map['thn_buat'] . " TAHUN BUAT: " . ($data['thn_buat'] ?? '-');
    $formatted_data[] = $emoji_map['milikke'] . " MILIK KE: " . ($data['milikke'] ?? '-');
    
    // Info pajak
    $formatted_data[] = $emoji_map['tgl_jatuh_tempo'] . " TGL AKHIR PAJAK: " . ($data['tgl_jatuh_tempo'] ?? '-');
    $formatted_data[] = $emoji_map['tgl_stnk'] . " TGL AKHIR STNK: " . ($data['tgl_stnk'] ?? '-');  
    $formatted_data[] = $emoji_map['status_pajak'] . " STATUS PAJAK: " . ($data['status_pajak'] ?? '-');      
    // Format nilai uang
    $formatCurrency = function($value) {
        return 'Rp ' . number_format((int)$value, 0, ',', '.');
    };
    
    $formatted_data[] = $emoji_map['total_pkb_pokok'] . " PKB POKOK: " . $formatCurrency($data['total_pkb_pokok'] ?? 0);
    $formatted_data[] = $emoji_map['total_pkb_denda'] . " PKB DENDA: " . $formatCurrency($data['total_pkb_denda'] ?? 0);
    $formatted_data[] = $emoji_map['total_jr_pokok'] . " SWDKLLJ POKOK: " . $formatCurrency($data['total_jr_pokok'] ?? 0);
    $formatted_data[] = $emoji_map['total_jr_denda'] . " SWDKLLJ DENDA: " . $formatCurrency($data['total_jr_denda'] ?? 0);
    $formatted_data[] = $emoji_map['total'] . " TOTAL: " . $formatCurrency($data['total'] ?? 0);
    
    // Info lokasi
    if (!empty($data['lokasi_samsat'])) {
        $formatted_data[] = $emoji_map['lokasi_samsat'] . " LOKASI SAMSAT: " . $data['lokasi_samsat'];
    }
    
    return [
        'status' => true,
        'data' => implode("\n", $formatted_data),
        // 'raw_data' => $data // Untuk debugging
    ];
}

// Handle request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $kode = $input['kode'] ?? '';
    $nomor = $input['nomor'] ?? '';
    $seri = $input['seri'] ?? '';

    if (empty($kode) || empty($nomor) || empty($seri)) {
        echo json_encode([
            'status' => false,
            'message' => 'Parameter tidak lengkap',
            'required_params' => ['kode', 'nomor', 'seri'],
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $result = checkVehicle($kode, $nomor, $seri);
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $kode = $_GET['kode'] ?? '';
    $nomor = $_GET['nomor'] ?? '';
    $seri = $_GET['seri'] ?? '';

    if (empty($kode) || empty($nomor) || empty($seri)) {
        echo json_encode([
            'status' => false,
            'message' => 'Parameter tidak lengkap',
            'required_params' => ['kode', 'nomor', 'seri'],
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $result = checkVehicle($kode, $nomor, $seri);
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        'status' => false,
        'message' => 'Metode request tidak diizinkan',
    ], JSON_PRETTY_PRINT);
}

?>
