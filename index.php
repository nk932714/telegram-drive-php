<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Telegram Drive</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
/* ── RESET & BASE ───────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg:        #0d0f14;
  --surface:   #13161e;
  --surface2:  #1a1e2a;
  --surface3:  #21273a;
  --border:    #ffffff0f;
  --border2:   #ffffff1a;
  --teal:      #00c9a7;
  --teal-dim:  #00c9a722;
  --blue:      #3b82f6;
  --blue-dim:  #3b82f615;
  --red:       #f43f5e;
  --red-dim:   #f43f5e15;
  --yellow:    #fbbf24;
  --text:      #e8eaf0;
  --text-muted:#7c8499;
  --text-dim:  #4a5066;
  --radius:    12px;
  --radius-sm: 8px;
  --transition:0.18s cubic-bezier(.4,0,.2,1);
}

html, body {
  height: 100%;
  background: var(--bg);
  color: var(--text);
  font-family: 'Syne', sans-serif;
  overflow-x: hidden;
}

/* ── SCROLLBAR ──────────────────────────────────────── */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }

/* ── LOGIN SCREEN ───────────────────────────────────── */
#login-screen {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: radial-gradient(ellipse at 60% 30%, #0e2a2a 0%, var(--bg) 65%);
}

.login-card {
  background: var(--surface);
  border: 1px solid var(--border2);
  border-radius: 20px;
  padding: 48px 44px;
  width: 380px;
  max-width: 95vw;
  box-shadow: 0 40px 80px #00000060, 0 0 0 1px #ffffff06;
}

.login-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 36px;
}

.login-logo svg { color: var(--teal); }

.login-logo h1 {
  font-size: 22px;
  font-weight: 800;
  letter-spacing: -0.5px;
}

.login-logo span { color: var(--teal); }

.login-card label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 8px;
}

.login-card input[type="password"] {
  width: 100%;
  background: var(--surface2);
  border: 1px solid var(--border2);
  border-radius: var(--radius-sm);
  padding: 13px 16px;
  color: var(--text);
  font-family: 'Space Mono', monospace;
  font-size: 14px;
  outline: none;
  transition: border-color var(--transition);
}

.login-card input[type="password"]:focus {
  border-color: var(--teal);
}

.btn-primary {
  width: 100%;
  background: var(--teal);
  color: #0a1a17;
  border: none;
  border-radius: var(--radius-sm);
  padding: 13px;
  font-family: 'Syne', sans-serif;
  font-weight: 700;
  font-size: 14px;
  letter-spacing: 0.05em;
  cursor: pointer;
  margin-top: 16px;
  transition: opacity var(--transition), transform var(--transition);
}

.btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }
.btn-primary:active { transform: translateY(0); }

.login-error {
  color: var(--red);
  font-size: 13px;
  margin-top: 12px;
  text-align: center;
  display: none;
}

/* ── APP LAYOUT ─────────────────────────────────────── */
#app {
  display: none;
  height: 100vh;
  display: grid;
  grid-template-columns: 240px 1fr;
  grid-template-rows: 64px 1fr;
}

/* ── TOPBAR ─────────────────────────────────────────── */
.topbar {
  grid-column: 1 / -1;
  display: flex;
  align-items: center;
  padding: 0 24px;
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  gap: 16px;
  position: sticky;
  top: 0;
  z-index: 50;
}

.topbar-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 800;
  font-size: 16px;
  letter-spacing: -0.3px;
  margin-right: 8px;
}

.topbar-logo svg { color: var(--teal); }
.topbar-logo span { color: var(--teal); }

.topbar-search {
  flex: 1;
  max-width: 480px;
  position: relative;
}

.topbar-search input {
  width: 100%;
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: 40px;
  padding: 9px 16px 9px 40px;
  color: var(--text);
  font-family: 'Syne', sans-serif;
  font-size: 14px;
  outline: none;
  transition: border-color var(--transition);
}

.topbar-search input:focus { border-color: var(--teal); }
.topbar-search input::placeholder { color: var(--text-dim); }

.topbar-search svg {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-dim);
  pointer-events: none;
}

.topbar-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-left: auto;
}

.btn-icon {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: var(--text-muted);
  transition: all var(--transition);
}

.btn-icon:hover { border-color: var(--border2); color: var(--text); background: var(--surface3); }

.btn-sync {
  background: var(--surface2);
  color: var(--teal);
  border: 1px solid #00c9a730;
  border-radius: var(--radius-sm);
  padding: 9px 18px;
  font-family: 'Syne', sans-serif;
  font-weight: 700;
  font-size: 13px;
  display: flex;
  align-items: center;
  gap: 7px;
  cursor: pointer;
  transition: all var(--transition);
  letter-spacing: 0.03em;
}

.btn-sync:hover { background: var(--teal-dim); border-color: var(--teal); }
.btn-sync:disabled { opacity: 0.5; cursor: not-allowed; }

.source-badge {
  display: inline-block;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  padding: 2px 6px;
  border-radius: 4px;
  background: var(--teal-dim);
  color: var(--teal);
  margin-left: 6px;
  vertical-align: middle;
}

.settings-row {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 16px;
}

.settings-row label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--text-muted);
}

