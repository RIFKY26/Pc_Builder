<?php
/**
 * DEBUG AI - File untuk test apakah AI configuration sudah benar
 * Akses: http://localhost/Web%20Pc/debug_ai.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug AI Configuration</h1>";
echo "<hr>";

// 1. Cek apakah file config ada
echo "<h2>1. Cek File Configuration</h2>";
$config_path = __DIR__ . '/ai_config.php';
if (file_exists($config_path)) {
    echo "✅ File ai_config.php ditemukan di: " . $config_path . "<br>";
    require_once $config_path;
} else {
    if (file_exists('ai_config.php')) {
        echo "✅ File ai_config.php ditemukan (path relatif)<br>";
        require_once 'ai_config.php';
    } else {
        echo "❌ File ai_config.php TIDAK ditemukan!<br>";
        echo "Buat file ai_config.php dari ai_config.example.php<br>";
        exit;
    }
}

// 2. Cek konstanta
echo "<h2>2. Cek Konstanta</h2>";
echo "ENABLE_AI: " . (defined('ENABLE_AI') ? (ENABLE_AI ? '✅ true' : '❌ false') : '❌ TIDAK TERDEFINISI') . "<br>";
echo "AI_PROVIDER: " . (defined('AI_PROVIDER') ? AI_PROVIDER : '❌ TIDAK TERDEFINISI') . "<br>";
echo "OPENAI_API_KEY: " . (defined('OPENAI_API_KEY') && !empty(OPENAI_API_KEY) ? '✅ Ada (' . substr(trim(OPENAI_API_KEY), 0, 20) . '...)' : '❌ KOSONG') . "<br>";

// 3. Test koneksi API
if (defined('ENABLE_AI') && ENABLE_AI && defined('AI_PROVIDER') && AI_PROVIDER == 'openai' && !empty(OPENAI_API_KEY)) {
    echo "<h2>3. Test API Call</h2>";
    
    require_once 'ai_service.php';
    
    // Test dengan data dummy
    $test_solution = [
        'cpu' => ['name' => 'Intel Core i5-12400', 'price' => 2800000, 'tdp' => 65, 'socket' => 'LGA1700'],
        'motherboard' => ['name' => 'Asus B760M', 'price' => 2100000, 'socket' => 'LGA1700', 'ram_type' => 'DDR4'],
        'gpu' => ['name' => 'Asus RTX 3060 12GB', 'price' => 4400000, 'tdp' => 170, 'length' => 240],
        'ram' => ['name' => 'Corsair 16GB DDR4', 'price' => 650000, 'type' => 'DDR4', 'size' => 16],
        'psu' => ['name' => 'Corsair 650W', 'price' => 900000, 'wattage' => 650],
        'storage' => ['name' => 'Samsung NVMe 1TB', 'price' => 1100000, 'interface' => 'NVMe'],
        'casing' => ['name' => 'NZXT Mid Tower', 'price' => 800000, 'max_gpu_length' => 340]
    ];
    
    echo "Mencoba memanggil API OpenAI...<br>";
    echo "<div style='background: #f0f0f0; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    
    $start_time = microtime(true);
    $result = generate_ai_insight($test_solution, 15000000, 'mid');
    $end_time = microtime(true);
    $duration = $end_time - $start_time;
    
    if ($result !== false && !empty($result)) {
        echo "<strong style='color: green;'>✅ SUKSES! AI merespons</strong><br><br>";
        echo "<strong>Waktu response:</strong> " . number_format($duration, 2) . " detik<br><br>";
        echo "<strong>Hasil AI:</strong><br>";
        echo "<div style='background: white; padding: 15px; border-left: 4px solid green; margin-top: 10px;'>";
        echo nl2br(htmlspecialchars($result));
        echo "</div>";
    } else {
        echo "<strong style='color: red;'>❌ GAGAL! AI tidak merespons</strong><br><br>";
        echo "Kemungkinan masalah:<br>";
        echo "- API key tidak valid<br>";
        echo "- Tidak ada koneksi internet<br>";
        echo "- API key sudah expired<br>";
        echo "- Rate limit tercapai<br>";
        echo "- Error di API call (cek error log)<br>";
        
        // Cek error terakhir
        if (function_exists('curl_error')) {
            echo "<br><strong>Info:</strong> Pastikan cURL extension aktif di PHP<br>";
        }
    }
    
    echo "</div>";
} else {
    echo "<h2>3. Test API Call</h2>";
    echo "⚠️ Konfigurasi belum lengkap. Pastikan:<br>";
    echo "- ENABLE_AI = true<br>";
    echo "- AI_PROVIDER = 'openai'<br>";
    echo "- OPENAI_API_KEY sudah diisi<br>";
}

// 4. Cek PHP extensions
echo "<h2>4. Cek PHP Extensions</h2>";
echo "cURL: " . (function_exists('curl_init') ? '✅ Terinstall' : '❌ TIDAK terinstall') . "<br>";
echo "JSON: " . (function_exists('json_encode') ? '✅ Terinstall' : '❌ TIDAK terinstall') . "<br>";

// 5. Cek error log
echo "<h2>5. Informasi</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "<br>";

echo "<hr>";
echo "<h2>Langkah Selanjutnya</h2>";
echo "<ol>";
echo "<li>Jika semua ✅, berarti konfigurasi sudah benar. Coba refresh halaman utama dan test lagi.</li>";
echo "<li>Jika ada ❌, perbaiki masalah yang ditampilkan di atas.</li>";
echo "<li>Jika API test gagal, cek koneksi internet dan pastikan API key masih valid.</li>";
echo "</ol>";

echo "<br><a href='index.php'>← Kembali ke Halaman Utama</a> | <a href='test_api_key.php'>Test API Key →</a>";
?>

