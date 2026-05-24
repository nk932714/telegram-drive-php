<?php
// ============================================================
//  TELEGRAM DRIVE — API
//  Actions: login, logout, check_auth, list, upload,
//           download, delete, create_folder, sync
// ============================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/parser.php';

header('Content-Type: application/json');

session_start();

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// ── PUBLIC ACTIONS (no auth needed) ──────────────────────────

if ($action === 'login') {
    $pw = isset($_POST['password']) ? $_POST['password'] : '';
    if ($pw === DRIVE_PASSWORD) {
        $_SESSION['tg_drive_auth'] = true;
        echo json_encode(array('ok' => true));
    } else {
        echo json_encode(array('ok' => false, 'error' => 'Wrong password'));
    }
    exit;
}

if ($action === 'logout') {
    session_destroy();
    echo json_encode(array('ok' => true));
    exit;
}

if ($action === 'check_auth') {
    echo json_encode(array('ok' => !empty($_SESSION['tg_drive_auth'])));
    exit;
}

// ── AUTH GATE ─────────────────────────────────────────────────
if (empty($_SESSION['tg_drive_auth'])) {
    http_response_code(401);
    echo json_encode(array('ok' => false, 'error' => 'Not authenticated'));
    exit;
}

// ── AUTHENTICATED ACTIONS ─────────────────────────────────────
switch ($action) {

    // ── LIST ──────────────────────────────────────────────────
    case 'list':
        $files = db_all_files();
        echo json_encode(array('ok' => true, 'files' => $files));
        break;

    // ── UPLOAD ────────────────────────────────────────────────
    case 'upload':
        if (empty($_FILES['file'])) {
            echo json_encode(array('ok' => false, 'error' => 'No file received'));
            exit;
        }
        $file    = $_FILES['file'];
        $name    = basename($file['name']);
        $mime    = $file['type'] ? $file['type'] : mime_content_type($file['tmp_name']);
        $size    = (int)$file['size'];
        $tmp     = $file['tmp_name'];
        $folder  = trim(isset($_POST['folder']) ? $_POST['folder'] : 'Root');

        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(array('ok' => false, 'error' => 'Upload error: ' . $file['error']));
            exit;
        }

        $tgRes = tg_send_file($tmp, $name, $mime, $size);
        if (!$tgRes['ok']) {
            echo json_encode(array('ok' => false, 'error' => 'Telegram: ' . (isset($tgRes['description']) ? $tgRes['description'] : 'unknown')));
            exit;
        }

        $msg    = $tgRes['result'];
        $fileId = extract_file_id($msg);
        $uid    = extract_file_unique_id($msg);
        $msgId  = (int)$msg['message_id'];

        $meta = array(
            'id'          => uniqid('f_', true),
            'name'        => $name,
            'size'        => $size,
            'mime'        => $mime,
            'folder'      => $folder ? $folder : 'Root',
            'tg_file_id'  => $fileId,
            'tg_file_uid' => $uid,
            'tg_msg_id'   => $msgId,
            'uploaded_at' => time(),
            'source'      => 'upload',
        );

        db_add_file($meta);
        echo json_encode(array('ok' => true, 'file' => $meta));
        break;

    // ── DOWNLOAD ──────────────────────────────────────────────
    case 'download':
        $id   = isset($_GET['id']) ? $_GET['id'] : '';
        $meta = db_get_file($id);
        if (!$meta) {
            echo json_encode(array('ok' => false, 'error' => 'File not found'));
            exit;
        }
        $pathRes = tg_get_file_path($meta['tg_file_id']);
        if (!$pathRes['ok']) {
            echo json_encode(array('ok' => false, 'error' => 'Cannot get file path from Telegram'));
            exit;
        }
        $filePath = $pathRes['result']['file_path'];
        $url      = TG_FILE_API . '/' . $filePath;

        // Clear JSON header, stream the file
        header_remove('Content-Type');
        header('Content-Type: ' . $meta['mime']);
        header('Content-Disposition: attachment; filename="' . rawurlencode($meta['name']) . '"');
        if ($meta['size']) header('Content-Length: ' . $meta['size']);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        exit;

    // ── DELETE ────────────────────────────────────────────────
    case 'delete':
        $id   = isset($_POST['id']) ? $_POST['id'] : '';
        $meta = db_get_file($id);
        if (!$meta) {
            echo json_encode(array('ok' => false, 'error' => 'File not found'));
            exit;
        }
        if ($meta['tg_msg_id']) tg_delete_message((int)$meta['tg_msg_id']);
        db_delete_file($id);
        echo json_encode(array('ok' => true));
        break;

    // ── CREATE FOLDER ─────────────────────────────────────────
    case 'create_folder':
        $name = trim(isset($_POST['name']) ? $_POST['name'] : '');
        if (!$name) {
            echo json_encode(array('ok' => false, 'error' => 'Folder name required'));
            exit;
        }
        $meta = array(
            'id'          => 'folder_' . uniqid(),
            'name'        => $name,
            'type'        => 'folder',
            'size'        => 0,
            'mime'        => 'folder',
            'folder'      => 'Root',
            'tg_file_id'  => null,
            'tg_file_uid' => null,
            'tg_msg_id'   => null,
            'uploaded_at' => time(),
            'source'      => 'local',
        );
        db_add_file($meta);
        echo json_encode(array('ok' => true, 'folder' => $meta));
        break;

    // ── SYNC FROM TELEGRAM ────────────────────────────────────
    // Pulls ALL updates the bot has ever received via getUpdates,
    // parses every message for files/photos/videos/audio,
    // and saves new ones to the local DB.
    case 'sync':
        // Use stored offset so we only fetch new updates each time.
        // Pass offset=0 in POST to force a full re-sync from scratch.
        $forceResync = isset($_POST['resync']) && $_POST['resync'] === '1';
        $offset      = $forceResync ? 0 : offset_get();

        $imported = 0;
        $skipped  = 0;
        $errors   = array();
        $processed = 0;

        // Loop through pages of 100 updates until exhausted
        while (true) {
            $url = TG_API . '/getUpdates?limit=100&timeout=0&allowed_updates=["message","channel_post"]';
            if ($offset > 0) $url .= '&offset=' . $offset;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $res = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                $errors[] = 'cURL error: ' . $err;
                break;
            }

            $data = json_decode($res, true);

            if (empty($data['ok'])) {
                $errors[] = isset($data['description']) ? $data['description'] : 'Telegram API error';
                break;
            }

            $updates = isset($data['result']) ? $data['result'] : array();
            if (empty($updates)) break; // No more updates

            foreach ($updates as $update) {
                $offset = (int)$update['update_id'] + 1;
                $processed++;

                // Accept both private messages and channel posts
                $msg = null;
                if (isset($update['message']))      $msg = $update['message'];
                elseif (isset($update['channel_post'])) $msg = $update['channel_post'];

                if (!$msg) { $skipped++; continue; }

                $meta = parse_tg_message($msg);
                if (!$meta) { $skipped++; continue; } // Text-only message, no file

                // Dedup: skip if we already have this file (by unique_id or msg_id)
                $alreadyHave = false;
                if (!empty($meta['tg_file_uid'])) {
                    $alreadyHave = db_find_by_unique_id($meta['tg_file_uid']);
                }
                if (!$alreadyHave && !empty($meta['tg_msg_id'])) {
                    $alreadyHave = db_find_by_msg_id((int)$meta['tg_msg_id']);
                }

                if ($alreadyHave) { $skipped++; continue; }

                db_add_file($meta);
                $imported++;
            }

            // Save offset after each page so progress is not lost
            offset_save($offset);

            // If we got fewer than 100 updates we've reached the end
            if (count($updates) < 100) break;
        }

        offset_save($offset);

        $msg_out = "Synced. Imported: $imported new file(s). Skipped: $skipped.";
        if ($errors) $msg_out .= ' Errors: ' . implode('; ', $errors);

        echo json_encode(array(
            'ok'         => true,
            'imported'   => $imported,
            'skipped'    => $skipped,
            'processed'  => $processed,
            'next_offset'=> $offset,
            'message'    => $msg_out,
        ));
        break;

    default:
        echo json_encode(array('ok' => false, 'error' => 'Unknown action: ' . $action));
}

