<?php
/**
 * PC-BUILDER AI (Constraint Satisfaction Problem)
 * Sistem Rekomendasi Rakit PC menggunakan Backtracking Search
 * 
 * Interface Web dengan HTML/CSS/Bootstrap
 */

require_once 'database.php';
require_once 'ai_service.php';

// --- KONFIGURASI ALGORITMA ---
// Urutan variabel berpengaruh pada kecepatan pencarian (Heuristic).
// Kita urutkan dari yang paling krusial/banyak constraint:
// CPU -> Motherboard -> GPU -> RAM -> PSU -> Storage -> Casing
$VARIABLES = ["cpu", "motherboard", "gpu", "ram", "psu", "storage", "casing"];

/**
 * Fungsi Pengecekan Constraint (Sesuai Proposal Bab 3.3)
 * Mengecek apakah nilai baru (value) valid untuk ditambahkan ke assignment.
 */
function is_consistent($var, $value, $assignment, $user_budget, $user_tier) {
    
    // --- 1. CONSTRAINT BUDGET [Proposal 3.3 Poin 1] ---
    // Total harga tidak boleh melebihi budget user
    $current_cost = 0;
    foreach ($assignment as $item) {
        $current_cost += $item['price'];
    }
    if ($current_cost + $value['price'] > $user_budget) {
        return false;
    }

    // --- 2. USER REQUIREMENT CONSTRAINT (Optional) [Proposal 3.3 Poin 7] ---
    // Jika user minta High-End, jangan kasih komponen Low-End (agar optimal)
    // Aturan ini hanya berlaku untuk komponen yang punya atribut 'tier'
    if ($user_tier == "high" && isset($value["tier"]) && $value["tier"] == "low") {
        return false;
    }
    
    // --- 3. TEKNIS: CPU & MOTHERBOARD [Proposal 3.3 Poin 2] ---
    if ($var == "motherboard" && isset($assignment["cpu"])) {
        if ($value["socket"] != $assignment["cpu"]["socket"]) {
            return false;
        }
    }
    if ($var == "cpu" && isset($assignment["motherboard"])) {
        if ($value["socket"] != $assignment["motherboard"]["socket"]) {
            return false;
        }
    }

    // --- 4. TEKNIS: RAM & MOTHERBOARD [Proposal 3.3 Poin 3] ---
    if ($var == "ram" && isset($assignment["motherboard"])) {
        if ($value["type"] != $assignment["motherboard"]["ram_type"]) {
            return false;
        }
    }
    if ($var == "motherboard" && isset($assignment["ram"])) {
        if ($value["ram_type"] != $assignment["ram"]["type"]) {
            return false;
        }
    }

    // --- 5. TEKNIS: PSU WATTAGE [Proposal 3.3 Poin 4] ---
    // Rumus: PSU >= CPU.TDP + GPU.TDP + 50W Buffer
    if ($var == "psu" && isset($assignment["cpu"]) && isset($assignment["gpu"])) {
        $needed = $assignment["cpu"]["tdp"] + $assignment["gpu"]["tdp"] + 50;
        if ($value["wattage"] < $needed) {
            return false;
        }
    }
    
    // Cek kebalikan (jika PSU dipilih duluan sebelum CPU/GPU)
    $current_tdp = 0;
    if (isset($assignment["cpu"])) {
        $current_tdp += $assignment["cpu"]["tdp"];
    }
    if (isset($assignment["gpu"])) {
        $current_tdp += $assignment["gpu"]["tdp"];
    }
    // Tambahkan TDP komponen yg sedang dicek jika dia CPU/GPU
    if ($var == "cpu") {
        $current_tdp += $value["tdp"];
    }
    if ($var == "gpu") {
        $current_tdp += $value["tdp"];
    }
    
    if (isset($assignment["psu"]) && ($var == "cpu" || $var == "gpu")) {
        if ($assignment["psu"]["wattage"] < ($current_tdp + 50)) {
            return false;
        }
    }

    // --- 6. TEKNIS: GPU SIZE VS CASING [Proposal 3.3 Poin 5] ---
    if ($var == "casing" && isset($assignment["gpu"])) {
        if ($assignment["gpu"]["length"] > $value["max_gpu_length"]) {
            return false;
        }
    }
    if ($var == "gpu" && isset($assignment["casing"])) {
        if ($value["length"] > $assignment["casing"]["max_gpu_length"]) {
            return false;
        }
    }

    // --- 7. TEKNIS: STORAGE INTERFACE [Proposal 3.3 Poin 6] ---
    // (Sederhana: Asumsi motherboard support SATA & NVMe, 
    // tapi bisa ditambahkan constraint slot jika data motherboard lengkap)
    
    return true; // Jika lolos semua tes, berarti VALID
}

/**
 * Implementasi Algoritma Backtracking (Sesuai Proposal Bab 5.2)
 */
