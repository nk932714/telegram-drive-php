<?php
// ============================================================
//  TELEGRAM DRIVE — MESSAGE PARSER
//  Extracts file metadata from any Telegram message object
// ============================================================

/**
 * Given a Telegram message array, returns a file meta array
 * ready to be stored in the DB, or null if no file found.
 */
function parse_tg_message(array $msg): ?array {
    $msgId = (int)($msg['message_id'] ?? 0);
    $date  = (int)($msg['date'] ?? time());

    // Caption or text becomes the display name fallback
    $caption = trim($msg['caption'] ?? $msg['text'] ?? '');

    // ── DOCUMENT (any generic file, PDF, ZIP, etc.) ──────────
    if (!empty($msg['document'])) {
        $doc = $msg['document'];
        return [
            'id'          => 'tg_' . $msgId . '_doc',
            'name'        => $doc['file_name'] ?? ('file_' . $msgId),
            'size'        => (int)($doc['file_size'] ?? 0),
            'mime'        => $doc['mime_type'] ?? 'application/octet-stream',
            'folder'      => 'Telegram',
            'tg_file_id'  => $doc['file_id'],
            'tg_file_uid' => $doc['file_unique_id'] ?? '',
            'tg_msg_id'   => $msgId,
            'caption'     => $caption,
            'uploaded_at' => $date,
            'source'      => 'telegram',
        ];
    }

    // ── VIDEO ────────────────────────────────────────────────
    if (!empty($msg['video'])) {
        $v = $msg['video'];
        $name = $v['file_name'] ?? ('video_' . $msgId . '.mp4');
        return [
            'id'          => 'tg_' . $msgId . '_vid',
            'name'        => $name,
            'size'        => (int)($v['file_size'] ?? 0),
            'mime'        => $v['mime_type'] ?? 'video/mp4',
            'folder'      => 'Telegram',
            'tg_file_id'  => $v['file_id'],
            'tg_file_uid' => $v['file_unique_id'] ?? '',
            'tg_msg_id'   => $msgId,
            'caption'     => $caption,
            'duration'    => $v['duration'] ?? 0,
            'width'       => $v['width'] ?? 0,
            'height'      => $v['height'] ?? 0,
            'uploaded_at' => $date,
            'source'      => 'telegram',
        ];
    }

    // ── VIDEO NOTE (round videos) ────────────────────────────
    if (!empty($msg['video_note'])) {
        $v = $msg['video_note'];
        return [
            'id'          => 'tg_' . $msgId . '_vnote',
            'name'        => 'video_note_' . $msgId . '.mp4',
            'size'        => (int)($v['file_size'] ?? 0),
            'mime'        => 'video/mp4',
            'folder'      => 'Telegram',
            'tg_file_id'  => $v['file_id'],
            'tg_file_uid' => $v['file_unique_id'] ?? '',
            'tg_msg_id'   => $msgId,
            'caption'     => $caption,
            'uploaded_at' => $date,
            'source'      => 'telegram',
        ];
    }

    // ── AUDIO (music files) ──────────────────────────────────
    if (!empty($msg['audio'])) {
        $a = $msg['audio'];
        $name = $a['file_name'] ?? (($a['title'] ?? 'audio') . '_' . $msgId . '.mp3');
        return [
            'id'          => 'tg_' . $msgId . '_aud',
            'name'        => $name,
            'size'        => (int)($a['file_size'] ?? 0),
            'mime'        => $a['mime_type'] ?? 'audio/mpeg',
            'folder'      => 'Telegram',
            'tg_file_id'  => $a['file_id'],
            'tg_file_uid' => $a['file_unique_id'] ?? '',
            'tg_msg_id'   => $msgId,
            'caption'     => $caption,
            'artist'      => $a['performer'] ?? '',
            'title'       => $a['title'] ?? '',
            'duration'    => $a['duration'] ?? 0,
            'uploaded_at' => $date,
            'source'      => 'telegram',
        ];
    }

    // ── VOICE NOTE ───────────────────────────────────────────
    if (!empty($msg['voice'])) {
        $v = $msg['voice'];
        return [
            'id'          => 'tg_' . $msgId . '_voice',
            'name'        => 'voice_' . $msgId . '.ogg',
            'size'        => (int)($v['file_size'] ?? 0),
            'mime'        => 'audio/ogg',
            'folder'      => 'Telegram',
            'tg_file_id'  => $v['file_id'],
            'tg_file_uid' => $v['file_unique_id'] ?? '',
            'tg_msg_id'   => $msgId,
            'caption'     => $caption,
            'uploaded_at' => $date,
            'source'      => 'telegram',
        ];
    }

    // ── PHOTO ────────────────────────────────────────────────
    if (!empty($msg['photo'])) {
        // Use the largest available size
        $photos = $msg['photo'];
        $photo  = end($photos);
        return [
            'id'          => 'tg_' . $msgId . '_img',
            'name'        => 'photo_' . $msgId . '.jpg',
            'size'        => (int)($photo['file_size'] ?? 0),
            'mime'        => 'image/jpeg',
            'folder'      => 'Telegram',
            'tg_file_id'  => $photo['file_id'],
            'tg_file_uid' => $photo['file_unique_id'] ?? '',
            'tg_msg_id'   => $msgId,
            'caption'     => $caption,
            'width'       => $photo['width'] ?? 0,
            'height'      => $photo['height'] ?? 0,
            'uploaded_at' => $date,
            'source'      => 'telegram',
        ];
    }

    // ── STICKER ──────────────────────────────────────────────
    if (!empty($msg['sticker'])) {
        $s = $msg['sticker'];
        $ext = $s['is_animated'] ? 'tgs' : ($s['is_video'] ? 'webm' : 'webp');
        return [
            'id'          => 'tg_' . $msgId . '_stk',
            'name'        => 'sticker_' . $msgId . '.' . $ext,
            'size'        => (int)($s['file_size'] ?? 0),
            'mime'        => 'image/webp',
            'folder'      => 'Telegram',
            'tg_file_id'  => $s['file_id'],
            'tg_file_uid' => $s['file_unique_id'] ?? '',
            'tg_msg_id'   => $msgId,
            'caption'     => $s['emoji'] ?? '',
            'uploaded_at' => $date,
            'source'      => 'telegram',
        ];
    }

    // No file found in this message
    return null;
}
