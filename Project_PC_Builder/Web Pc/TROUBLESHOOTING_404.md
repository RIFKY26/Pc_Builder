# Troubleshooting Error 404 "Not Found"

Jika Anda mendapatkan error 404 saat mengakses `debug_ai.php` atau `test_api_key.php`, ikuti langkah berikut:

## 🔍 Penyebab Error 404

Error 404 berarti file tidak ditemukan di server. Kemungkinan penyebab:

1. **Folder belum di-copy ke htdocs XAMPP**
2. **Path folder salah**
3. **Nama folder berbeda**

---

## ✅ Solusi 1: Pastikan Folder di htdocs

### Langkah:

1. **Cek lokasi folder saat ini**
   - Folder Anda ada di: `C:\Tugas\AI\Project_PC_Builder\Web Pc\`
   - Ini adalah lokasi project, BUKAN lokasi web server

2. **Copy ke htdocs XAMPP**
   - Buka folder: `C:\xampp\htdocs\`
   - Copy folder `Web Pc` ke dalam `htdocs`
   - Hasil: `C:\xampp\htdocs\Web Pc\`

3. **Pastikan semua file ada**
   - Di dalam `C:\xampp\htdocs\Web Pc\` harus ada:
     - `index.php`
     - `database.php`
     - `ai_config.php`
     - `ai_service.php`
     - `debug_ai.php` ✅
     - `test_api_key.php` ✅
     - dll

---

## ✅ Solusi 2: Cek Path di Browser

### Path yang Benar:

```
http://localhost/Web%20Pc/debug_ai.php
```

atau

```
http://localhost/Web Pc/debug_ai.php
```

### Path yang SALAH:

```
http://localhost/Web%20Pc/debug_ai.php  ← Jika folder tidak ada di htdocs
http://localhost/Project_PC_Builder/Web%20Pc/debug_ai.php  ← SALAH
```

---

## ✅ Solusi 3: Test dengan index.php Dulu

Sebelum test `debug_ai.php`, test dulu apakah aplikasi utama bisa diakses:

1. **Buka:**
   ```
   http://localhost/Web%20Pc/
   ```
   atau
   ```
   http://localhost/Web Pc/index.php
   ```

2. **Jika index.php bisa diakses:**
   - Berarti folder sudah benar di htdocs
   - Masalahnya mungkin di path file debug

3. **Jika index.php juga error 404:**
   - Berarti folder belum di-copy ke htdocs
   - Ikuti Solusi 1 di atas

---

## 🔧 Langkah Perbaikan Lengkap

### Step 1: Copy Folder ke htdocs

1. Buka File Explorer
2. Navigasi ke: `C:\Tugas\AI\Project_PC_Builder\Web Pc\`
3. **Select All** (Ctrl+A) → **Copy** (Ctrl+C)
4. Navigasi ke: `C:\xampp\htdocs\`
5. Buat folder baru: `Web Pc` (jika belum ada)
6. Masuk ke folder tersebut
7. **Paste** (Ctrl+V) semua file

### Step 2: Verifikasi File

Pastikan di `C:\xampp\htdocs\Web Pc\` ada file:
- ✅ `index.php`
- ✅ `database.php`
- ✅ `ai_config.php`
- ✅ `ai_service.php`
- ✅ `debug_ai.php`
- ✅ `test_api_key.php`

### Step 3: Restart Apache

1. Buka XAMPP Control Panel
2. Klik **Stop** pada Apache
3. Tunggu beberapa detik
4. Klik **Start** pada Apache lagi

### Step 4: Test Lagi

1. Buka: `http://localhost/Web%20Pc/`
   - Harus muncul halaman utama ✅

2. Buka: `http://localhost/Web%20Pc/debug_ai.php`
   - Harus muncul halaman debug ✅

3. Buka: `http://localhost/Web%20Pc/test_api_key.php`
   - Harus muncul halaman test API key ✅

---

## 🎯 Quick Fix (Paling Cepat)

**Jika Anda ingin cepat, ikuti ini:**

1. **Buka File Explorer**
2. **Copy folder `Web Pc`** dari:
   ```
   C:\Tugas\AI\Project_PC_Builder\Web Pc\
   ```
3. **Paste ke:**
   ```
   C:\xampp\htdocs\Web Pc\
   ```
4. **Restart Apache** di XAMPP
5. **Test:** `http://localhost/Web%20Pc/debug_ai.php`

---

## ❓ Checklist

Sebelum test lagi, pastikan:

- [ ] Folder `Web Pc` ada di `C:\xampp\htdocs\`
- [ ] File `debug_ai.php` ada di `C:\xampp\htdocs\Web Pc\`
- [ ] File `test_api_key.php` ada di `C:\xampp\htdocs\Web Pc\`
- [ ] Apache sudah running (hijau di XAMPP)
- [ ] Path di browser: `http://localhost/Web%20Pc/debug_ai.php`

---

## 🔍 Alternatif: Cek Lokasi File

Jika masih bingung, cek di browser:

1. Buka: `http://localhost/`
2. Lihat apakah ada folder `Web Pc` di list
3. Jika tidak ada, berarti folder belum di-copy
4. Jika ada, klik dan cek apakah file `debug_ai.php` ada di dalamnya

---

## 💡 Tips

**Jangan lupa:**
- File harus ada di `htdocs`, bukan di folder project asli
- Setelah copy, restart Apache
- Gunakan path dengan `%20` untuk spasi: `Web%20Pc`

**Jika masih error:**
- Cek apakah file benar-benar ada di `htdocs`
- Cek permission folder (harus bisa di-read)
- Cek error log Apache: `C:\xampp\apache\logs\error.log`

