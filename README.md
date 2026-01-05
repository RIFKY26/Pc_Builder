# PC-Builder AI - Sistem Rekomendasi Rakit PC Cerdas

Sistem rekomendasi rakitan PC cerdas yang menggunakan pendekatan **Constraint Satisfaction Problem (CSP)** dengan algoritma **Backtracking Search** untuk menemukan kombinasi komponen paling optimal berdasarkan budget dan kebutuhan pengguna.

## 📝 Deskripsi Proyek
Proyek ini dirancang untuk mempermudah proses pemilihan komponen PC yang seringkali membingungkan bagi pemula karena masalah kompatibilitas. Dengan memasukkan budget dan target penggunaan, sistem akan secara otomatis memvalidasi ribuan kemungkinan kombinasi untuk memberikan hasil yang paling efisien secara teknis dan biaya.

## 🚀 Fitur Utama
* **Pencarian Otomatis**: Menemukan kombinasi 7 komponen utama (CPU, GPU, RAM, Motherboard, PSU, Storage, Casing) secara instan.
* **Validasi Constraint Real-time**: Memastikan kecocokan socket, tipe RAM, dimensi fisik komponen, hingga kapasitas daya.
* **Integrasi Generative AI**: Memberikan analisis mendalam mengenai performa rakitan melalui Google Gemini, OpenAI, atau Claude.
* **Informasi Teknis Detail**: Menampilkan total harga, estimasi konsumsi daya (TDP), dan waktu eksekusi algoritma.
* **Interface Modern**: Tampilan web yang responsif menggunakan Bootstrap 5.

## 🛠️ Spesifikasi Sistem (Metode CSP)
Sistem ini memodelkan perakitan PC sebagai masalah kepuasan batasan dengan detail berikut:

### 1. Variabel & Domain
* **Variabel**: CPU, Motherboard, GPU, RAM, PSU, Storage, Casing.
* **Domain**: Data produk nyata yang tersimpan dalam `database.php`.

### 2. Batasan (Constraints)
* **Budget**: Total harga seluruh komponen tidak boleh melebihi input user.
* **Kompatibilitas Socket**: Socket CPU harus sesuai dengan Motherboard.
* **Tipe RAM**: Dukungan tipe DDR4/DDR5 pada Motherboard harus sesuai dengan RAM yang dipilih.
* **Kecukupan Daya**: Kapasitas PSU harus $\geq$ (TDP CPU + TDP GPU + 50W Buffer).
* **Dimensi Fisik**: Panjang GPU tidak boleh melebihi kapasitas ruang dalam Casing.
* **Tier Kebutuhan**: Komponen disesuaikan dengan profil pengguna (Office, Gaming, atau Rendering).

## 💻 Cara Instalasi & Menjalankan

### Persyaratan
1. **XAMPP** terinstall (PHP 7.4 ke atas).
2. **Web Browser** (Chrome/Firefox/Edge).

### Langkah-langkah
1. Copy folder `Web Pc` ke direktori `C:\xampp\htdocs\`.
2. Buka **XAMPP Control Panel** dan klik **Start** pada **Apache**.
3. Buka browser dan ketik alamat: `http://localhost/Web Pc/`.
4. Masukkan budget (contoh: 15000000) dan pilih kebutuhan.
5. Klik **"Cari Rakitan Terbaik"**.

## 🤖 Konfigurasi Generative AI (Opsional)
Untuk mendapatkan analisis berbasis AI, lakukan langkah berikut:
1. Dapatkan API Key dari [Google AI Studio](https://aistudio.google.com/app/apikey) (Gemini) atau [OpenAI Platform](https://platform.openai.com/).
2. Buka file `ai_config.php`.
3. Set `ENABLE_AI` menjadi `true`.
4. Masukkan API Key Anda pada variabel provider yang sesuai (contoh: `GEMINI_API_KEY`).

## 📂 Struktur File Utama
* `index.php`: Logika utama algoritma Backtracking dan antarmuka web.
* `database.php`: Dataset komponen PC.
* `ai_service.php`: Integrasi API dengan layanan Generative AI.
* `ai_config.php`: Pengaturan kunci API dan model AI.
* `debug_ai.php`: Alat untuk menguji konektivitas AI.

---
*Dikembangkan sebagai Tugas Besar Mata Kuliah Kecerdasan Buatan.*
