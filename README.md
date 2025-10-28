# Mata ASN Ku

Aplikasi internal untuk pengelolaan data dan proses kerja ASN dengan antarmuka modern, realtime update, serta dukungan komponen interaktif. Dibangun di atas Laravel, Livewire, TailwindCSS, dan Vite, dengan Bun sebagai package manager dan task runner frontend.

## Fitur Singkat
- Livewire komponen interaktif dan tabel dinamis (PowerGrid)
- UI siap pakai dengan WireUI dan DaisyUI
- Build modern via Vite + TailwindCSS
- Realtime (Laravel Reverb/WebSocket)
- Antrean dan monitoring via Laravel Horizon

## Teknologi yang Digunakan
- Laravel (Framework backend)
- Livewire, WireUI, PowerGrid (Komponen UI dan tabel)
- TailwindCSS + DaisyUI (Styling)
- Vite (Build tool/front-end dev server)
- Bun (Package manager dan runner) – alternatif Node.js
- Laravel Horizon (Queue worker/dashboard)
- Laravel Reverb (WebSocket)

## Prasyarat Sistem
- PHP ≥ 8.2 dengan ekstensi umum (mbstring, openssl, pdo, tokenizer, xml, ctype, json)
- Composer
- Database: SQLite (tersedia `database/database.sqlite`) atau MySQL/PostgreSQL
- Git
- Bun (disarankan) atau Node.js

## Instalasi Backend (Laravel & Composer)
Jalankan di PowerShell pada folder proyek.

```powershell
composer install

copy .env.example .env
php artisan key:generate

# Opsi A: SQLite (cepat untuk lokal)
# Pastikan file ini ada: database/database.sqlite
# Di .env atur:
# DB_CONNECTION=sqlite
# DB_DATABASE="/database/database.sqlite"

# Opsi B: MySQL/PostgreSQL
# Sesuaikan DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

php artisan migrate
php artisan storage:link
```

## Instalasi Frontend (Bun)
Install Bun di Windows (PowerShell):

```powershell
irm bun.sh/install.ps1 | iex
```

Lalu install dependensi frontend:

```powershell
bun install
```

## Menjalankan Aplikasi (Development)
Terdapat dua cara: terintegrasi (satu perintah) atau manual (proses terpisah).

### Opsi 1 — Terintegrasi (satu perintah)
Menjalankan PHP server, Vite, Horizon, dan Reverb secara paralel.

```powershell
bun run start
```

Layanan yang berjalan:
- Laravel (PHP) dev server: http://127.0.0.1:8000
- Vite dev server: http://127.0.0.1:5173
- Horizon (queue): berjalan sebagai worker + dashboard pada aplikasi Laravel
- Reverb (WebSocket): dijalankan oleh `php artisan reverb:start --debug`

### Opsi 2 — Manual (terpisah)
Jalankan pada terminal terpisah:

```powershell
# Terminal A: Laravel
bun run serve   # setara: php artisan serve  → http://127.0.0.1:8000

# Terminal B: Vite
bun run dev     # Vite dev server           → http://127.0.0.1:5173

# (Opsional) Terminal C: Horizon
php artisan horizon

# (Opsional) Terminal D: Reverb (WebSocket)
php artisan reverb:start --debug
```

## Port Akses
- Development:
  - Aplikasi Laravel: 8000
  - Vite: 5173
- Production/Deploy:
  - Mengikuti environment variable `PORT` (lihat pengaturan Nginx pada `nixpacks.toml`).

## Skrip yang Tersedia (package.json)
```bash
# Frontend
bun run dev            # Menjalankan Vite dev server
bun run build          # Build aset produksi

# Backend & layanan
bun run serve          # php artisan serve
bun run start-queue    # php artisan horizon
bun run dev-websocket  # php artisan reverb:start --debug

# Terintegrasi (disarankan untuk lokal)
bun run start          # serve + dev + horizon + reverb (paralel)
```

## Build Produksi
```powershell
bun run build
```
Hasil build akan diproses oleh Laravel Vite Plugin. Pastikan variabel `APP_ENV=production` dan `APP_DEBUG=false` pada `.env` saat produksi.

## Catatan Deploy (Nixpacks/Container)
- Proses start menggunakan Nginx + PHP-FPM dengan binding ke `PORT` dari environment (lihat `nixpacks.toml`).
- Perintah pasca build (otomatis):
  - `php artisan optimize:clear`
  - `php artisan storage:link`
  - `php artisan migrate --force`

## Struktur Build Frontend
- Entry: `resources/css/app.css`, `resources/js/app.js` (lihat `vite.config.js`)
- Alias path: `_/`, `@/` (JS), `~/` (CSS), `$/` (vendor)
- Tailwind: `tailwind.config.js` dengan plugin Forms, Typography, DaisyUI, WireUI, dan ikon

## Troubleshooting Singkat
- Aset tidak termuat saat dev: pastikan `bun run dev` berjalan dan plugin Laravel Vite aktif, lalu refresh halaman Laravel di port 8000.
- Error database: cek koneksi di `.env`. Untuk SQLite, pastikan path absolut di Windows sudah benar dan file `database.sqlite` dapat diakses.
- WebSocket tidak tersambung: jalankan `php artisan reverb:start` (atau gunakan `bun run start`). Sesuaikan konfigurasi Reverb di `.env` bila diperlukan.
- Antrean tidak diproses: pastikan Horizon aktif (`php artisan horizon`) dan queue driver sesuai (`QUEUE_CONNECTION` di `.env`).

---

Selamat mengembangkan! Jika ada pertanyaan internal tim, sertakan konfigurasi `.env` yang relevan (tanpa kredensial sensitif) saat melapor.

