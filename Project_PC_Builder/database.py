import random

# --- KONFIGURASI GENERATOR ---
# Kita buat template spesifikasi dasar agar logic constraint tetap aman.
# Script di bawah akan mengalikan ini dengan berbagai merk.

brands_gpu = ["Asus", "MSI", "Gigabyte", "Zotac", "Palit", "Galax"]
brands_mobo = ["Asus", "MSI", "Gigabyte", "Asrock"]
brands_ram = ["Corsair", "Kingston", "G.Skill", "TeamGroup", "Adata", "V-Gen"]
brands_psu = ["Corsair", "FSP", "CoolerMaster", "Seasonic", "BeQuiet", "MSI"]
brands_case = ["Cube Gaming", "Paradox", "NZXT", "Lian Li", "Tecware", "Corsair"]
brands_storage = ["Samsung", "WD", "Seagate", "Adata", "Kingston", "TeamGroup"]

# --- DATA TEMPLATE (SPESIFIKASI TEKNIS) ---
# CPU (Tidak ada merk selain Intel/AMD, jadi kita list manual yang banyak)
cpu_templates = [
    # Intel Gen 12
    {"name": "Intel Core i3-12100F", "price": 1350000, "socket": "LGA1700", "tdp": 58, "tier": "mid"},
    {"name": "Intel Core i3-12100", "price": 1900000, "socket": "LGA1700", "tdp": 60, "tier": "mid"},
    {"name": "Intel Core i5-12400F", "price": 2200000, "socket": "LGA1700", "tdp": 65, "tier": "mid"},
    {"name": "Intel Core i5-12400", "price": 2800000, "socket": "LGA1700", "tdp": 65, "tier": "mid"},
    {"name": "Intel Core i7-12700F", "price": 4500000, "socket": "LGA1700", "tdp": 125, "tier": "high"},
    # Intel Gen 13 & 14
    {"name": "Intel Core i3-13100F", "price": 1800000, "socket": "LGA1700", "tdp": 58, "tier": "mid"},
    {"name": "Intel Core i5-13400F", "price": 3300000, "socket": "LGA1700", "tdp": 65, "tier": "mid"},
    {"name": "Intel Core i5-13500", "price": 3900000, "socket": "LGA1700", "tdp": 65, "tier": "high"},
    {"name": "Intel Core i5-13600K", "price": 5100000, "socket": "LGA1700", "tdp": 125, "tier": "high"},
    {"name": "Intel Core i5-14400F", "price": 3500000, "socket": "LGA1700", "tdp": 65, "tier": "mid"},
    {"name": "Intel Core i7-14700K", "price": 6800000, "socket": "LGA1700", "tdp": 125, "tier": "high"},
    # AMD AM4
    {"name": "AMD Ryzen 3 4100", "price": 1100000, "socket": "AM4", "tdp": 65, "tier": "low"},
    {"name": "AMD Ryzen 5 4500", "price": 1300000, "socket": "AM4", "tdp": 65, "tier": "low"},
    {"name": "AMD Ryzen 5 5500", "price": 1500000, "socket": "AM4", "tdp": 65, "tier": "mid"},
    {"name": "AMD Ryzen 5 5600G", "price": 1900000, "socket": "AM4", "tdp": 65, "tier": "mid"},
    {"name": "AMD Ryzen 5 5600", "price": 1950000, "socket": "AM4", "tdp": 65, "tier": "mid"},
    {"name": "AMD Ryzen 7 5700X", "price": 2800000, "socket": "AM4", "tdp": 65, "tier": "mid"},
    {"name": "AMD Ryzen 7 5800X3D", "price": 5200000, "socket": "AM4", "tdp": 105, "tier": "high"},
    # AMD AM5
    {"name": "AMD Ryzen 5 7500F", "price": 2700000, "socket": "AM5", "tdp": 65, "tier": "mid"},
    {"name": "AMD Ryzen 5 7600", "price": 3300000, "socket": "AM5", "tdp": 65, "tier": "high"},
    {"name": "AMD Ryzen 7 7700", "price": 5200000, "socket": "AM5", "tdp": 65, "tier": "high"},
    {"name": "AMD Ryzen 7 7800X3D", "price": 6500000, "socket": "AM5", "tdp": 120, "tier": "high"},
]

gpu_templates = [
    {"base": "GTX 1650", "p": 2100000, "tdp": 75, "len": 190, "tier": "low"},
    {"base": "RX 6500 XT", "p": 2300000, "tdp": 107, "len": 190, "tier": "low"},
    {"base": "RX 6600", "p": 3100000, "tdp": 132, "len": 220, "tier": "mid"},
    {"base": "RTX 3050", "p": 3300000, "tdp": 130, "len": 220, "tier": "mid"},
    {"base": "RTX 3060 12GB", "p": 4400000, "tdp": 170, "len": 240, "tier": "mid"},
    {"base": "RTX 4060", "p": 4800000, "tdp": 115, "len": 240, "tier": "mid"},
    {"base": "RX 7600", "p": 4500000, "tdp": 165, "len": 240, "tier": "mid"},
    {"base": "RTX 4060 Ti", "p": 6500000, "tdp": 160, "len": 250, "tier": "high"},
    {"base": "RX 7700 XT", "p": 7500000, "tdp": 245, "len": 270, "tier": "high"},
    {"base": "RTX 4070 Super", "p": 10500000, "tdp": 220, "len": 280, "tier": "high"},
]

