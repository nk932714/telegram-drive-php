# Telegram Drive (PHP Edition)

A self-hosted cloud drive that uses **Telegram as the storage backend**.  
Built with pure PHP + HTML + CSS + AJAX — no frameworks, no Node.js, no Composer needed.

---

## 📁 Files

```
telegram-drive/
├── index.php      ← Main UI (the app you open in browser)
├── api.php        ← Backend: handles all AJAX requests
├── config.php     ← ⚠️ YOUR CREDENTIALS GO HERE
├── db.php         ← JSON flat-file database helper
└── files_db.json  ← Auto-created on first upload
```

---

## ⚡ Quick Setup

### Step 1 — Create a Telegram Bot

1. Open Telegram, search for **@BotFather**
2. Send `/newbot` and follow the prompts
3. Copy your **Bot Token** (looks like `123456789:ABCdef...`)

### Step 2 — Get Your Chat ID

**Option A — Personal Storage (Saved Messages):**
1. Send a message to your bot
2. Visit: `https://api.telegram.org/bot<YOUR_TOKEN>/getUpdates`
3. Find `"chat":{"id": 123456789}` — that number is your Chat ID

**Option B — Private Channel (more organized):**
1. Create a private Telegram channel
2. Add your bot as an **Admin** with post permissions
3. Forward a message from the channel to @userinfobot to get the channel ID (starts with -100...)

### Step 3 — Edit `config.php`

```php
define('BOT_TOKEN',      '123456789:ABCdef...');  // Your bot token
define('CHAT_ID',        '123456789');             // Your chat ID
define('DRIVE_PASSWORD', 'your_secure_password');  // Login password
```

### Step 4 — Deploy

Upload all 4 PHP files to **any PHP 7.4+ web server** (Apache, Nginx, shared hosting).

Requirements:
- PHP 7.4+
- `curl` extension enabled (usually on by default)
- `file_uploads = On` in php.ini
- Write permission on the folder (for `files_db.json`)

### Step 5 — Open the App

Navigate to `https://your-domain.com/telegram-drive/` and log in.

---

## 🔒 Security Notes

- The drive is protected by the password in `config.php`
- All files are stored **on Telegram's servers** — no disk space used on your server
- The `files_db.json` file stores only metadata (names, Telegram file IDs, sizes)
- For production, add `.htaccess` rules to block direct access to `config.php`, `db.php`, `api.php`, and `files_db.json`

**Recommended `.htaccess` additions:**
```apache
<FilesMatch "^(config|db|api|files_db)\.php$|files_db\.json$">
  # Uncomment to restrict direct API access from outside
  # Order Deny,Allow
  # Deny from all
</FilesMatch>
```

---

## 📦 Features

- **Upload** any file up to 50MB (Telegram Bot API limit)
- **Download** files directly through the browser
- **Delete** files (removes from Telegram + local index)
- **Organize** into virtual folders
- **Filter** by type: images, videos, audio, documents
- **Search** by filename
- **Grid & List** views
- **Drag & Drop** upload
- Upload **progress indicators**
- Password-protected login

---

## ⚠️ Limitations

- **50MB per file** — Telegram Bot API limit. For larger files, you'd need MTProto (a different, more complex API).
- Files are sent to your bot's "Saved Messages" or a channel — they're accessible via your bot but not in your regular Telegram file manager.
- The flat JSON database is fine for hundreds of files; for thousands, consider SQLite.

---

## 🛠️ Troubleshooting

| Problem | Solution |
|---|---|
| Upload fails | Check `BOT_TOKEN` and `CHAT_ID` are correct in config.php |
| "Telegram error: Bad Request" | Make sure your bot has been started (send it `/start`) |
| Can't write files_db.json | Give the folder write permission: `chmod 755 telegram-drive/` |
| Files don't appear after upload | Click Refresh (↻) button in the top bar |
