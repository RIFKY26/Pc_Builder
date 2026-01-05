# PC-Builder AI - Sistem Rekomendasi Rakit PC

Sistem rekomendasi rakitan PC menggunakan **Constraint Satisfaction Problem (CSP)** dengan algoritma **Backtracking Search**.

## Spesifikasi Teknis

- **Metode**: Constraint Satisfaction Problem (CSP) dengan Backtracking Search
- **Bahasa**: PHP Native (tanpa framework/library eksternal)
- **Server**: XAMPP (Apache + PHP)
- **Interface**: Web sederhana dengan HTML/CSS/Bootstrap

## Instalasi

1. Pastikan XAMPP sudah terinstall di komputer Anda
2. Copy folder `Web Pc` ke dalam direktori `htdocs` XAMPP
   - Lokasi default: `C:\xampp\htdocs\Web Pc`
3. Jalankan XAMPP Control Panel
4. Start Apache service
5. Buka browser dan akses: `http://localhost/Web%20Pc/` atau `http://localhost/Web Pc/`

## Struktur File

```
Web Pc/
├── index.php            # Interface web dan logika utama
├── database.php         # Data komponen PC (CPU, GPU, Motherboard, dll)
├── ai_config.php        # Konfigurasi Generative AI (API keys)
├── ai_config.example.php # Template konfigurasi AI
├── ai_service.php      # Service untuk memanggil API Generative AI
├── debug_ai.php        # Tool untuk debug AI configuration
├── test_api_key.php    # Tool untuk test API key
├── README.md           # Dokumentasi ini
├── AI_SETUP.md         # Dokumentasi setup Generative AI
└── .gitignore          # File untuk ignore Git
```

## Formulasi Masalah (CSP)

### Variables (Variabel)
- CPU
- Motherboard
- GPU
- RAM
- PSU
- Storage
- Casing

### Domains (Domain)
Daftar produk nyata dengan spesifikasi lengkap (nama, harga, socket, TDP, dll)

### Constraints (Batasan)

1. **Budget Constraint**: Total harga komponen tidak boleh melebihi input user

2. **Binary Constraints**:
   - CPU Socket == Motherboard Socket
   - RAM Type (DDR4/5) == Motherboard RAM Support
   - GPU Length <= Casing Max GPU Length

3. **N-ary Constraints**:
   - PSU Wattage >= (TDP CPU + TDP GPU + 50W)

4. **User Requirement Constraint**:
   - Jika user memilih tier "high", tidak akan diberikan komponen tier "low"

## Cara Penggunaan

1. Buka aplikasi di browser
2. Masukkan **Budget Maksimal** (dalam Rupiah, tanpa titik/koma)
   - Contoh: `15000000` untuk 15 juta rupiah
3. Pilih **Kebutuhan**:
   - Office / Productivity
   - Gaming
   - Editing / Rendering
4. Klik tombol **"Cari Rakitan Terbaik"**
5. Sistem akan menampilkan rekomendasi rakitan PC yang optimal

## Algoritma Backtracking Search

Algoritma bekerja dengan urutan prioritas:
1. CPU → 2. Motherboard → 3. GPU → 4. RAM → 5. PSU → 6. Storage → 7. Casing

Setiap langkah akan:
- Memilih variabel yang belum terisi
- Mencoba nilai dari domain (diurutkan dari harga tertinggi untuk mendapatkan spek terbaik)
- Mengecek constraint sebelum assign
- Jika tidak valid, backtrack dan coba nilai lain
- Jika semua variabel terisi, return solusi

## Fitur

- ✅ Pencarian otomatis kombinasi komponen yang optimal
- ✅ Validasi constraint real-time
- ✅ Menampilkan total harga dan estimasi daya
- ✅ Menampilkan waktu eksekusi algoritma
- ✅ **Integrasi Generative AI** (ChatGPT, Gemini, Claude) untuk insight yang lebih kaya
- ✅ Interface modern dengan Bootstrap 5
- ✅ Responsive design (mobile-friendly)

## Fitur Generative AI (Opsional)

Sistem mendukung integrasi dengan Generative AI untuk memberikan analisis dan insight yang lebih kaya:

- **OpenAI (ChatGPT)**: GPT-3.5-turbo, GPT-4
- **Google Gemini**: Gemini Pro (free tier tersedia)
- **Anthropic Claude**: Claude 3 Sonnet, Opus, Haiku

**Setup**: Lihat dokumentasi lengkap di [AI_SETUP.md](AI_SETUP.md)

**Catatan**: Fitur AI bersifat opsional. Sistem akan tetap berjalan dengan insight default jika AI tidak dikonfigurasi.

## Catatan

- Data komponen di-generate secara dinamis dengan variasi harga acak
- Algoritma menggunakan pendekatan greedy (sort harga tinggi ke rendah) untuk mendapatkan spek terbaik dalam budget
- Jika tidak ditemukan solusi, coba naikkan budget atau kurangi spesifikasi kebutuhan
- Fitur Generative AI memerlukan API key dari provider yang dipilih (opsional)

## Teknologi yang Digunakan

- PHP 7.4+ (Native, tanpa framework)
- Bootstrap 5.3.0 (CDN)
- Bootstrap Icons 1.10.0 (CDN)

## Lisensi

Project untuk Tugas Besar Mata Kuliah Kecerdasan Buatan.

