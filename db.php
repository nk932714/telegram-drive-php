<?php
// ============================================================
//  TELEGRAM DRIVE — DB (JSON flat-file storage)
// ============================================================
require_once __DIR__ . '/config.php';

// ── FILE DB ──────────────────────────────────────────────────

function db_load(): array {
    if (!file_exists(DB_FILE)) return [];
    $raw = file_get_contents(DB_FILE);
    return json_decode($raw, true) ?: [];
}

function db_save(array $data): void {
    file_put_contents(DB_FILE, json_encode($data, JSON_PRETTY_PRINT));
}

function db_add_file(array $meta): void {
    $db = db_load();
    $db[$meta['id']] = $meta;
    db_save($db);
}

function db_delete_file(string $id): bool {
    $db = db_load();
    if (!isset($db[$id])) return false;
    unset($db[$id]);
    db_save($db);
    return true;
}

function db_get_file(string $id): ?array {
    $db = db_load();
    return $db[$id] ?? null;
}

function db_all_files(): array {
    $db = db_load();
    usort($db, function($a, $b) {
        return $b['uploaded_at'] - $a['uploaded_at'];
    });
    return array_values($db);
}

/**
 * Find a stored file by its Telegram message_id.
 * Used to avoid duplicates when syncing.
 */
function db_find_by_msg_id(int $msgId): bool {
    $db = db_load();
    foreach ($db as $file) {
        if (isset($file['tg_msg_id']) && (int)$file['tg_msg_id'] === $msgId) {
            return true;
        }
    }
    return false;
}

/**
 * Find a stored file by its Telegram file_unique_id.
 * More reliable dedup than message_id for forwarded files.
 */
function db_find_by_unique_id(string $uniqueId): bool {
    if (!$uniqueId) return false;
    $db = db_load();
    foreach ($db as $file) {
        if (isset($file['tg_file_uid']) && $file['tg_file_uid'] === $uniqueId) {
            return true;
        }
    }
    return false;
}

// ── OFFSET STORE (for getUpdates polling) ────────────────────

define('OFFSET_FILE', __DIR__ . '/sync_offset.json');

function offset_get(): int {
    if (!file_exists(OFFSET_FILE)) return 0;
    $raw = file_get_contents(OFFSET_FILE);
    $data = json_decode($raw, true);
    return (int)($data['offset'] ?? 0);
}

function offset_save(int $offset): void {
    file_put_contents(OFFSET_FILE, json_encode(['offset' => $offset]));
}