function backtracking_search($assignment, $user_budget, $user_tier, $components, $variables) {
    
    // 1. GOAL TEST: Apakah semua variabel sudah terisi? [Proposal Bab 5.2 Step 1]
    if (count($assignment) == count($variables)) {
        return $assignment;
    }

    // 2. SELECT VARIABLE: Pilih variabel yang belum ada di assignment [Proposal Bab 5.2 Step 2]
    $unassigned = [];
    foreach ($variables as $v) {
        if (!isset($assignment[$v])) {
            $unassigned[] = $v;
        }
    }
    $var = $unassigned[0]; // Pilih yang pertama sesuai urutan prioritas

    // 3. ORDER VALUES: Ambil domain nilai dari database [Proposal Bab 5.2 Step 3]
    // Tips Optimasi: Kita sort dari yang termahal dulu jika budget besar, 
    // atau termurah dulu. Disini kita acak/default dari database generator.
    $domain_values = $components[$var];
    
    // Kita coba sort domain berdasarkan harga (High to Low) agar dapat spek terbaik 
    // yang muat di budget (Greedy approach)
    usort($domain_values, function($a, $b) {
        return $b['price'] - $a['price'];
    });

    foreach ($domain_values as $value) {
        // 4. CONSTRAINT CHECK [Proposal Bab 5.2 Step 4]
        if (is_consistent($var, $value, $assignment, $user_budget, $user_tier)) {
            
            // Assign Nilai
            $assignment[$var] = $value;
            
            // Recursive Call (Panggil diri sendiri untuk variabel berikutnya)
            $result = backtracking_search($assignment, $user_budget, $user_tier, $components, $variables);
            
            // Jika result bukan failure, kembalikan result
            if ($result !== null) {
                return $result;
            }
            
            // Backtracking Step: Hapus assignment jika jalan buntu [Proposal Bab 5.2 Step Backtrack]
            unset($assignment[$var]);
        }
    }
    
    return null; // Return Failure jika tidak ada solusi
}