// ============================================================
//  TELEGRAM HELPERS
// ============================================================

function tg_send_file(string $tmpPath, string $name, string $mime, int $size): array {
    $url = TG_API . '/sendDocument';

    $isImage = strpos($mime, 'image/') === 0;
    $isVideo = strpos($mime, 'video/') === 0;
    $isAudio = strpos($mime, 'audio/') === 0;

    if ($isImage) $url = TG_API . '/sendPhoto';
    elseif ($isVideo) $url = TG_API . '/sendVideo';
    elseif ($isAudio) $url = TG_API . '/sendAudio';

    if ($isImage)     $fieldName = 'photo';
    elseif ($isVideo) $fieldName = 'video';
    elseif ($isAudio) $fieldName = 'audio';
    else              $fieldName = 'document';

    $post = array(
        'chat_id'  => CHAT_ID,
        $fieldName => new CURLFile($tmpPath, $mime, $name),
        'caption'  => $name . ' (' . format_bytes($size) . ')',
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
        CURLOPT_TIMEOUT        => 300,
    ));
    $res = curl_exec($ch);
    curl_close($ch);

    return json_decode($res, true) ?: array('ok' => false, 'description' => 'cURL failed');
}

function extract_file_id(array $msg): string {
    foreach (array('document', 'video', 'audio', 'voice', 'video_note', 'sticker') as $type) {
        if (!empty($msg[$type]['file_id'])) return $msg[$type]['file_id'];
    }
    if (!empty($msg['photo'])) {
        $photos = $msg['photo'];
        $last   = end($photos);
        return $last['file_id'];
    }
    return '';
}

function extract_file_unique_id(array $msg): string {
    foreach (array('document', 'video', 'audio', 'voice', 'video_note', 'sticker') as $type) {
        if (!empty($msg[$type]['file_unique_id'])) return $msg[$type]['file_unique_id'];
    }
    if (!empty($msg['photo'])) {
        $photos = $msg['photo'];
        $last   = end($photos);
        return isset($last['file_unique_id']) ? $last['file_unique_id'] : '';
    }
    return '';
}

function tg_get_file_path(string $fileId): array {
    $url = TG_API . '/getFile?file_id=' . urlencode($fileId);
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true) ?: array('ok' => false);
}

function tg_delete_message(int $msgId): void {
    $ch = curl_init(TG_API . '/deleteMessage');
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => array('chat_id' => CHAT_ID, 'message_id' => $msgId),
    ));
    curl_exec($ch);
    curl_close($ch);
}

function format_bytes(int $bytes): string {
    if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576)    return round($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024)       return round($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}
