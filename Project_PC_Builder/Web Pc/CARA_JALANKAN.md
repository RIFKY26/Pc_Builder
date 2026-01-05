# Cara Menjalankan PC-Builder AI

Panduan lengkap cara menjalankan aplikasi PC-Builder AI di XAMPP.

## 📋 Prerequisites (Yang Diperlukan)

1. **XAMPP** sudah terinstall
   - Download: https://www.apachefriends.org/
   - Versi: XAMPP untuk Windows (PHP 7.4+)

2. **Browser** (Chrome, Firefox, Edge, dll)

---

## 🚀 Langkah 1: Setup XAMPP

### 1.1. Install XAMPP (jika belum)
1. Download XAMPP dari https://www.apachefriends.org/
2. Install XAMPP (default: `C:\xampp`)
3. Pastikan Apache dan MySQL terinstall

### 1.2. Copy Project ke htdocs
1. Buka folder XAMPP (biasanya `C:\xampp\htdocs`)
2. Copy folder `Web Pc` ke dalam `htdocs`
   - Hasil: `C:\xampp\htdocs\Web Pc\`
3. Pastikan semua file ada di dalam folder tersebut

### 1.3. Jalankan XAMPP
1. Buka **XAMPP Control Panel**
2. Klik **Start** pada **Apache**
   - Status akan berubah menjadi hijau jika berhasil
3. (Opsional) Start **MySQL** jika diperlukan

---

## 🌐 Langkah 2: Akses Aplikasi

### 2.1. Buka Browser
1. Buka browser (Chrome, Firefox, Edge, dll)
2. Ketik di address bar:
   ```
   http://localhost/Web%20Pc/
   ```
   atau
   ```
   http://localhost/Web Pc/
   ```

### 2.2. Halaman Utama
- Jika berhasil, Anda akan melihat halaman **PC-Builder AI**
- Ada form untuk input budget dan kebutuhan

---

## 🧪 Langkah 3: Test Aplikasi (Tanpa AI)

### 3.1. Test Pencarian Rakitan
1. Di halaman utama, masukkan:
   - **Budget**: `15000000` (15 juta)
   - **Kebutuhan**: Pilih "Gaming"
2. Klik tombol **"Cari Rakitan Terbaik"**
3. Tunggu beberapa detik
4. Hasil akan muncul dengan:
   - Daftar komponen PC
   - Total harga
   - Estimasi daya
   - Waktu pencarian
   - **AI Insight** (masih default/statis)

### 3.2. Cek Hasil
- Jika muncul daftar komponen, berarti aplikasi berjalan dengan baik
- Jika error, cek bagian troubleshooting di bawah

---

## 🤖 Langkah 4: Test AI (Gemini)

### 4.1. Test API Key
1. Buka: `http://localhost/Web%20Pc/test_api_key.php`
2. Cek status:
   - ✅ API key terdefinisi
   - ✅ Format valid
   - ✅ Header siap dikirim

### 4.2. Test Koneksi AI
1. Buka: `http://localhost/Web%20Pc/debug_ai.php`
2. File ini akan:
   - Test koneksi ke Gemini API
   - Menampilkan hasil AI jika berhasil
   - Menampilkan error jika gagal

**Jika berhasil**, Anda akan melihat:
- ✅ SUKSES! AI merespons
- Hasil AI yang panjang dan detail

### 4.3. Test di Aplikasi Utama
1. Kembali ke: `http://localhost/Web%20Pc/`
2. Masukkan budget dan cari rakitan lagi
3. Scroll ke bagian bawah hasil
4. Lihat bagian **"AI Insight & Analisis"**

**Yang harus Anda lihat:**
- ✅ Badge **HIJAU**: "Powered by GEMINI"
- ✅ Teks insight **PANJANG** dan detail (dari AI)
- ✅ Styling dengan border biru di kiri

**Jika masih badge abu-abu "Default Insight":**
- Cek koneksi internet
- Buka `debug_ai.php` untuk lihat error
- Pastikan API key sudah benar di `ai_config.php`

---

## 📝 Checklist Sebelum Demo

Pastikan semua sudah ✅:

