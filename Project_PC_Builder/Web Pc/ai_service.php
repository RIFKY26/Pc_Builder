<?php
/**
 * GENERATIVE AI SERVICE
 * 
 * Service untuk memanggil berbagai provider Generative AI API
 * Support: OpenAI (ChatGPT), Google Gemini, Anthropic Claude
 */

// Load config jika ada, jika tidak gunakan default (AI disabled)
$config_path = __DIR__ . '/ai_config.php';
if (file_exists($config_path)) {
    require_once $config_path;
} else {
    // Coba path relatif juga
    if (file_exists('ai_config.php')) {
        require_once 'ai_config.php';
    } else {
        // Default: AI disabled
        if (!defined('ENABLE_AI')) define('ENABLE_AI', false);
        if (!defined('AI_PROVIDER')) define('AI_PROVIDER', 'none');
        if (!defined('OPENAI_API_KEY')) define('OPENAI_API_KEY', '');
        if (!defined('GEMINI_API_KEY')) define('GEMINI_API_KEY', '');
        if (!defined('CLAUDE_API_KEY')) define('CLAUDE_API_KEY', '');
        if (!defined('OPENAI_MODEL')) define('OPENAI_MODEL', 'gpt-3.5-turbo');
        if (!defined('GEMINI_MODEL')) define('GEMINI_MODEL', 'gemini-pro');
        if (!defined('CLAUDE_MODEL')) define('CLAUDE_MODEL', 'claude-3-sonnet-20240229');
        if (!defined('OPENAI_API_URL')) define('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions');
        if (!defined('GEMINI_API_URL')) define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/');
        if (!defined('CLAUDE_API_URL')) define('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages');
        if (!defined('AI_TIMEOUT')) define('AI_TIMEOUT', 10);
    }
}

/**
 * Generate AI insight untuk rakitan PC
 * 
 * @param array $solution Array berisi komponen yang dipilih
 * @param int $budget Budget yang digunakan
 * @param string $tier Tier kebutuhan (low/mid/high)
 * @return string|false Insight dari AI atau false jika gagal
 */
function generate_ai_insight($solution, $budget, $tier) {
    if (!ENABLE_AI || AI_PROVIDER == 'none') {
        return false;
    }
    
    $provider = AI_PROVIDER;
    
    // Build prompt untuk AI
    $prompt = build_ai_prompt($solution, $budget, $tier);
    
    // Panggil API sesuai provider
    switch ($provider) {
        case 'openai':
            return call_openai_api($prompt);
        case 'gemini':
            return call_gemini_api($prompt);
        case 'claude':
            return call_claude_api($prompt);
        default:
            return false;
    }
}

/**
 * Build prompt untuk AI berdasarkan rakitan PC
 */
function build_ai_prompt($solution, $budget, $tier) {
    $components_text = "";
    $total_price = 0;
    $total_tdp = 0;
    
    $component_names = [
        "cpu" => "CPU",
        "motherboard" => "Motherboard",
        "gpu" => "GPU",
        "ram" => "RAM",
        "psu" => "PSU",
        "storage" => "Storage",
        "casing" => "Casing"
    ];
    
    foreach ($solution as $key => $item) {
        $components_text .= "- {$component_names[$key]}: {$item['name']}\n";
        $total_price += $item['price'];
        if (in_array($key, ['cpu', 'gpu']) && isset($item['tdp'])) {
            $total_tdp += $item['tdp'];
        }
    }
    
    $prompt = "Anda adalah ahli PC Builder yang berpengalaman. Berikan analisis dan insight untuk rakitan PC berikut:\n\n";
    $prompt .= "BUDGET: Rp " . number_format($budget, 0, ',', '.') . "\n";
    $prompt .= "KEBUTUHAN: " . strtoupper($tier) . " tier\n";
    $prompt .= "TOTAL HARGA: Rp " . number_format($total_price, 0, ',', '.') . "\n";
    $prompt .= "ESTIMASI TDP: {$total_tdp}W\n\n";
    $prompt .= "KOMPONEN:\n{$components_text}\n";
    $prompt .= "Berikan analisis dalam bahasa Indonesia yang mencakup:\n";
    $prompt .= "1. Kekuatan dan kelebihan rakitan ini\n";
    $prompt .= "2. Performa yang diharapkan untuk kebutuhan {$tier} tier\n";
    $prompt .= "3. Kompatibilitas antar komponen\n";
    $prompt .= "4. Saran optimasi atau upgrade (jika ada)\n";
    $prompt .= "5. Tips perawatan dan penggunaan\n";
    $prompt .= "Jawab dengan format yang rapi dan mudah dibaca (maksimal 200 kata).";
    
    return $prompt;
}

