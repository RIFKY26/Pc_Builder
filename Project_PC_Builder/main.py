import time
from database import components

# --- KONFIGURASI ALGORITMA ---
# Urutan variabel berpengaruh pada kecepatan pencarian (Heuristic).
# Kita urutkan dari yang paling krusial/banyak constraint:
# CPU -> Motherboard -> GPU -> RAM -> PSU -> Storage -> Casing
VARIABLES = ["cpu", "motherboard", "gpu", "ram", "psu", "storage", "casing"]

def is_consistent(var, value, assignment, user_budget, user_tier):
    """
    Fungsi Pengecekan Constraint (Sesuai Proposal Bab 3.3)
    Mengecek apakah nilai baru (value) valid untuk ditambahkan ke assignment.
    """
    
    # --- 1. CONSTRAINT BUDGET [Proposal 3.3 Poin 1] ---
    # Total harga tidak boleh melebihi budget user
    current_cost = sum(item['price'] for item in assignment.values())
    if current_cost + value['price'] > user_budget:
        return False

    # --- 2. USER REQUIREMENT CONSTRAINT (Optional) [Proposal 3.3 Poin 7] ---
    # Jika user minta High-End, jangan kasih komponen Low-End (agar optimal)
    # Aturan ini hanya berlaku untuk komponen yang punya atribut 'tier'
    if user_tier == "high" and value.get("tier") == "low":
        return False
    
    # --- 3. TEKNIS: CPU & MOTHERBOARD [Proposal 3.3 Poin 2] ---
    if var == "motherboard" and "cpu" in assignment:
        if value["socket"] != assignment["cpu"]["socket"]:
            return False
    if var == "cpu" and "motherboard" in assignment:
        if value["socket"] != assignment["motherboard"]["socket"]:
            return False

    # --- 4. TEKNIS: RAM & MOTHERBOARD [Proposal 3.3 Poin 3] ---
    if var == "ram" and "motherboard" in assignment:
        if value["type"] != assignment["motherboard"]["ram_type"]:
            return False
    if var == "motherboard" and "ram" in assignment:
        if value["ram_type"] != assignment["ram"]["type"]:
            return False

    # --- 5. TEKNIS: PSU WATTAGE [Proposal 3.3 Poin 4] ---
    # Rumus: PSU >= CPU.TDP + GPU.TDP + 50W Buffer
    if var == "psu" and "cpu" in assignment and "gpu" in assignment:
        needed = assignment["cpu"]["tdp"] + assignment["gpu"]["tdp"] + 50
        if value["wattage"] < needed:
            return False
    
    # Cek kebalikan (jika PSU dipilih duluan sebelum CPU/GPU)
    current_tdp = 0
    if "cpu" in assignment: current_tdp += assignment["cpu"]["tdp"]
    if "gpu" in assignment: current_tdp += assignment["gpu"]["tdp"]
    # Tambahkan TDP komponen yg sedang dicek jika dia CPU/GPU
    if var == "cpu": current_tdp += value["tdp"]
    if var == "gpu": current_tdp += value["tdp"]
    
    if "psu" in assignment and (var == "cpu" or var == "gpu"):
        if assignment["psu"]["wattage"] < (current_tdp + 50):
            return False

    # --- 6. TEKNIS: GPU SIZE VS CASING [Proposal 3.3 Poin 5] ---
    if var == "casing" and "gpu" in assignment:
        if assignment["gpu"]["length"] > value["max_gpu_length"]:
            return False
    if var == "gpu" and "casing" in assignment:
        if value["length"] > assignment["casing"]["max_gpu_length"]:
            return False

    # --- 7. TEKNIS: STORAGE INTERFACE [Proposal 3.3 Poin 6] ---
    # (Sederhana: Asumsi motherboard support SATA & NVMe, 
    # tapi bisa ditambahkan constraint slot jika data motherboard lengkap)
    
    return True # Jika lolos semua tes, berarti VALID