- [ ] XAMPP sudah terinstall
- [ ] Apache sudah running (hijau di XAMPP Control Panel)
- [ ] Folder `Web Pc` sudah di `C:\xampp\htdocs\`
- [ ] Bisa akses `http://localhost/Web%20Pc/`
- [ ] Form input budget berfungsi
- [ ] Pencarian rakitan berhasil
- [ ] API key Gemini sudah diisi di `ai_config.php`
- [ ] `AI_PROVIDER = 'gemini'` di `ai_config.php`
- [ ] `ENABLE_AI = true` di `ai_config.php`
- [ ] Test di `debug_ai.php` berhasil
- [ ] Badge "Powered by GEMINI" muncul di hasil

---

## ❌ Troubleshooting

### Masalah 1: Halaman tidak muncul (404)

**Penyebab:**
- Folder tidak ada di htdocs
- Path salah

**Solusi:**
1. Cek apakah folder ada di `C:\xampp\htdocs\Web Pc\`
2. Cek apakah file `index.php` ada di dalamnya
3. Pastikan path di browser benar: `http://localhost/Web%20Pc/`

### Masalah 2: Apache tidak bisa start

**Penyebab:**
- Port 80 sudah digunakan
- XAMPP belum terinstall dengan benar

**Solusi:**
1. Tutup aplikasi yang menggunakan port 80 (Skype, IIS, dll)
2. Atau ubah port Apache di XAMPP Control Panel → Config → Apache → httpd.conf
3. Ubah `Listen 80` menjadi `Listen 8080`
4. Akses dengan: `http://localhost:8080/Web%20Pc/`

### Masalah 3: Error PHP

**Penyebab:**
- PHP version tidak support
- Extension tidak aktif

**Solusi:**
1. Pastikan PHP 7.4+ (cek di XAMPP Control Panel)
2. Pastikan cURL extension aktif (biasanya sudah default)
3. Cek error log di: `C:\xampp\php\logs\php_error_log`

### Masalah 4: AI tidak muncul (masih Default Insight)

**Penyebab:**
- API key tidak ter-load
- Koneksi internet tidak ada
- API key tidak valid

**Solusi:**
1. Buka `test_api_key.php` → cek apakah API key ter-load
2. Buka `debug_ai.php` → lihat error detail
3. Cek koneksi internet
4. Pastikan API key di `ai_config.php` sudah benar
5. Pastikan `AI_PROVIDER = 'gemini'` dan `ENABLE_AI = true`

### Masalah 5: "Missing bearer or basic authentication"

**Penyebab:**
- API key tidak terkirim dengan benar
- API key ada spasi di awal/akhir

**Solusi:**
1. Buka `ai_config.php`
2. Pastikan API key tidak ada spasi di awal/akhir
3. Pastikan format: `define('GEMINI_API_KEY', 'AIzaSy...');`
4. Simpan file dan refresh browser

---

## 🎯 Quick Start (Ringkas)

**Untuk menjalankan cepat:**

1. **Start XAMPP Apache**
   - Buka XAMPP Control Panel
   - Klik Start pada Apache

2. **Buka Browser**
   - Ketik: `http://localhost/Web%20Pc/`

3. **Test Aplikasi**
   - Masukkan budget: `15000000`
   - Pilih: Gaming
   - Klik: "Cari Rakitan Terbaik"

4. **Test AI**
   - Buka: `http://localhost/Web%20Pc/debug_ai.php`
   - Lihat hasil AI

**Selesai!** 🎉

---

## 📞 Jika Masih Bermasalah

1. **Cek Error Log**
   - Buka: `C:\xampp\php\logs\php_error_log`
   - Lihat error terakhir

2. **Test File Individual**
   - `test_api_key.php` → Test API key
   - `debug_ai.php` → Test AI connection
   - `index.php` → Test aplikasi utama

3. **Cek Konfigurasi**
   - Pastikan `ai_config.php` sudah benar
   - Pastikan semua file ada

4. **Restart Apache**
   - Stop Apache di XAMPP
   - Start lagi

---

## ✅ Expected Result

**Jika semua berjalan dengan baik:**

1. **Halaman Utama:**
   - Form input budget dan kebutuhan
   - Tombol "Cari Rakitan Terbaik"

2. **Hasil Pencarian:**
   - Daftar 7 komponen (CPU, Motherboard, GPU, RAM, PSU, Storage, Casing)
   - Total harga, estimasi daya, waktu pencarian
   - **AI Insight dengan badge hijau "Powered by GEMINI"**
   - Teks insight panjang dan detail dari AI

3. **Debug Tools:**
   - `test_api_key.php` → Semua ✅
   - `debug_ai.php` → ✅ SUKSES! AI merespons

---

**Selamat mencoba!** 🚀

