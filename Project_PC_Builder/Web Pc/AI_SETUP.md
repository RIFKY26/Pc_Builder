# Setup Generative AI Integration

Dokumentasi untuk mengaktifkan fitur Generative AI pada PC-Builder AI.

## Overview

Sistem mendukung integrasi dengan beberapa provider Generative AI:
- **OpenAI (ChatGPT)** - GPT-3.5-turbo, GPT-4
- **Google Gemini** - Gemini Pro
- **Anthropic Claude** - Claude 3 Sonnet, Opus, Haiku

## Cara Setup

### 1. Buka File Konfigurasi

Edit file `ai_config.php` dan pilih provider yang ingin digunakan.

### 2. Setup OpenAI (ChatGPT)

1. Daftar/Login ke [OpenAI Platform](https://platform.openai.com/)
2. Buat API Key di [API Keys](https://platform.openai.com/api-keys)
3. Copy API key Anda
4. Edit `ai_config.php`:
   ```php
   define('AI_PROVIDER', 'openai');
   define('OPENAI_API_KEY', 'sk-...'); // Paste API key di sini
   define('OPENAI_MODEL', 'gpt-3.5-turbo'); // atau 'gpt-4'
   ```

**Pricing**: 
- GPT-3.5-turbo: ~$0.002 per 1K tokens (sangat murah)
- GPT-4: Lebih mahal, tapi lebih akurat

### 3. Setup Google Gemini

1. Daftar/Login ke [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Buat API Key
3. Copy API key Anda
4. Edit `ai_config.php`:
   ```php
   define('AI_PROVIDER', 'gemini');
   define('GEMINI_API_KEY', 'AIza...'); // Paste API key di sini
   ```

**Pricing**: 
- Free tier tersedia dengan limit tertentu
- Sangat cocok untuk testing

### 4. Setup Anthropic Claude

1. Daftar/Login ke [Anthropic Console](https://console.anthropic.com/)
2. Buat API Key
3. Copy API key Anda
4. Edit `ai_config.php`:
   ```php
   define('AI_PROVIDER', 'claude');
   define('CLAUDE_API_KEY', 'sk-ant-...'); // Paste API key di sini
   define('CLAUDE_MODEL', 'claude-3-sonnet-20240229');
   ```

**Pricing**: 
- Claude 3 Sonnet: ~$0.003 per 1K tokens
- Claude 3 Opus: Lebih mahal, lebih powerful

## Menonaktifkan AI

Jika tidak ingin menggunakan AI, edit `ai_config.php`:

```php
define('ENABLE_AI', false);
```

Atau set provider ke 'none':

```php
define('AI_PROVIDER', 'none');
```

Sistem akan tetap berjalan dengan insight default (statis).

## Testing

1. Setelah setup API key, buka aplikasi di browser
2. Masukkan budget dan kebutuhan
3. Klik "Cari Rakitan Terbaik"
4. Jika AI aktif, akan muncul badge "Powered by [PROVIDER]" di bagian AI Insight
5. Jika AI tidak aktif atau gagal, akan muncul badge "Default Insight"

## Troubleshooting

### Error: API Key tidak valid
- Pastikan API key sudah di-copy dengan benar (tanpa spasi)
- Cek apakah API key masih aktif di dashboard provider

### Error: Timeout
- Cek koneksi internet
- Beberapa provider mungkin memblokir request dari IP tertentu
- Coba increase timeout di `ai_config.php`: `define('AI_TIMEOUT', 15);`

### AI tidak muncul
- Cek apakah `ENABLE_AI = true` di `ai_config.php`
- Cek apakah API key sudah diisi
- Cek console browser untuk error JavaScript (jika ada)
- Sistem akan otomatis fallback ke default insight jika AI gagal

### Rate Limit
- Beberapa provider memiliki rate limit pada free tier
- Tunggu beberapa saat atau upgrade ke paid tier

## Keamanan

⚠️ **PENTING**: Jangan commit file `ai_config.php` yang berisi API key ke repository public!

1. Tambahkan `ai_config.php` ke `.gitignore`
2. Atau gunakan environment variables (untuk production)
3. Atau buat `ai_config.example.php` sebagai template

## Cost Estimation

Untuk penggunaan normal (1 request per user):
- **OpenAI GPT-3.5**: ~$0.001 per request (sangat murah)
- **Gemini**: Free tier biasanya cukup untuk testing
- **Claude**: ~$0.002 per request

Dengan 1000 request per bulan, estimasi cost:
- OpenAI: ~$1
- Gemini: Free (dalam limit)
- Claude: ~$2

## Rekomendasi

Untuk development/testing:
- Gunakan **Gemini** (free tier tersedia)

Untuk production:
- Gunakan **OpenAI GPT-3.5-turbo** (paling murah dan cepat)
- Atau **Claude 3 Sonnet** (lebih akurat untuk analisis teknis)