.settings-row input {
  margin-bottom: 0 !important;
}

.webhook-status {
  font-size: 12px;
  padding: 10px 14px;
  border-radius: var(--radius-sm);
  background: var(--surface2);
  border: 1px solid var(--border);
  color: var(--text-muted);
  font-family: 'Space Mono', monospace;
  word-break: break-all;
  margin-bottom: 14px;
  min-height: 40px;
}

.webhook-status.active { border-color: var(--teal); color: var(--teal); }
.webhook-status.inactive { border-color: var(--border); color: var(--text-dim); }

.settings-divider {
  border: none;
  border-top: 1px solid var(--border);
  margin: 16px 0;
}

.btn-upload {
  background: var(--teal);
  color: #0a1a17;
  border: none;
  border-radius: var(--radius-sm);
  padding: 9px 18px;
  font-family: 'Syne', sans-serif;
  font-weight: 700;
  font-size: 13px;
  display: flex;
  align-items: center;
  gap: 7px;
  cursor: pointer;
  transition: opacity var(--transition), transform var(--transition);
  letter-spacing: 0.03em;
}

.btn-upload:hover { opacity: 0.88; transform: translateY(-1px); }

/* ── SIDEBAR ────────────────────────────────────────── */
.sidebar {
  background: var(--surface);
  border-right: 1px solid var(--border);
  padding: 20px 12px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.sidebar-section {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--text-dim);
  padding: 12px 12px 6px;
  margin-top: 8px;
}

.sidebar-section:first-child { margin-top: 0; }

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 12px;
  border-radius: var(--radius-sm);
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-muted);
  transition: all var(--transition);
  border: 1px solid transparent;
}

.nav-item:hover { background: var(--surface2); color: var(--text); }

.nav-item.active {
  background: var(--teal-dim);
  color: var(--teal);
  border-color: #00c9a720;
}

.nav-item svg { flex-shrink: 0; }

.folder-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 12px;
  border-radius: var(--radius-sm);
  cursor: pointer;
  font-size: 13px;
  color: var(--text-muted);
  transition: all var(--transition);
}

.folder-item:hover { background: var(--surface2); color: var(--text); }
.folder-item.active { background: var(--teal-dim); color: var(--teal); }

.folder-name {
  display: flex;
  align-items: center;
  gap: 8px;
}

.sidebar-storage {
  margin-top: auto;
  padding: 16px;
  background: var(--surface2);
  border-radius: var(--radius);
  border: 1px solid var(--border);
}

.storage-label {
  font-size: 11px;
  color: var(--text-muted);
  margin-bottom: 8px;
  display: flex;
  justify-content: space-between;
}

.storage-label span:last-child { color: var(--teal); }

.storage-bar {
  height: 4px;
  background: var(--surface3);
  border-radius: 2px;
  overflow: hidden;
}

.storage-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--teal), var(--blue));
  border-radius: 2px;
  width: 0%;
  transition: width 0.6s ease;
}

/* ── MAIN CONTENT ───────────────────────────────────── */
.main {
  overflow-y: auto;
  padding: 28px 32px;
  background: var(--bg);
}

.main-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
}

.main-title {
  font-size: 22px;
  font-weight: 800;
  letter-spacing: -0.5px;
}

.main-title span {
  color: var(--teal);
}

.view-toggle {
  display: flex;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  overflow: hidden;
}

.view-btn {
  padding: 7px 12px;
  cursor: pointer;
  color: var(--text-dim);
  border: none;
  background: transparent;
  transition: all var(--transition);
  display: flex;
  align-items: center;
}

.view-btn.active { background: var(--surface3); color: var(--teal); }

/* ── STATS ROW ──────────────────────────────────────── */
.stats-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 28px;
}

.stat-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--teal), transparent);
}

.stat-value {
  font-size: 28px;
  font-weight: 800;
  letter-spacing: -1px;
  font-family: 'Space Mono', monospace;
  color: var(--teal);
}

.stat-label {
  font-size: 12px;
  color: var(--text-muted);
  margin-top: 4px;
  font-weight: 600;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

/* ── DROP ZONE ──────────────────────────────────────── */
.drop-zone {
  border: 2px dashed var(--border2);
  border-radius: var(--radius);
  padding: 28px;
  text-align: center;
  margin-bottom: 28px;
  transition: all var(--transition);
  cursor: pointer;
  background: var(--surface);
}

.drop-zone:hover, .drop-zone.drag-over {
  border-color: var(--teal);
  background: var(--teal-dim);
}

.drop-zone svg { color: var(--text-dim); margin-bottom: 8px; }
.drop-zone:hover svg, .drop-zone.drag-over svg { color: var(--teal); }

.drop-text {
  font-size: 14px;
  color: var(--text-muted);
}

.drop-text strong { color: var(--teal); }

/* ── FILE GRID ──────────────────────────────────────── */
#files-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 16px;
}

#files-list {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.file-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 16px;
  cursor: pointer;
  transition: all var(--transition);
  position: relative;
  overflow: hidden;
  group: true;
}

.file-card:hover {
  border-color: var(--border2);
  background: var(--surface2);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px #00000040;
}

