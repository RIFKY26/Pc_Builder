<?php
/**
 * CONTOH KONFIGURASI GENERATIVE AI API
 * 
 * Copy file ini menjadi ai_config.php dan isi dengan API key Anda
 * Jangan commit ai_config.php ke repository public!
 */

// Aktifkan/Nonaktifkan fitur AI
define('ENABLE_AI', true);

// Pilih provider: 'openai', 'gemini', 'claude', atau 'none'
define('AI_PROVIDER', 'openai');

// --- OPENAI (ChatGPT) Configuration ---
define('OPENAI_API_KEY', ''); // Isi dengan API key dari https://platform.openai.com/api-keys
define('OPENAI_MODEL', 'gpt-3.5-turbo'); // Model: gpt-3.5-turbo, gpt-4, gpt-4-turbo
define('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions');

// --- GOOGLE GEMINI Configuration ---
define('GEMINI_API_KEY', ''); // Isi dengan API key dari https://makersuite.google.com/app/apikey
define('GEMINI_MODEL', 'gemini-pro'); // Model: gemini-pro, gemini-pro-vision
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/');

// --- ANTHROPIC CLAUDE Configuration ---
define('CLAUDE_API_KEY', ''); // Isi dengan API key dari https://console.anthropic.com/
define('CLAUDE_MODEL', 'claude-3-sonnet-20240229'); // Model: claude-3-opus, claude-3-sonnet, claude-3-haiku
define('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages');

// Timeout untuk API call (dalam detik)
define('AI_TIMEOUT', 10);
?>

