<?php
// ============================================================
//  TELEGRAM DRIVE — WEBHOOK RECEIVER
//
//  Point your Telegram bot's webhook here:
//  https://yourdomain.com/telegram-drive/webhook.php
//
//  Telegram will POST every new message here in real-time.
//  Any file/photo/video forwarded to the bot shows up
//  instantly in the drive — no manual Sync needed.
//
//  Set it once via Settings → Webhook in the UI.
// ============================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/parser.php';

// Only accept POST from Telegram
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Read the incoming JSON payload
$body = file_get_contents('php://input');
$update = json_decode($body, true);

if (!$update) {
    http_response_code(400);
    exit('Bad Request');
}

// Accept both regular messages and channel posts
$msg = null;
if (isset($update['message']))       $msg = $update['message'];
elseif (isset($update['channel_post'])) $msg = $update['channel_post'];

if ($msg) {
    $meta = parse_tg_message($msg);

    if ($meta) {
        // Deduplicate by file_unique_id first, then message_id
        $alreadyHave = false;
        if (!empty($meta['tg_file_uid'])) {
            $alreadyHave = db_find_by_unique_id($meta['tg_file_uid']);
        }
        if (!$alreadyHave && !empty($meta['tg_msg_id'])) {
            $alreadyHave = db_find_by_msg_id((int)$meta['tg_msg_id']);
        }

        if (!$alreadyHave) {
            db_add_file($meta);
        }
    }
}

// Always respond 200 OK so Telegram doesn't retry
http_response_code(200);
echo 'ok';