.file-card .file-actions {
  position: absolute;
  top: 10px;
  right: 10px;
  display: flex;
  gap: 6px;
  opacity: 0;
  transition: opacity var(--transition);
}

.file-card:hover .file-actions { opacity: 1; }

.fa-btn {
  background: var(--surface3);
  border: 1px solid var(--border2);
  border-radius: 6px;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: var(--text-muted);
  transition: all var(--transition);
  font-size: 11px;
}

.fa-btn:hover { color: var(--text); border-color: var(--teal); }
.fa-btn.del:hover { color: var(--red); border-color: var(--red); background: var(--red-dim); }

.file-icon {
  width: 52px;
  height: 52px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 12px;
  font-size: 22px;
}

.file-name {
  font-size: 13px;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 4px;
}

.file-meta {
  font-size: 11px;
  color: var(--text-muted);
  font-family: 'Space Mono', monospace;
}

/* ── LIST VIEW ──────────────────────────────────────── */
.file-row {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 12px 16px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  transition: all var(--transition);
  cursor: pointer;
}

.file-row:hover {
  background: var(--surface2);
  border-color: var(--border2);
}

.file-row-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  flex-shrink: 0;
}

.file-row-info { flex: 1; min-width: 0; }
.file-row-name {
  font-size: 14px;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.file-row-meta {
  font-size: 11px;
  color: var(--text-muted);
  font-family: 'Space Mono', monospace;
  margin-top: 2px;
}

.file-row-size {
  font-size: 12px;
  color: var(--text-muted);
  font-family: 'Space Mono', monospace;
  width: 80px;
  text-align: right;
  flex-shrink: 0;
}

.file-row-actions {
  display: flex;
  gap: 6px;
  opacity: 0;
  transition: opacity var(--transition);
}

.file-row:hover .file-row-actions { opacity: 1; }

/* ── UPLOAD PROGRESS ────────────────────────────────── */
#upload-progress {
  position: fixed;
  bottom: 24px;
  right: 24px;
  width: 320px;
  z-index: 100;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.progress-item {
  background: var(--surface);
  border: 1px solid var(--border2);
  border-radius: var(--radius);
  padding: 14px 16px;
  box-shadow: 0 8px 32px #00000060;
  animation: slideUp 0.2s ease;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

.progress-name {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 8px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.progress-bar-bg {
  height: 4px;
  background: var(--surface3);
  border-radius: 2px;
  overflow: hidden;
}

.progress-bar-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--teal), var(--blue));
  border-radius: 2px;
  transition: width 0.3s ease;
}

.progress-status {
  font-size: 11px;
  color: var(--text-muted);
  margin-top: 6px;
  font-family: 'Space Mono', monospace;
}

/* ── MODAL ──────────────────────────────────────────── */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: #00000080;
  backdrop-filter: blur(4px);
  z-index: 200;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  pointer-events: none;
  transition: opacity var(--transition);
}

.modal-overlay.open {
  opacity: 1;
  pointer-events: all;
}

.modal {
  background: var(--surface);
  border: 1px solid var(--border2);
  border-radius: 16px;
  padding: 28px;
  width: 380px;
  max-width: 95vw;
  transform: translateY(10px);
  transition: transform var(--transition);
  box-shadow: 0 32px 64px #00000080;
}

.modal-overlay.open .modal { transform: translateY(0); }

.modal h3 {
  font-size: 18px;
  font-weight: 800;
  margin-bottom: 16px;
  letter-spacing: -0.3px;
}

.modal input {
  width: 100%;
  background: var(--surface2);
  border: 1px solid var(--border2);
  border-radius: var(--radius-sm);
  padding: 11px 14px;
  color: var(--text);
  font-family: 'Syne', sans-serif;
  font-size: 14px;
  outline: none;
  transition: border-color var(--transition);
  margin-bottom: 14px;
}

.modal input:focus { border-color: var(--teal); }

.modal-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.btn-cancel {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 9px 18px;
  color: var(--text-muted);
  font-family: 'Syne', sans-serif;
  font-weight: 600;
  font-size: 13px;
  cursor: pointer;
  transition: all var(--transition);
}

.btn-cancel:hover { border-color: var(--border2); color: var(--text); }

.btn-confirm {
  background: var(--teal);
  color: #0a1a17;
  border: none;
  border-radius: var(--radius-sm);
  padding: 9px 18px;
  font-family: 'Syne', sans-serif;
  font-weight: 700;
  font-size: 13px;
  cursor: pointer;
  transition: opacity var(--transition);
}

.btn-confirm:hover { opacity: 0.88; }

/* ── TOAST ──────────────────────────────────────────── */
#toast {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%) translateY(12px);
  background: var(--surface);
  border: 1px solid var(--border2);
  border-radius: 40px;
  padding: 10px 20px;
  font-size: 13px;
  font-weight: 600;
  color: var(--text);
  z-index: 300;
  opacity: 0;
  transition: all 0.25s ease;
  box-shadow: 0 8px 32px #00000060;
  pointer-events: none;
  white-space: nowrap;
}

#toast.show {
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}

#toast.success { border-color: var(--teal); color: var(--teal); }
#toast.error   { border-color: var(--red); color: var(--red); }

