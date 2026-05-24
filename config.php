<?php
// ============================================================
//  TELEGRAM DRIVE — CONFIG
//  Fill these in before running the app
// ============================================================

// 1. Create a bot via @BotFather on Telegram → get this token
define('BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE');

// 2. Your personal Telegram chat_id (use @userinfobot to find it)
//    OR a private channel/group chat_id (starts with -100...)
define('CHAT_ID', 'YOUR_CHAT_ID_HERE');

// 3. (Optional) Simple password to protect your drive
define('DRIVE_PASSWORD', 'changeme123');

// 4. Local JSON file that stores file metadata
define('DB_FILE', __DIR__ . '/files_db.json');

// Telegram Bot API base URL
define('TG_API', 'https://api.telegram.org/bot' . BOT_TOKEN);
define('TG_FILE_API', 'https://api.telegram.org/file/bot' . BOT_TOKEN);

// Max upload size hint shown to user (Telegram bot limit is 50MB)
define('MAX_UPLOAD_MB', 50);
