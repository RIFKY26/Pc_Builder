<?php
/**
 * Test API Key - File untuk test apakah API key ter-load dengan benar
 * Akses: http://localhost/Web%20Pc/test_api_key.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test API Key Configuration</h1>";
echo "<hr>";

// Load config
$config_path = __DIR__ . '/ai_config.php';
if (file_exists($config_path)) {
    require_once $config_path;
    echo "✅ File ai_config.php ditemukan di: " . $config_path . "<br><br>";
} else {
    if (file_exists('ai_config.php')) {
        require_once 'ai_config.php';
        echo "✅ File ai_config.php ditemukan (path relatif)<br><br>";
    } else {
        echo "❌ File ai_config.php TIDAK ditemukan!<br><br>";
        exit;
    }
}

// Cek konstanta
echo "<h2>Status Konstanta:</h2>";
echo "ENABLE_AI: " . (defined('ENABLE_AI') ? (ENABLE_AI ? '✅ true' : '❌ false') : '❌ TIDAK TERDEFINISI') . "<br>";
echo "AI_PROVIDER: " . (defined('AI_PROVIDER') ? AI_PROVIDER : '❌ TIDAK TERDEFINISI') . "<br>";

// Cek API Key
echo "<h2>Status API Key:</h2>";
if (defined('OPENAI_API_KEY')) {
    $api_key = OPENAI_API_KEY;
    $api_key_trimmed = trim($api_key);
    
    echo "OPENAI_API_KEY terdefinisi: ✅<br>";
    echo "Panjang API key: " . strlen($api_key) . " karakter<br>";
    echo "Panjang setelah trim: " . strlen($api_key_trimmed) . " karakter<br>";
    echo "Preview (20 karakter pertama): " . substr($api_key_trimmed, 0, 20) . "...<br>";
    echo "Preview (20 karakter terakhir): ..." . substr($api_key_trimmed, -20) . "<br>";
    
    // Validasi format
    if (empty($api_key_trimmed)) {
        echo "❌ API Key KOSONG setelah trim!<br>";
    } elseif (substr($api_key_trimmed, 0, 3) !== 'sk-') {
        echo "❌ Format API Key TIDAK VALID! Harus dimulai dengan 'sk-'<br>";
        echo "Karakter pertama: '" . substr($api_key_trimmed, 0, 3) . "'<br>";
    } else {
        echo "✅ Format API Key VALID (dimulai dengan 'sk-')<br>";
    }
    
    // Test apakah bisa digunakan untuk Authorization header
    echo "<h2>Test Authorization Header:</h2>";
    $auth_header = 'Authorization: Bearer ' . $api_key_trimmed;
    echo "Header yang akan dikirim:<br>";
    echo "<code style='background: #f0f0f0; padding: 5px; display: block; margin: 10px 0;'>" . htmlspecialchars($auth_header) . "</code>";
    
    // Cek apakah ada karakter aneh
    if (preg_match('/[\r\n]/', $api_key_trimmed)) {
        echo "⚠️ WARNING: API Key mengandung karakter newline/carriage return!<br>";
    }
    
} else {
    echo "❌ OPENAI_API_KEY TIDAK TERDEFINISI!<br>";
}

echo "<hr>";
echo "<h2>Langkah Selanjutnya:</h2>";
echo "<ol>";
echo "<li>Jika semua ✅, berarti API key sudah benar. Coba test di <a href='debug_ai.php'>debug_ai.php</a></li>";
echo "<li>Jika ada ❌, perbaiki masalah yang ditampilkan di atas.</li>";
echo "<li>Pastikan tidak ada spasi di awal/akhir API key di file ai_config.php</li>";
echo "</ol>";

echo "<br><a href='index.php'>← Kembali ke Halaman Utama</a> | <a href='debug_ai.php'>Test API Call →</a>";
?>

