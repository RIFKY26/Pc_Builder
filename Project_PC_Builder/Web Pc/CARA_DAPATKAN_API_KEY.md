# Cara Mendapatkan API Key - Panduan Lengkap

Dokumentasi lengkap cara mendapatkan API key untuk berbagai provider Generative AI.

## 📊 Perbandingan Provider

| Provider | Free Tier | Pricing | Rekomendasi |
|----------|-----------|---------|-------------|
| **Google Gemini** | ✅ Ya (Free) | Gratis dengan limit | ⭐ **TERBAIK untuk Testing** |
| **OpenAI (ChatGPT)** | ❌ Tidak | ~$0.002 per 1K tokens | ⭐ **TERBAIK untuk Production** |
| **Anthropic Claude** | ❌ Tidak | ~$0.003 per 1K tokens | Untuk analisis teknis |

---

## 🆓 1. Google Gemini (GRATIS - Recommended untuk Testing)

### Keuntungan:
- ✅ **GRATIS** dengan free tier
- ✅ Limit cukup untuk testing dan development
- ✅ Mudah didapatkan
- ✅ Tidak perlu kartu kredit

### Cara Mendapatkan API Key:

1. **Buka Google AI Studio**
   - Kunjungi: https://makersuite.google.com/app/apikey
   - Atau: https://aistudio.google.com/app/apikey

2. **Login dengan Google Account**
   - Gunakan akun Google Anda (Gmail)

3. **Buat API Key**
   - Klik tombol **"Create API Key"** atau **"Get API Key"**
   - Pilih project (atau buat project baru)
   - API key akan langsung dibuat

4. **Copy API Key**
   - API key akan muncul dalam format: `AIzaSy...`
   - Copy API key tersebut

5. **Setup di Aplikasi**
   - Buka file `ai_config.php`
   - Paste API key di `GEMINI_API_KEY`
   - Set `AI_PROVIDER = 'gemini'`

### Limit Free Tier:
- **60 requests per menit**
- **1,500 requests per hari**
- Cukup untuk testing dan development

### Contoh Setup:
```php
define('AI_PROVIDER', 'gemini');
define('GEMINI_API_KEY', 'AIzaSy...'); // Paste API key di sini
```

---

## 💰 2. OpenAI (ChatGPT) - Berbayar

### Pricing:
- **GPT-3.5-turbo**: ~$0.002 per 1K tokens (sangat murah)
- **GPT-4**: Lebih mahal (~$0.03 per 1K tokens)
- **Minimum top-up**: Biasanya $5-10

### Cara Mendapatkan API Key:

1. **Buka OpenAI Platform**
   - Kunjungi: https://platform.openai.com/
   - Klik **"Sign Up"** atau **"Log In"**

2. **Buat Akun**
   - Daftar dengan email atau Google/Microsoft account
   - Verifikasi email

3. **Top Up Credit**
   - Masuk ke dashboard
   - Klik **"Billing"** atau **"Add Payment Method"**
   - Tambahkan kartu kredit/debit
   - Top up minimal $5-10 (untuk testing)

4. **Buat API Key**
   - Klik menu **"API Keys"** di sidebar kiri
   - Atau langsung: https://platform.openai.com/api-keys
   - Klik **"Create new secret key"**
   - Beri nama (opsional): "PC Builder AI"
   - Klik **"Create secret key"**
   - **⚠️ PENTING**: Copy API key SEKARANG! Tidak bisa dilihat lagi setelah ini
   - API key format: `sk-proj-...` atau `sk-...`

5. **Setup di Aplikasi**
   - Buka file `ai_config.php`
   - Paste API key di `OPENAI_API_KEY`
   - Set `AI_PROVIDER = 'openai'`

### Estimasi Biaya:
- **1 request** (sekitar 500 tokens): ~$0.001
- **1000 request**: ~$1
- **Sangat murah untuk production**

### Contoh Setup:
```php
define('AI_PROVIDER', 'openai');
define('OPENAI_API_KEY', 'sk-proj-...'); // Paste API key di sini
define('OPENAI_MODEL', 'gpt-3.5-turbo'); // Paling murah
```

---

## 💰 3. Anthropic Claude - Berbayar

### Pricing:
- **Claude 3 Sonnet**: ~$0.003 per 1K tokens
- **Claude 3 Opus**: Lebih mahal
- **Minimum top-up**: Biasanya $5

### Cara Mendapatkan API Key:

1. **Buka Anthropic Console**
   - Kunjungi: https://console.anthropic.com/
   - Klik **"Sign Up"** atau **"Log In"**