/* ── EMPTY STATE ────────────────────────────────────── */
.empty-state {
  text-align: center;
  padding: 80px 20px;
  color: var(--text-dim);
}

.empty-state svg { margin-bottom: 16px; opacity: 0.4; }
.empty-state h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; color: var(--text-muted); }
.empty-state p { font-size: 13px; }

/* ── FILE TYPE COLORS ───────────────────────────────── */
.type-image  { background: #3b82f615; color: #3b82f6; }
.type-video  { background: #8b5cf615; color: #8b5cf6; }
.type-audio  { background: #ec489915; color: #ec4899; }
.type-doc    { background: #f59e0b15; color: #f59e0b; }
.type-zip    { background: #10b98115; color: #10b981; }
.type-code   { background: #00c9a715; color: #00c9a7; }
.type-folder { background: #fbbf2415; color: #fbbf24; }
.type-other  { background: #64748b15; color: #64748b; }

/* ── BREADCRUMB ─────────────────────────────────────── */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 20px;
  font-size: 13px;
  color: var(--text-muted);
}

.breadcrumb span { color: var(--text-dim); }
.breadcrumb a { color: var(--text-muted); cursor: pointer; transition: color var(--transition); }
.breadcrumb a:hover { color: var(--teal); }

/* ── HIDDEN FILE INPUT ──────────────────────────────── */
#file-input { display: none; }

/* ── LOADING SPINNER ─────────────────────────────────── */
.spinner {
  width: 20px;
  height: 20px;
  border: 2px solid var(--border2);
  border-top-color: var(--teal);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  display: inline-block;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* ── RESPONSIVE ─────────────────────────────────────── */
@media (max-width: 768px) {
  #app {
    grid-template-columns: 1fr;
    grid-template-rows: 64px 1fr;
  }
  .sidebar { display: none; }
  .main { padding: 16px; }
  .stats-row { grid-template-columns: repeat(2, 1fr); }
  #files-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
}
</style>
</head>
<body>

<!-- ══ LOGIN SCREEN ══════════════════════════════════════════ -->
<div id="login-screen">
  <div class="login-card">
    <div class="login-logo">
      <svg width="32" height="32" fill="none" viewBox="0 0 32 32">
        <rect width="32" height="32" rx="8" fill="#00c9a722"/>
        <path d="M6 16l10-8 10 8M9 14v9h14v-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M13 23v-5h6v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
      </svg>
      <h1>Telegram<span>Drive</span></h1>
    </div>
    <label>Password</label>
    <input type="password" id="login-pw" placeholder="Enter your drive password" />
    <button class="btn-primary" onclick="doLogin()">Access Drive</button>
    <div class="login-error" id="login-error">Incorrect password. Try again.</div>
  </div>
</div>

<!-- ══ MAIN APP ══════════════════════════════════════════════ -->
<div id="app" style="display:none">

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-logo">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
        <path d="M3 12l9-7 9 7M6 10.5V19h12V10.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M10 19v-4h4v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
      </svg>
      Telegram<span>Drive</span>
    </div>

    <div class="topbar-search">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
        <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
      <input type="text" id="search-input" placeholder="Search files…" oninput="filterFiles()">
    </div>

    <div class="topbar-actions">
      <button class="btn-icon" title="New Folder" onclick="showFolderModal()">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
          <path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" stroke="currentColor" stroke-width="1.8"/>
          <path d="M12 11v4M10 13h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </button>
      <button class="btn-icon" title="Refresh" onclick="loadFiles()">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
          <path d="M4 12a8 8 0 018-8 8 8 0 016.93 4M20 12a8 8 0 01-8 8 8 8 0 01-6.93-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          <path d="M19 4v4h-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <button class="btn-sync" id="sync-btn" onclick="syncFromTelegram()" title="Pull forwarded files from Telegram">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
          <path d="M21 3L3 10.5l7.5 3L14 21l3-7.5L21 3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
        </svg>
        Sync
      </button>
      <button class="btn-upload" onclick="document.getElementById('file-input').click()">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
          <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12M8 8l4-4 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Upload
      </button>
      <button class="btn-icon" title="Settings / Webhook" onclick="openModal('settings-modal')">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
          <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" stroke="currentColor" stroke-width="1.8"/>
        </svg>
      </button>
      <button class="btn-icon" title="Logout" onclick="doLogout()">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
          <path d="M17 16l4-4m0 0l-4-4m4 4H7M13 4H5a2 2 0 00-2 2v12a2 2 0 002 2h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  </header>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-section">Navigation</div>
    <div class="nav-item active" onclick="setSection('all', this)">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
        <rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
        <rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
        <rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
        <rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
      </svg>
      All Files
    </div>
    <div class="nav-item" onclick="setSection('image', this)">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
        <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/>
        <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.5"/>
        <path d="M3 15l5-5 4 4 3-3 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      Images
    </div>
    <div class="nav-item" onclick="setSection('video', this)">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
        <rect x="2" y="5" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/>
        <path d="M16 9l6-3v12l-6-3V9z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
      </svg>
      Videos
    </div>
    <div class="nav-item" onclick="setSection('audio', this)">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
        <path d="M9 18V5l12-2v13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        <circle cx="6" cy="18" r="3" stroke="currentColor" stroke-width="1.8"/>
        <circle cx="18" cy="16" r="3" stroke="currentColor" stroke-width="1.8"/>
      </svg>
      Audio
    </div>
    <div class="nav-item" onclick="setSection('doc', this)">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="1.8"/>
        <path d="M14 2v6h6M8 12h8M8 16h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
      </svg>
      Documents
    </div>

    <div class="sidebar-section">Folders</div>
    <div id="folders-list"></div>

    <div class="sidebar-storage">
      <div class="storage-label">
        <span>Telegram Storage</span>
        <span>Unlimited ∞</span>
      </div>
      <div class="storage-bar">
        <div class="storage-fill" id="storage-fill" style="width:2%"></div>
      </div>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main">
    <div class="main-header">
      <div>
        <div class="breadcrumb">
          <a onclick="setSection('all')">Drive</a>
          <span>›</span>
          <span id="breadcrumb-current">All Files</span>
        </div>
        <h2 class="main-title" id="section-title">All Files <span id="file-count-badge"></span></h2>
      </div>
      <div class="view-toggle">
        <button class="view-btn active" id="grid-btn" onclick="setView('grid')" title="Grid view">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
            <rect x="14" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
            <rect x="3" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
            <rect x="14" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
          </svg>
        </button>
        <button class="view-btn" id="list-btn" onclick="setView('list')" title="List view">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
            <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row" id="stats-row">
      <div class="stat-card">
        <div class="stat-value" id="stat-total">0</div>
        <div class="stat-label">Total Files</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="stat-images">0</div>
        <div class="stat-label">Images</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="stat-videos">0</div>
        <div class="stat-label">Videos</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="stat-size">0 B</div>
        <div class="stat-label">Total Size</div>
      </div>
    </div>

    <!-- Drop Zone -->
    <div class="drop-zone" id="drop-zone" onclick="document.getElementById('file-input').click()">
      <svg width="36" height="36" fill="none" viewBox="0 0 24 24">
        <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12M8 8l4-4 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <div class="drop-text">
        <strong>Click to upload</strong> or drag &amp; drop files here<br>
        <small style="color:var(--text-dim)">Max 50MB per file · Any format</small>
      </div>
    </div>

    <!-- Files Grid -->
    <div id="files-grid"></div>
    <div id="files-list" style="display:none"></div>
  </main>
</div>

<!-- ══ MODALS ════════════════════════════════════════════════ -->
<div class="modal-overlay" id="folder-modal">
  <div class="modal">
    <h3>📁 New Folder</h3>
    <input type="text" id="folder-name-input" placeholder="Folder name" />
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('folder-modal')">Cancel</button>
      <button class="btn-confirm" onclick="createFolder()">Create</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="delete-modal">
  <div class="modal">
    <h3>🗑️ Delete File</h3>
    <p style="color:var(--text-muted);font-size:14px;margin-bottom:20px">
      This will permanently delete <strong id="delete-file-name" style="color:var(--text)"></strong> from Telegram. This cannot be undone.
    </p>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('delete-modal')">Cancel</button>
      <button class="btn-confirm" style="background:var(--red);color:#fff" onclick="confirmDelete()">Delete</button>
    </div>
  </div>
</div>

<!-- Settings / Webhook Modal -->
<div class="modal-overlay" id="settings-modal">
  <div class="modal" style="width:460px">
    <h3>⚙️ Settings</h3>

    <p style="font-size:13px;color:var(--text-muted);margin-bottom:18px;line-height:1.6">
      <strong style="color:var(--teal)">Sync</strong> pulls all files &amp; forwarded media from your bot via <code style="font-family:Space Mono,monospace;font-size:11px;background:var(--surface2);padding:1px 5px;border-radius:4px">getUpdates</code>.<br>
      <strong style="color:var(--teal)">Webhook</strong> lets Telegram push new messages instantly (requires public HTTPS URL).
    </p>

    <!-- Manual Sync -->
    <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;margin-bottom:16px">
      <div style="font-size:13px;font-weight:700;margin-bottom:8px">📥 Manual Sync</div>
      <div style="font-size:12px;color:var(--text-muted);margin-bottom:12px">
        Pull all messages your bot has received (including forwarded files) since last sync.
        Run this after forwarding files to your bot.
      </div>
      <button class="btn-confirm" style="width:100%" onclick="syncFromTelegram();closeModal('settings-modal')">
        Sync Now from Telegram
      </button>
    </div>

    <hr class="settings-divider">

    <!-- Webhook Setup -->
    <div style="font-size:13px;font-weight:700;margin-bottom:10px">🔗 Webhook (Auto-sync)</div>

    <div style="font-size:12px;color:var(--text-muted);margin-bottom:12px">Current webhook:</div>
    <div class="webhook-status inactive" id="webhook-status-display">Loading…</div>

    <div class="settings-row">
      <label>Your webhook URL</label>
      <input type="url" id="webhook-url-input" placeholder="https://yourdomain.com/telegram-drive/webhook.php" />
    </div>

    <div style="display:flex;gap:10px;margin-bottom:4px">
      <button class="btn-confirm" style="flex:1" onclick="setWebhook()">Set Webhook</button>
      <button class="btn-cancel" style="flex:1;color:var(--red);border-color:var(--red-dim)" onclick="deleteWebhook()">Remove Webhook</button>
    </div>
    <div style="font-size:11px;color:var(--text-dim);margin-top:8px">
      ⚠ Webhook requires HTTPS. Once set, forwarded files appear in the drive instantly.
    </div>

    <hr class="settings-divider">

    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('settings-modal')">Close</button>
    </div>
  </div>
</div>

<!-- Upload Progress -->
<div id="upload-progress"></div>

<!-- Toast -->
<div id="toast"></div>

<!-- Hidden file input -->
<input type="file" id="file-input" multiple onchange="handleFileSelect(this.files)">

<script>
// ══ STATE ════════════════════════════════════════════════════
let allFiles = [];
let currentSection = 'all';
let currentView = 'grid';
let currentFolder = null;
let deleteTargetId = null;

// ══ AUTH ═════════════════════════════════════════════════════
async function checkAuth() {
  const r = await ajax({ action: 'check_auth' }, 'GET');
  if (r.ok) showApp();
}

async function doLogin() {
  const pw = document.getElementById('login-pw').value;
  const r  = await ajax({ action: 'login', password: pw });
  if (r.ok) {
    showApp();
  } else {
    document.getElementById('login-error').style.display = 'block';
  }
}

document.getElementById('login-pw').addEventListener('keydown', e => {
  if (e.key === 'Enter') doLogin();
});

async function doLogout() {
  await ajax({ action: 'logout' });
  document.getElementById('app').style.display = 'none';
  document.getElementById('login-screen').style.display = 'flex';
}

function showApp() {
  document.getElementById('login-screen').style.display = 'none';
  document.getElementById('app').style.display = 'grid';
  loadFiles();
}

// ══ AJAX HELPER ══════════════════════════════════════════════
function ajax(data, method = 'POST') {
  const fd = new FormData();
  for (const [k, v] of Object.entries(data)) fd.append(k, v);
  return fetch('api.php' + (method === 'GET' ? '?' + new URLSearchParams(data) : ''), {
    method,
    body: method === 'POST' ? fd : undefined,
  }).then(r => r.json()).catch(() => ({ ok: false, error: 'Network error' }));
}

// ══ LOAD FILES ════════════════════════════════════════════════
async function loadFiles() {
  const r = await ajax({ action: 'list' }, 'GET');
  if (!r.ok) { showToast('Failed to load files', 'error'); return; }
  allFiles = r.files || [];
  renderAll();
  updateStats();
  renderFolders();
}

// ══ SYNC FROM TELEGRAM ═══════════════════════════════════════
let syncing = false;

async function syncFromTelegram(resync) {
  if (syncing) return;
  syncing = true;

  const btn = document.getElementById('sync-btn');
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner" style="width:14px;height:14px;border-width:2px"></span> Syncing…';
  }

  showToast('Syncing with Telegram…');

  const payload = { action: 'sync' };
  if (resync) payload.resync = '1';

  const r = await ajax(payload);

  if (btn) {
    btn.disabled = false;
    btn.innerHTML = `<svg width="15" height="15" fill="none" viewBox="0 0 24 24">
      <path d="M21 3L3 10.5l7.5 3L14 21l3-7.5L21 3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
    </svg> Sync`;
  }

  syncing = false;

  if (r.ok) {
    const msg = r.imported > 0
      ? `✓ ${r.imported} new file${r.imported !== 1 ? 's' : ''} imported from Telegram`
      : 'Already up to date — no new files found';
    showToast(msg, r.imported > 0 ? 'success' : '');
    if (r.imported > 0) await loadFiles();
  } else {
    showToast('Sync failed: ' + (r.error || 'unknown error'), 'error');
  }
}

// ══ RENDER ════════════════════════════════════════════════════
function getFilteredFiles() {
  const q = document.getElementById('search-input').value.toLowerCase();
  return allFiles.filter(f => {
    const matchSearch = !q || f.name.toLowerCase().includes(q);
    const matchSection = currentSection === 'all' || getFileType(f) === currentSection;
    const matchFolder  = !currentFolder || f.folder === currentFolder;
    return matchSearch && matchSection && matchFolder;
  });
}

function renderAll() {
  const files = getFilteredFiles();
  document.getElementById('file-count-badge').textContent = files.length ? `(${files.length})` : '';

  if (currentView === 'grid') {
    document.getElementById('files-grid').style.display = 'grid';
    document.getElementById('files-list').style.display = 'none';
    renderGrid(files);
  } else {
    document.getElementById('files-grid').style.display = 'none';
    document.getElementById('files-list').style.display = 'flex';
    renderList(files);
  }
}

function renderGrid(files) {
  const el = document.getElementById('files-grid');
  if (!files.length) { el.innerHTML = emptyState(); return; }
  el.innerHTML = files.map(f => `
    <div class="file-card" ondblclick="downloadFile('${f.id}')">
      <div class="file-actions">
        <div class="fa-btn" title="Download" onclick="event.stopPropagation();downloadFile('${f.id}')">⬇</div>
        <div class="fa-btn del" title="Delete" onclick="event.stopPropagation();askDelete('${f.id}','${escHtml(f.name)}')">✕</div>
      </div>
      <div class="file-icon ${typeClass(f)}">${typeEmoji(f)}</div>
      <div class="file-name" title="${escHtml(f.name)}">${escHtml(f.name)}</div>
      <div class="file-meta">${formatBytes(f.size)} · ${timeAgo(f.uploaded_at)}</div>
    </div>
  `).join('');
}

function renderList(files) {
  const el = document.getElementById('files-list');
  if (!files.length) { el.innerHTML = emptyState(); return; }
  el.innerHTML = files.map(f => `
    <div class="file-row">
      <div class="file-row-icon ${typeClass(f)}">${typeEmoji(f)}</div>
      <div class="file-row-info">
        <div class="file-row-name">${escHtml(f.name)}</div>
        <div class="file-row-meta">${escHtml(f.folder)} · ${timeAgo(f.uploaded_at)}</div>
      </div>
      <div class="file-row-size">${formatBytes(f.size)}</div>
      <div class="file-row-actions">
        <div class="fa-btn" title="Download" onclick="downloadFile('${f.id}')">⬇</div>
        <div class="fa-btn del" title="Delete" onclick="askDelete('${f.id}','${escHtml(f.name)}')">✕</div>
      </div>
    </div>
  `).join('');
}

function emptyState() {
  return `<div class="empty-state" style="grid-column:1/-1">
    <svg width="48" height="48" fill="none" viewBox="0 0 24 24">
      <path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" stroke="currentColor" stroke-width="1.5"/>
    </svg>
    <h3>No files here</h3>
    <p>Upload files to get started</p>
  </div>`;
}

// ══ STATS ════════════════════════════════════════════════════
function updateStats() {
  const imgs  = allFiles.filter(f => getFileType(f) === 'image').length;
  const vids  = allFiles.filter(f => getFileType(f) === 'video').length;
  const total = allFiles.reduce((s, f) => s + (f.size || 0), 0);

  document.getElementById('stat-total').textContent  = allFiles.length;
  document.getElementById('stat-images').textContent = imgs;
  document.getElementById('stat-videos').textContent = vids;
  document.getElementById('stat-size').textContent   = formatBytes(total);
}

// ══ FOLDERS ══════════════════════════════════════════════════
function renderFolders() {
  const folders = [...new Set(allFiles.map(f => f.folder).filter(Boolean).filter(f => f !== 'Root'))];
  const el = document.getElementById('folders-list');
  el.innerHTML = folders.map(name => `
    <div class="folder-item ${currentFolder === name ? 'active' : ''}" onclick="setFolder('${escHtml(name)}')">
      <div class="folder-name">📁 <span>${escHtml(name)}</span></div>
      <small style="color:var(--text-dim);font-size:10px">${allFiles.filter(f=>f.folder===name).length}</small>
    </div>
  `).join('');
}

function setFolder(name) {
  currentFolder = (currentFolder === name) ? null : name;
  document.getElementById('breadcrumb-current').textContent = currentFolder || 'All Files';
  renderAll();
  renderFolders();
}

// ══ UPLOAD ═══════════════════════════════════════════════════
function handleFileSelect(files) {
  for (const f of files) uploadFile(f);
  document.getElementById('file-input').value = '';
}

async function uploadFile(file) {
  const pid  = 'p_' + Date.now() + Math.random();
  const prog = addProgress(file.name, pid);

  const fd = new FormData();
  fd.append('action', 'upload');
  fd.append('file', file);
  fd.append('folder', currentFolder || 'Root');

  // Simulate progress (Fetch doesn't expose upload progress easily without XHR)
  let pct = 0;
  const interval = setInterval(() => {
    pct = Math.min(pct + Math.random() * 15, 90);
    updateProgress(pid, pct, 'Uploading…');
  }, 300);

  try {
    const r = await fetch('api.php', { method: 'POST', body: fd });
    const data = await r.json();
    clearInterval(interval);

    if (data.ok) {
      updateProgress(pid, 100, 'Done ✓');
      setTimeout(() => removeProgress(pid), 1800);
      allFiles.unshift(data.file);
      renderAll();
      updateStats();
      renderFolders();
      showToast(`${file.name} uploaded`, 'success');
    } else {
      updateProgress(pid, 100, '✗ ' + (data.error || 'Failed'));
      prog.querySelector('.progress-bar-fill').style.background = 'var(--red)';
      setTimeout(() => removeProgress(pid), 3000);
    }
  } catch (e) {
    clearInterval(interval);
    updateProgress(pid, 100, '✗ Network error');
    setTimeout(() => removeProgress(pid), 3000);
  }
}

function addProgress(name, id) {
  const wrap = document.getElementById('upload-progress');
  const el = document.createElement('div');
  el.className = 'progress-item';
  el.id = id;
  el.innerHTML = `
    <div class="progress-name">📤 ${escHtml(name)}</div>
    <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:0%"></div></div>
    <div class="progress-status">Starting…</div>
  `;
  wrap.appendChild(el);
  return el;
}

function updateProgress(id, pct, status) {
  const el = document.getElementById(id);
  if (!el) return;
  el.querySelector('.progress-bar-fill').style.width = pct + '%';
  el.querySelector('.progress-status').textContent = status;
}

function removeProgress(id) {
  const el = document.getElementById(id);
  if (el) el.remove();
}

// ══ DOWNLOAD ═════════════════════════════════════════════════
function downloadFile(id) {
  window.location.href = `api.php?action=download&id=${id}`;
}

// ══ DELETE ═══════════════════════════════════════════════════
function askDelete(id, name) {
  deleteTargetId = id;
  document.getElementById('delete-file-name').textContent = name;
  openModal('delete-modal');
}

async function confirmDelete() {
  if (!deleteTargetId) return;
  closeModal('delete-modal');
  const r = await ajax({ action: 'delete', id: deleteTargetId });
  if (r.ok) {
    allFiles = allFiles.filter(f => f.id !== deleteTargetId);
    renderAll();
    updateStats();
    renderFolders();
    showToast('File deleted', 'success');
  } else {
    showToast('Delete failed', 'error');
  }
  deleteTargetId = null;
}

// ══ FOLDER MODAL ═════════════════════════════════════════════
function showFolderModal() {
  document.getElementById('folder-name-input').value = '';
  openModal('folder-modal');
}

async function createFolder() {
  const name = document.getElementById('folder-name-input').value.trim();
  if (!name) return;
  closeModal('folder-modal');
  const r = await ajax({ action: 'create_folder', name });
  if (r.ok) {
    allFiles.unshift(r.folder);
    renderAll();
    renderFolders();
    showToast(`Folder "${name}" created`, 'success');
  }
}

document.getElementById('folder-name-input').addEventListener('keydown', e => {
  if (e.key === 'Enter') createFolder();
});

// ══ VIEW / SECTION ════════════════════════════════════════════
function setView(v) {
  currentView = v;
  document.getElementById('grid-btn').classList.toggle('active', v === 'grid');
  document.getElementById('list-btn').classList.toggle('active', v === 'list');
  renderAll();
}

function setSection(sec, el) {
  currentSection = sec;
  currentFolder  = null;
  document.querySelectorAll('.nav-item').forEach(e => e.classList.remove('active'));
  if (el) el.classList.add('active');

  const labels = { all: 'All Files', image: 'Images', video: 'Videos', audio: 'Audio', doc: 'Documents' };
  document.getElementById('section-title').innerHTML =
    (labels[sec] || sec) + ' <span id="file-count-badge"></span>';
  document.getElementById('breadcrumb-current').textContent = labels[sec] || sec;
  renderAll();
}

function filterFiles() { renderAll(); }

// ══ DRAG & DROP ══════════════════════════════════════════════
const dz = document.getElementById('drop-zone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
dz.addEventListener('drop', e => {
  e.preventDefault();
  dz.classList.remove('drag-over');
  handleFileSelect(e.dataTransfer.files);
});

// ══ MODALS ═══════════════════════════════════════════════════
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
});

// ══ TOAST ════════════════════════════════════════════════════
let toastTimer;
function showToast(msg, type = '') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'show ' + type;
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => { t.className = ''; }, 2800);
}

// ══ HELPERS ══════════════════════════════════════════════════
function getFileType(f) {
  const m = f.mime || '';
  if (m === 'folder') return 'folder';
  if (m.startsWith('image/')) return 'image';
  if (m.startsWith('video/')) return 'video';
  if (m.startsWith('audio/')) return 'audio';
  if (m.includes('pdf') || m.includes('word') || m.includes('text') || m.includes('spreadsheet') || m.includes('presentation')) return 'doc';
  if (m.includes('zip') || m.includes('rar') || m.includes('tar') || m.includes('gzip')) return 'zip';
  if (m.includes('javascript') || m.includes('json') || m.includes('html') || m.includes('css') || m.includes('xml')) return 'code';
  return 'other';
}

function typeClass(f) {
  const t = getFileType(f);
  return { image:'type-image', video:'type-video', audio:'type-audio', doc:'type-doc', zip:'type-zip', code:'type-code', folder:'type-folder' }[t] || 'type-other';
}

function typeEmoji(f) {
  const t = getFileType(f);
  return { image:'🖼️', video:'🎬', audio:'🎵', doc:'📄', zip:'🗜️', code:'💻', folder:'📁' }[t] || '📎';
}

function formatBytes(b) {
  if (!b) return '0 B';
  if (b >= 1073741824) return (b/1073741824).toFixed(2) + ' GB';
  if (b >= 1048576)    return (b/1048576).toFixed(2) + ' MB';
  if (b >= 1024)       return (b/1024).toFixed(1) + ' KB';
  return b + ' B';
}

function timeAgo(ts) {
  const diff = Math.floor(Date.now()/1000) - ts;
  if (diff < 60)   return 'just now';
  if (diff < 3600) return Math.floor(diff/60) + 'm ago';
  if (diff < 86400)return Math.floor(diff/3600) + 'h ago';
  return Math.floor(diff/86400) + 'd ago';
}

function escHtml(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

// ══ INIT ═════════════════════════════════════════════════════
checkAuth();
</script>
</body>
</html>