mobo_templates = [
    {"base": "H610M", "p": 1100000, "s": "LGA1700", "ram": "DDR4", "ff": "mATX"},
    {"base": "B760M", "p": 2100000, "s": "LGA1700", "ram": "DDR4", "ff": "mATX"},
    {"base": "B760M D5", "p": 2500000, "s": "LGA1700", "ram": "DDR5", "ff": "mATX"},
    {"base": "Z790", "p": 4500000, "s": "LGA1700", "ram": "DDR5", "ff": "ATX"},
    {"base": "A520M", "p": 950000, "s": "AM4", "ram": "DDR4", "ff": "mATX"},
    {"base": "B450M", "p": 1200000, "s": "AM4", "ram": "DDR4", "ff": "mATX"},
    {"base": "B550M", "p": 1600000, "s": "AM4", "ram": "DDR4", "ff": "mATX"},
    {"base": "A620M", "p": 1800000, "s": "AM5", "ram": "DDR5", "ff": "mATX"},
    {"base": "B650M", "p": 2400000, "s": "AM5", "ram": "DDR5", "ff": "mATX"},
    {"base": "X670", "p": 4800000, "s": "AM5", "ram": "DDR5", "ff": "ATX"},
]

ram_templates = [
    {"base": "8GB DDR4 3200MHz", "p": 350000, "type": "DDR4", "size": 8},
    {"base": "16GB (2x8) DDR4 3200MHz", "p": 650000, "type": "DDR4", "size": 16},
    {"base": "32GB (2x16) DDR4 3600MHz", "p": 1200000, "type": "DDR4", "size": 32},
    {"base": "16GB (2x8) DDR5 5200MHz", "p": 1000000, "type": "DDR5", "size": 16},
    {"base": "32GB (2x16) DDR5 6000MHz", "p": 1900000, "type": "DDR5", "size": 32},
]

psu_templates = [
    {"base": "450W 80+", "p": 500000, "w": 450},
    {"base": "550W 80+ Bronze", "p": 700000, "w": 550},
    {"base": "650W 80+ Bronze", "p": 900000, "w": 650},
    {"base": "750W 80+ Gold", "p": 1500000, "w": 750},
    {"base": "850W 80+ Gold", "p": 2100000, "w": 850},
]

storage_templates = [
    {"base": "SATA SSD 256GB", "p": 300000, "if": "SATA"},
    {"base": "SATA SSD 512GB", "p": 550000, "if": "SATA"},
    {"base": "NVMe Gen3 512GB", "p": 600000, "if": "NVMe"},
    {"base": "NVMe Gen4 1TB", "p": 1100000, "if": "NVMe"},
    {"base": "NVMe Gen4 2TB", "p": 2100000, "if": "NVMe"},
]

case_templates = [
    {"base": "Office mATX Case", "p": 350000, "max_gpu": 260, "sup": ["mATX", "ITX"]},
    {"base": "Gaming mATX Mesh", "p": 550000, "max_gpu": 320, "sup": ["mATX", "ITX"]},
    {"base": "Mid Tower Glass", "p": 800000, "max_gpu": 340, "sup": ["ATX", "mATX", "ITX"]},
    {"base": "Premium Airflow Case", "p": 1500000, "max_gpu": 400, "sup": ["ATX", "mATX", "ITX"]},
]

# --- FUNGSI GENERATOR ---
def generate_data():
    data = {
        "cpu": cpu_templates, # CPU pakai template langsung karena tidak ada merk lain
        "gpu": [], "motherboard": [], "ram": [], "psu": [], "storage": [], "casing": []
    }

    # Generate GPU
    for tmpl in gpu_templates:
        for brand in brands_gpu:
            # Variasi harga acak +- 10% biar realistis
            price_variation = tmpl["p"] + random.randint(-100000, 200000)
            data["gpu"].append({
                "name": f"{brand} {tmpl['base']}",
                "price": price_variation,
                "tdp": tmpl["tdp"],
                "length": tmpl["len"],
                "tier": tmpl["tier"]
            })

    # Generate Mobo
    for tmpl in mobo_templates:
        for brand in brands_mobo:
            price_variation = tmpl["p"] + random.randint(-50000, 150000)
            data["motherboard"].append({
                "name": f"{brand} {tmpl['base']}",
                "price": price_variation,
                "socket": tmpl["s"],
                "ram_type": tmpl["ram"],
                "form_factor": tmpl["ff"]
            })

    # Generate RAM
    for tmpl in ram_templates:
        for brand in brands_ram:
            price_variation = tmpl["p"] + random.randint(-20000, 100000)
            data["ram"].append({
                "name": f"{brand} {tmpl['base']}",
                "price": price_variation,
                "type": tmpl["type"],
                "size": tmpl["size"]
            })

    # Generate PSU
    for tmpl in psu_templates:
        for brand in brands_psu:
            data["psu"].append({
                "name": f"{brand} {tmpl['base']}",
                "price": tmpl["p"] + random.randint(-50000, 100000),
                "wattage": tmpl["w"]
            })
            
    # Generate Storage
    for tmpl in storage_templates:
        for brand in brands_storage:
            data["storage"].append({
                "name": f"{brand} {tmpl['base']}",
                "price": tmpl["p"] + random.randint(-20000, 50000),
                "interface": tmpl["if"]
            })
            
    # Generate Case
    for tmpl in case_templates:
        for brand in brands_case:
            data["casing"].append({
                "name": f"{brand} {tmpl['base']}",
                "price": tmpl["p"] + random.randint(-50000, 200000),
                "max_gpu_length": tmpl["max_gpu"],
                "form_support": tmpl["sup"]
            })

    return data

# Variable utama yang akan diimport oleh main.py
components = generate_data()

# Uncomment baris di bawah ini kalau mau liat total datanya pas dijalankan
# print(f"Total CPU: {len(components['cpu'])}")
# print(f"Total GPU: {len(components['gpu'])}")
# print(f"Total Mobo: {len(components['motherboard'])}")