function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// --- PROSES FORM INPUT ---
$solution = null;
$execution_time = 0;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["budget"])) {
    $budget = intval($_POST["budget"]);
    $tier_input = strtolower(trim($_POST["tier"] ?? "office"));
    
    // Mapping input user ke tier data
    $target_tier = "low";
    if (in_array($tier_input, ["gaming", "editing", "render"])) {
        if ($budget > 15000000) {
            $target_tier = "high";
        } else {
            $target_tier = "mid";
        }
    }
    
    if ($budget > 0) {
        $start_time = microtime(true);
        
        // Jalankan Algoritma
        $solution = backtracking_search([], $budget, $target_tier, $components, $VARIABLES);
        
        $end_time = microtime(true);
        $execution_time = $end_time - $start_time;
        
        // Generate AI Insight jika solution ditemukan
        $ai_insight = null;
        if ($solution !== null) {
            $ai_insight = generate_ai_insight($solution, $budget, $target_tier);
            // Jika AI tidak tersedia atau gagal, gunakan default insight
            if ($ai_insight === false) {
                $ai_insight = get_default_insight($solution, $budget, $target_tier);
            }
        }
    } else {
        $error_message = "Budget harus lebih dari 0!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC-Builder AI - Sistem Rekomendasi Rakit PC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: none;
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .component-item {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .component-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1em;
        }
        .component-price {
            color: #667eea;
            font-weight: 600;
        }
        .alert-success {
            border-radius: 10px;
            border: none;
        }
        .alert-danger {
            border-radius: 10px;
            border: none;
        }
        .stats-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .ai-insight-content {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .ai-insight-content p {
            color: #444;
            font-size: 0.95em;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="card">
            <div class="card-header text-center">
                <h1 class="mb-0">
                    <i class="bi bi-cpu"></i> PC-Builder AI
                </h1>
                <p class="mb-0 mt-2">Sistem Rekomendasi Rakit PC dengan Constraint Satisfaction Problem</p>
            </div>
            <div class="card-body p-4">
                <!-- Form Input -->
                <form method="POST" action="">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="budget" class="form-label">
                                <i class="bi bi-currency-dollar"></i> Budget Maksimal
                            </label>
                            <input type="number" class="form-control form-control-lg" 
                                   id="budget" name="budget" 
                                   placeholder="Contoh: 15000000" 
                                   value="<?php echo isset($_POST['budget']) ? htmlspecialchars($_POST['budget']) : ''; ?>" 
                                   required>
                            <small class="text-muted">Masukkan budget dalam Rupiah (tanpa titik/koma)</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tier" class="form-label">
                                <i class="bi bi-sliders"></i> Kebutuhan
                            </label>
                            <select class="form-select form-select-lg" id="tier" name="tier" required>
                                <option value="office" <?php echo (isset($_POST['tier']) && $_POST['tier'] == 'office') ? 'selected' : ''; ?>>Office / Productivity</option>
                                <option value="gaming" <?php echo (isset($_POST['tier']) && $_POST['tier'] == 'gaming') ? 'selected' : ''; ?>>Gaming</option>
                                <option value="editing" <?php echo (isset($_POST['tier']) && $_POST['tier'] == 'editing') ? 'selected' : ''; ?>>Editing / Rendering</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-search"></i> Cari Rakitan Terbaik
                        </button>
                    </div>
                </form>

                <!-- Hasil Pencarian -->
                <?php if ($solution !== null): ?>
                    <div class="alert alert-success mt-4" role="alert">
                        <h4 class="alert-heading">
                            <i class="bi bi-check-circle-fill"></i> Rakitan Ditemukan!
                        </h4>
                        <hr>
                        
                        <?php
                        $total_price = 0;
                        $total_wattage = 0;
                        $component_labels = [
                            "cpu" => "CPU",
                            "motherboard" => "Motherboard",
                            "gpu" => "GPU",
                            "ram" => "RAM",
                            "psu" => "PSU",
                            "storage" => "Storage",
                            "casing" => "Casing"
                        ];
                        ?>
                        
                        <?php foreach ($VARIABLES as $var): ?>
                            <?php if (isset($solution[$var])): ?>
                                <?php 
                                $item = $solution[$var];
                                $total_price += $item['price'];
                                if (in_array($var, ['cpu', 'gpu'])) {
                                    $total_wattage += $item['tdp'];
                                }
                                ?>
                                <div class="component-item">
                                    <div class="component-name">
                                        <i class="bi bi-pc-display"></i> 
                                        <?php echo htmlspecialchars($component_labels[$var]); ?>
                                    </div>
                                    <div class="mt-2">
                                        <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                    </div>
                                    <div class="component-price mt-1">
                                        <?php echo format_rupiah($item['price']); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <div class="stats-box">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <h5><i class="bi bi-currency-dollar"></i> Total Harga</h5>
                                    <h3><?php echo format_rupiah($total_price); ?></h3>
                                </div>
                                <div class="col-md-4">
                                    <h5><i class="bi bi-lightning-charge"></i> Estimasi Daya</h5>
                                    <h3><?php echo $total_wattage; ?>W</h3>
                                    <small>PSU: <?php echo $solution['psu']['wattage']; ?>W</small>
                                </div>
                                <div class="col-md-4">
                                    <h5><i class="bi bi-stopwatch"></i> Waktu Pencarian</h5>
                                    <h3><?php echo number_format($execution_time, 4); ?>s</h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 p-4 bg-light rounded border-start border-4 border-primary">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-robot fs-3 text-primary me-2"></i>
                                <h5 class="mb-0">
                                    <strong>AI Insight & Analisis</strong>
                                    <?php 
                                    $ai_enabled = defined('ENABLE_AI') && ENABLE_AI;
                                    $ai_provider = defined('AI_PROVIDER') ? AI_PROVIDER : 'none';
                                    if ($ai_enabled && $ai_provider != 'none'): ?>
                                        <span class="badge bg-success ms-2">Powered by <?php echo strtoupper($ai_provider); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary ms-2">Default Insight</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                            <div class="ai-insight-content">
                                <?php 
                                if (isset($ai_insight)) {
                                    // Format teks AI dengan line breaks
                                    $formatted_insight = nl2br(htmlspecialchars($ai_insight));
                                    echo "<p class='mb-0' style='line-height: 1.8;'>{$formatted_insight}</p>";
                                } else {
                                    // Fallback jika tidak ada insight
                                    echo "<p class='mb-0'>Rakit ini menggunakan <strong>" . htmlspecialchars($solution['cpu']['name']) . "</strong> 
                                          dan <strong>" . htmlspecialchars($solution['gpu']['name']) . "</strong>.
                                          Sangat cocok untuk kebutuhan Anda. Pastikan airflow casing bagus!</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                    <div class="alert alert-danger mt-4" role="alert">
                        <h4 class="alert-heading">
                            <i class="bi bi-exclamation-triangle-fill"></i> Gagal Menemukan Rakitan
                        </h4>
                        <p>
                            <?php echo !empty($error_message) ? $error_message : "Tidak dapat menemukan rakitan yang sesuai dengan budget dan kebutuhan Anda."; ?>
                        </p>
                        <hr>
                        <p class="mb-0">
                            <strong>Saran:</strong> Naikkan budget atau kurangi spesifikasi kebutuhan.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Info Tentang Sistem -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Tentang Sistem</h5>
                <p class="card-text">
                    Sistem ini menggunakan <strong>Constraint Satisfaction Problem (CSP)</strong> dengan algoritma 
                    <strong>Backtracking Search</strong> untuk menemukan kombinasi komponen PC yang optimal.
                </p>
                <h6>Constraints yang Diterapkan:</h6>
                <ul>
                    <li><strong>Budget:</strong> Total harga tidak melebihi budget</li>
                    <li><strong>Socket:</strong> CPU dan Motherboard harus kompatibel</li>
                    <li><strong>RAM Type:</strong> RAM dan Motherboard harus kompatibel (DDR4/DDR5)</li>
                    <li><strong>PSU Wattage:</strong> PSU harus cukup untuk CPU + GPU + 50W buffer</li>
                    <li><strong>GPU Size:</strong> GPU harus muat di dalam casing</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

