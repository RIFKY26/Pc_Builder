<?php
/**
 * KONFIGURASI GENERATIVE AI API
 * 
 * Pilih salah satu provider dan isi API key-nya di bawah ini.
 * Jika tidak ingin menggunakan AI, biarkan kosong atau set ENABLE_AI = false
 */

// Aktifkan/Nonaktifkan fitur AI
define('ENABLE_AI', true); // Set false untuk menonaktifkan AI

// Pilih provider yang ingin digunakan: 'openai', 'gemini', 'claude', atau 'none'
define('AI_PROVIDER', 'gemini'); // Menggunakan Gemini (GRATIS)

// --- OPENAI (ChatGPT) Configuration ---
define('OPENAI_API_KEY', ''); // Isi dengan API key OpenAI Anda (dari https://platform.openai.com/api-keys)
define('OPENAI_MODEL', 'gpt-3.5-turbo'); // Model: gpt-3.5-turbo, gpt-4, gpt-4-turbo
define('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions');

// --- GOOGLE GEMINI Configuration ---
define('GEMINI_API_KEY', 'AIzaSyB7mRTY1PPf3qqKPMk-0kMmANgOVMraNUk'); // API key Gemini Anda
define('GEMINI_MODEL', 'gemini-pro'); // Model: gemini-pro, gemini-pro-vision
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/');

// --- ANTHROPIC CLAUDE Configuration ---
define('CLAUDE_API_KEY', ''); // Isi dengan API key Claude Anda (dari https://console.anthropic.com/)
define('CLAUDE_MODEL', 'claude-3-sonnet-20240229'); // Model: claude-3-opus, claude-3-sonnet, claude-3-haiku
define('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages');

// Timeout untuk API call (dalam detik)
define('AI_TIMEOUT', 10);

/**
 * Catatan:
 * 1. Untuk mendapatkan API key:
 *    - OpenAI: https://platform.openai.com/api-keys
 *    - Gemini: https://makersuite.google.com/app/apikey
 *    - Claude: https://console.anthropic.com/
 * 
 * 2. Beberapa provider menawarkan free tier dengan limit tertentu
 * 
 * 3. Jika tidak ingin menggunakan AI, set ENABLE_AI = false
 *    Sistem akan tetap berjalan dengan insight statis
 */
?>

