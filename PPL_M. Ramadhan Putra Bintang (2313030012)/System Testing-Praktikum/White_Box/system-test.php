<?php
/**
 * File: system-test.php
 * Tujuan: White Box Testing REST Client PHP untuk API NewsAPI.org (menggunakan respons asli)
 * Metode: try-catch dengan validasi logika + kondisi real-time dari API
 */

// Konfigurasi dasar
$base_url = "https://newsapi.org/v2/top-headlines";
$valid_key = "f4bbd4b3e4ee49eea1ffe00502212047"; 
$invalid_key = "abc123invalidkey";

/**
 * Fungsi untuk melakukan request ke NewsAPI.org
 */
function getNews($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception("Curl error: " . curl_error($ch));
    }

    curl_close($ch);
    return json_decode($response, true);
}

/**
 * Fungsi helper untuk menampilkan hasil uji
 */
function printResult($testName, $result, $extra = "") {
    echo $testName . " => " . ($result ? "PASS ✅" : "FAIL ❌") . " $extra" . PHP_EOL;
}

/* ==============================================================
   TEST CASE 1: API Key valid → hasil harus status: ok dan ada articles
   ============================================================== */
try {
    $url = $base_url . "?country=us&apiKey=" . $valid_key;
    $response = getNews($url);

    $result = isset($response['status']) && $response['status'] === 'ok' && isset($response['articles']);
    $extra = isset($response['status']) ? "(status: {$response['status']}, articles: " . count($response['articles'] ?? []) . ")" : "";
    printResult("TC001 - API Key Valid menghasilkan respon OK", $result, $extra);
} catch (Exception $e) {
    printResult("TC001 - API Key Valid menghasilkan respon OK", false, $e->getMessage());
}

/* ==============================================================
   TEST CASE 2: API Key tidak valid → hasil seharusnya ERROR (jika error maka FAIL)
   ============================================================== */
try {
    $url = $base_url . "?country=us&apiKey=" . $invalid_key;
    $response = getNews($url);

    // Validator: API key tidak valid seharusnya status = "error"
    $result = isset($response['status']) && $response['status'] === 'ok';
    $extra = isset($response['status']) ? "(status: {$response['status']}, message: {$response['message']})" : "";
    printResult("TC002 - API Key Tidak Valid", $result, $extra);
} catch (Exception $e) {
    printResult("TC002 - API Key Tidak Valid", false, $e->getMessage());
}

/* ==============================================================
   TEST CASE 3: Request kategori “technology” → hasil harus OK dan ada artikel
   ============================================================== */
try {
    $url = $base_url . "?country=us&category=technology&apiKey=" . $valid_key;
    $response = getNews($url);

    $result = isset($response['status']) && $response['status'] === 'ok' && count($response['articles']) > 0;
    $extra = isset($response['status']) ? "(status: {$response['status']}, articles: " . count($response['articles'] ?? []) . ")" : "";
    printResult("TC003 - Request kategori 'technology' menampilkan berita", $result, $extra);
} catch (Exception $e) {
    printResult("TC003 - Request kategori 'technology' menampilkan berita", false, $e->getMessage());
}

echo PHP_EOL . "=== Pengujian Selesai ===" . PHP_EOL;
?>