def backtracking_search(assignment, user_budget, user_tier):
    """
    Implementasi Algoritma Backtracking (Sesuai Proposal Bab 5.2)
    """
    
    # 1. GOAL TEST: Apakah semua variabel sudah terisi? [Proposal Bab 5.2 Step 1]
    if len(assignment) == len(VARIABLES):
        return assignment

    # 2. SELECT VARIABLE: Pilih variabel yang belum ada di assignment [Proposal Bab 5.2 Step 2]
    unassigned = [v for v in VARIABLES if v not in assignment]
    var = unassigned[0] # Pilih yang pertama sesuai urutan prioritas

    # 3. ORDER VALUES: Ambil domain nilai dari database [Proposal Bab 5.2 Step 3]
    # Tips Optimasi: Kita sort dari yang termahal dulu jika budget besar, 
    # atau termurah dulu. Disini kita acak/default dari database generator.
    domain_values = components[var]
    
    # Kita coba sort domain berdasarkan harga (High to Low) agar dapat spek terbaik 
    # yang muat di budget (Greedy approach)
    domain_values.sort(key=lambda x: x['price'], reverse=True)

    for value in domain_values:
        # 4. CONSTRAINT CHECK [Proposal Bab 5.2 Step 4]
        if is_consistent(var, value, assignment, user_budget, user_tier):
            
            # Assign Nilai
            assignment[var] = value
            
            # Recursive Call (Panggil diri sendiri untuk variabel berikutnya)
            result = backtracking_search(assignment, user_budget, user_tier)
            
            # Jika result bukan failure, kembalikan result
            if result is not None:
                return result
            
            # Backtracking Step: Hapus assignment jika jalan buntu [Proposal Bab 5.2 Step Backtrack]
            del assignment[var]
            
    return None # Return Failure jika tidak ada solusi

def format_rupiah(angka):
    return f"Rp {angka:,.0f}".replace(",", ".")

# --- MAIN PROGRAM (Interface CLI) ---
if __name__ == "__main__":
    print("\n============================================")
    print("   PC-BUILDER AI (Constraint Satisfaction)   ")
    print("============================================")
    
    # Input User
    try:
        budget = int(input("Masukkan Budget Maksimal (Contoh: 15000000): "))
        tier_input = input("Kebutuhan (office/gaming/editing)? ").lower()
        
        # Mapping input user ke tier data
        target_tier = "low"
        if tier_input in ["gaming", "editing", "render"]:
            if budget > 15000000: target_tier = "high"
            else: target_tier = "mid"
        
        print(f"\n🔍 Mencari rakitan PC terbaik untuk budget {format_rupiah(budget)}...")
        print(f"⚙️  Target Tier: {target_tier.upper()}")
        
        start_time = time.time()
        
        # Jalankan Algoritma
        solution = backtracking_search({}, budget, target_tier)
        
        end_time = time.time()
        
        if solution:
            print("\n✅ RAKITAN DITEMUKAN!")
            print("-" * 50)
            total_price = 0
            total_wattage = 0
            
            # Tampilkan per komponen
            for var in VARIABLES:
                item = solution[var]
                print(f"[{var.upper().ljust(11)}] : {item['name']}")
                print(f"              Harga: {format_rupiah(item['price'])}")
                total_price += item['price']
                
                # Hitung total TDP untuk info
                if var in ['cpu', 'gpu']:
                    total_wattage += item['tdp']

            print("-" * 50)
            print(f"💰 Total Harga : {format_rupiah(total_price)}")
            print(f"⚡ Estimasi Daya: {total_wattage}W (PSU: {solution['psu']['wattage']}W)")
            print(f"⏱️ Waktu Search : {end_time - start_time:.4f} detik")
            
            # Bonus: Simulasi Gen-AI (Print statis dulu)
            print("\n🤖 AI Insight:")
            print(f"Rakit ini menggunakan {solution['cpu']['name']} dan {solution['gpu']['name']}.")
            print("Sangat cocok untuk kebutuhan Anda. Pastikan airflow casing bagus!")
            
        else:
            print("\n❌ Gagal menemukan rakitan yang sesuai.")
            print("Saran: Naikkan budget atau kurangi spesifikasi kebutuhan.")
            
    except ValueError:
        print("Error: Masukkan angka budget yang benar.")