/**
 * Call OpenAI (ChatGPT) API
 */
function call_openai_api($prompt) {
    // Pastikan konstanta terdefinisi
    if (!defined('OPENAI_API_KEY') || empty(OPENAI_API_KEY)) {
        error_log("OpenAI API Key tidak terdefinisi atau kosong");
        return false;
    }
    
    // Trim API key untuk menghilangkan spasi di awal/akhir
    $api_key = trim(OPENAI_API_KEY);
    
    if (empty($api_key)) {
        error_log("OpenAI API Key kosong setelah trim");
        return false;
    }
    
    // Validasi format API key (harus dimulai dengan sk-)
    if (substr($api_key, 0, 3) !== 'sk-') {
        error_log("OpenAI API Key format tidak valid (harus dimulai dengan sk-)");
        return false;
    }
    
    $data = [
        'model' => defined('OPENAI_MODEL') ? OPENAI_MODEL : 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'Anda adalah ahli PC Builder yang berpengalaman. Berikan analisis teknis yang akurat dan saran yang bermanfaat.'
            ],
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ],
        'max_tokens' => 500,
        'temperature' => 0.7
    ];
    
    $api_url = defined('OPENAI_API_URL') ? OPENAI_API_URL : 'https://api.openai.com/v1/chat/completions';
    $timeout = defined('AI_TIMEOUT') ? AI_TIMEOUT : 10;
    
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    // Debug: Log error jika ada (hanya untuk development)
    if ($curl_error) {
        error_log("OpenAI cURL Error: " . $curl_error);
    }
    
    if ($http_code == 200 && $response) {
        $result = json_decode($response, true);
        if (isset($result['choices'][0]['message']['content'])) {
            return trim($result['choices'][0]['message']['content']);
        }
    } else {
        // Log error response untuk debugging
        if ($response) {
            $error_data = json_decode($response, true);
            if (isset($error_data['error']['message'])) {
                error_log("OpenAI API Error: " . $error_data['error']['message']);
            }
        }
        error_log("OpenAI API HTTP Code: " . $http_code);
    }
    
    return false;
}

/**
 * Call Google Gemini API
 */
function call_gemini_api($prompt) {
    if (empty(GEMINI_API_KEY)) {
        return false;
    }
    
    $url = GEMINI_API_URL . GEMINI_MODEL . ':generateContent?key=' . GEMINI_API_KEY;
    
    $data = [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => $prompt
                    ]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 500
        ]
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, AI_TIMEOUT);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200 && $response) {
        $result = json_decode($response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($result['candidates'][0]['content']['parts'][0]['text']);
        }
    }
    
    return false;
}

/**
 * Call Anthropic Claude API
 */
function call_claude_api($prompt) {
    if (empty(CLAUDE_API_KEY)) {
        return false;
    }
    
    $data = [
        'model' => CLAUDE_MODEL,
        'max_tokens' => 500,
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ]
    ];
    
    $ch = curl_init(CLAUDE_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'x-api-key: ' . CLAUDE_API_KEY,
        'anthropic-version: 2023-06-01'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, AI_TIMEOUT);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200 && $response) {
        $result = json_decode($response, true);
        if (isset($result['content'][0]['text'])) {
            return trim($result['content'][0]['text']);
        }
    }
    
    return false;
}

/**
 * Get default insight jika AI tidak tersedia
 */
function get_default_insight($solution, $budget, $tier) {
    $cpu_name = $solution['cpu']['name'];
    $gpu_name = $solution['gpu']['name'];
    $total_price = 0;
    foreach ($solution as $item) {
        $total_price += $item['price'];
    }
    
    $insight = "Rakitan ini menggunakan <strong>{$cpu_name}</strong> dan <strong>{$gpu_name}</strong>. ";
    
    if ($tier == "high") {
        $insight .= "Konfigurasi high-end ini sangat cocok untuk gaming AAA, video editing, dan rendering 3D. ";
    } elseif ($tier == "mid") {
        $insight .= "Konfigurasi mid-range ini ideal untuk gaming casual, streaming, dan produktivitas. ";
    } else {
        $insight .= "Konfigurasi entry-level ini cocok untuk office work, browsing, dan aplikasi ringan. ";
    }
    
    $insight .= "Pastikan airflow casing bagus dan PSU memiliki headroom yang cukup untuk stabilitas jangka panjang.";
    
    return $insight;
}
?>