2. **Buat Akun**
   - Daftar dengan email
   - Verifikasi email

3. **Top Up Credit**
   - Masuk ke dashboard
   - Klik **"Billing"**
   - Tambahkan payment method
   - Top up credit

4. **Buat API Key**
   - Klik menu **"API Keys"**
   - Klik **"Create Key"**
   - Beri nama (opsional)
   - Copy API key
   - API key format: `sk-ant-...`

5. **Setup di Aplikasi**
   - Buka file `ai_config.php`
   - Paste API key di `CLAUDE_API_KEY`
   - Set `AI_PROVIDER = 'claude'`

### Contoh Setup:
```php
define('AI_PROVIDER', 'claude');
define('CLAUDE_API_KEY', 'sk-ant-...'); // Paste API key di sini
```

---

## 🎯 Rekomendasi Berdasarkan Kebutuhan

### Untuk Testing/Development:
**⭐ Gunakan Google Gemini (GRATIS)**
- Tidak perlu kartu kredit
- Cukup untuk testing
- Setup mudah

### Untuk Production/Project:
**⭐ Gunakan OpenAI GPT-3.5-turbo**
- Paling murah ($0.001 per request)
- Cepat dan reliable
- Hasil bagus

### Untuk Analisis Teknis Detail:
**Gunakan Claude 3 Sonnet**
- Lebih akurat untuk analisis
- Lebih mahal sedikit

---

## 📝 Langkah Setup Setelah Dapat API Key

### 1. Edit File `ai_config.php`

```php
// Aktifkan AI
define('ENABLE_AI', true);

// Pilih provider (sesuai API key yang Anda punya)
define('AI_PROVIDER', 'gemini'); // atau 'openai' atau 'claude'

// Isi API key yang sudah Anda dapatkan
define('GEMINI_API_KEY', 'AIzaSy...'); // Untuk Gemini
// atau
define('OPENAI_API_KEY', 'sk-proj-...'); // Untuk OpenAI
// atau
define('CLAUDE_API_KEY', 'sk-ant-...'); // Untuk Claude
```

### 2. Test API Key

1. Buka: `http://localhost/Web%20Pc/test_api_key.php`
   - Cek apakah API key ter-load dengan benar

2. Buka: `http://localhost/Web%20Pc/debug_ai.php`
   - Test koneksi ke API
   - Lihat hasil AI

### 3. Test di Aplikasi

1. Buka: `http://localhost/Web%20Pc/`
2. Masukkan budget dan cari rakitan
3. Scroll ke bawah → Lihat bagian "AI Insight & Analisis"
4. Jika berhasil, akan muncul badge hijau "Powered by [PROVIDER]"

---

## ⚠️ Tips Keamanan

1. **Jangan Share API Key**
   - Jangan post di forum/social media
   - Jangan commit ke GitHub public

2. **Gunakan .gitignore**
   - File `ai_config.php` sudah di-`.gitignore`
   - Jangan commit file yang berisi API key

3. **Rotate API Key**
   - Jika ter-expose, segera revoke dan buat baru
   - Bisa di-revoke dari dashboard provider

4. **Monitor Usage**
   - Cek penggunaan di dashboard provider
   - Set limit jika perlu

---

## 💡 FAQ

### Q: Apakah ada yang benar-benar gratis?
**A:** Ya, **Google Gemini** menawarkan free tier yang cukup untuk testing.

### Q: Berapa biaya untuk 1000 request?
**A:** 
- Gemini: **GRATIS** (dalam limit)
- OpenAI: ~**$1**
- Claude: ~**$2**

### Q: Apakah perlu kartu kredit?
**A:** 
- Gemini: **TIDAK**
- OpenAI: **YA** (untuk top-up)
- Claude: **YA** (untuk top-up)

### Q: Mana yang terbaik untuk project tugas?
**A:** 
- **Gemini** untuk testing (gratis)
- **OpenAI GPT-3.5** untuk demo/final (murah, $1 untuk 1000 request)

---

## 🚀 Quick Start (Paling Cepat)

**Untuk testing cepat, gunakan Gemini:**

1. Buka: https://aistudio.google.com/app/apikey
2. Login dengan Google
3. Klik "Create API Key"
4. Copy API key
5. Paste di `ai_config.php`:
   ```php
   define('AI_PROVIDER', 'gemini');
   define('GEMINI_API_KEY', 'AIzaSy...'); // Paste di sini
   ```
6. Test di `debug_ai.php`

**Selesai! Tidak perlu kartu kredit!** 🎉

