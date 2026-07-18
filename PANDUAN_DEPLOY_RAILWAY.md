# Panduan Deploy "I Care True" ke Railway

Railway mendukung Laravel secara penuh: PHP versi bebas, Composer/Artisan
otomatis lewat build script, proses persisten (jadi Reverb/WebSocket untuk
live chat & game bisa jalan beneran), cron asli, dan database MySQL/PostgreSQL
bawaan. Tidak ada workaround aneh seperti di InfinityFree.

## Ringkasan arsitektur di Railway
Kita akan buat **3 service** dalam 1 project Railway:
1. **Web** — aplikasi Laravel utama (HTTP)
2. **Reverb** — WebSocket server untuk live chat & game real-time
3. **MySQL** — database (plugin bawaan Railway)

## 1. Buat akun & buat repo GitHub

Railway deploy dari GitHub repo. Karena repo lokal ini masih terhubung ke
template resmi `laravel/laravel`, kita perlu buat repo GitHub baru milik Anda:

1. Buka https://github.com/new, buat repo baru (misal `icare-true`), jangan
   centang "Initialize with README" (biar tidak konflik).
2. Di komputer, jalankan (ganti URL dengan repo Anda):
   ```
   git remote remove origin
   git remote add origin https://github.com/USERNAME_ANDA/icare-true.git
   git push -u origin main-icare
   ```
3. Kalau diminta login, gunakan GitHub Personal Access Token (bukan password) —
   buat di https://github.com/settings/tokens jika belum punya.

## 2. Buat project Railway

1. Daftar/login di https://railway.app dengan akun GitHub Anda.
2. Klik **New Project** > **Deploy from GitHub repo** > pilih repo `icare-true`.
3. Railway akan otomatis mendeteksi PHP/Laravel lewat Nixpacks, memakai
   konfigurasi dari file `nixpacks.toml` yang sudah disiapkan di root project
   (mengatur build: composer install, npm build, cache config; dan start:
   migrate + serve).

## 3. Tambahkan database MySQL

1. Di dashboard project Railway, klik **+ New** > **Database** > **Add MySQL**.
2. Railway otomatis menyediakan variabel `MYSQLHOST`, `MYSQLPORT`,
   `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD` yang bisa direferensikan
   dari service lain.

## 4. Set environment variables untuk service Web

Buka service Laravel Anda > tab **Variables** > isi berdasarkan
`.env.railway.example` yang sudah disiapkan:

- `APP_KEY` — generate baru secara lokal: `php artisan key:generate --show`,
  salin hasilnya (termasuk prefix `base64:`).
- `APP_URL` — Railway kasih domain otomatis (`xxx.up.railway.app`), isi setelah
  domain muncul di tab **Settings > Networking**.
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — pakai
  referensi variabel Railway persis seperti di `.env.railway.example`:
  ```
  DB_HOST=${{MySQL.MYSQLHOST}}
  DB_PORT=${{MySQL.MYSQLPORT}}
  DB_DATABASE=${{MySQL.MYSQLDATABASE}}
  DB_USERNAME=${{MySQL.MYSQLUSER}}
  DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
  ```
  (Railway otomatis mengganti `${{MySQL.XXX}}` dengan nilai asli dari service
  MySQL yang sudah dibuat di langkah 3 — asalkan nama service-nya "MySQL".)
- Variabel lain: copy semua dari `.env.railway.example`, ganti
  `REVERB_HOST`/`MAIL_*` sesuai kebutuhan (lihat langkah 5 untuk Reverb).

3. Klik **Deploy** — Railway akan build & jalankan aplikasi otomatis.

## 5. Deploy service Reverb (WebSocket, untuk live chat & game real-time)

1. Di project Railway yang sama, klik **+ New** > **GitHub Repo** > pilih repo
   yang sama (`icare-true`) lagi — ini akan jadi service kedua.
2. Beri nama service ini `reverb`.
3. Di tab **Settings** service `reverb`, override **Start Command** jadi:
   ```
   php artisan reverb:start --host=0.0.0.0 --port=$PORT
   ```
4. Di tab **Variables** service `reverb`, isi variabel yang sama seperti
   service Web (terutama `APP_KEY`, `DB_*`, `REVERB_APP_ID`,
   `REVERB_APP_KEY`, `REVERB_APP_SECRET` — HARUS SAMA PERSIS dengan service Web
   supaya autentikasi WebSocket cocok).
5. Setelah deploy, catat domain publik service `reverb` ini (tab **Settings >
   Networking > Generate Domain**).
6. Kembali ke service **Web**, update variabel:
   ```
   REVERB_HOST=domain-reverb-anda.up.railway.app
   REVERB_PORT=443
   REVERB_SCHEME=https
   VITE_REVERB_HOST="${REVERB_HOST}"
   VITE_REVERB_PORT="${REVERB_PORT}"
   VITE_REVERB_SCHEME="${REVERB_SCHEME}"
   ```
7. Redeploy service Web (rebuild otomatis kalau ada perubahan variabel, atau
   klik **Redeploy** manual) supaya asset frontend ikut menggunakan REVERB_HOST
   yang benar (karena `VITE_*` di-embed saat build, bukan runtime).

## 6. Migrasi & seed database

`nixpacks.toml` sudah otomatis menjalankan `php artisan migrate --force` setiap
kali start. Untuk seed data awal, jalankan manual sekali lewat Railway CLI:

```
npm install -g @railway/cli
railway login
railway link   # pilih project ini
railway run php artisan db:seed --force
```

## 7. Cron job (scheduled command)

Railway punya cron asli. Tambahkan **+ New > Cron Job** di project, isi:
- Schedule: `* * * * *` (tiap menit, standar Laravel scheduler)
- Command: `php artisan schedule:run`

Ini akan otomatis menjalankan `notify:daily-verse` dan
`notify:schedule-reminders` sesuai jadwal yang didefinisikan di
`routes/console.php` — tidak perlu workaround webhook seperti di InfinityFree.

## 8. Domain kustom (opsional)

Di tab **Settings > Networking** service Web, klik **Custom Domain** untuk
memasang domain Anda sendiri (arahkan CNAME sesuai instruksi Railway).

## 9. Test aplikasi

Buka domain Railway Anda. Kalau error, cek log lewat dashboard Railway (tab
**Deployments** > klik deployment terbaru > **View Logs**) — jauh lebih mudah
dibanding InfinityFree karena log real-time langsung terlihat di dashboard.

---

## Biaya (Free Tier)

Railway memberi **$5 credit gratis per bulan**. Untuk 3 service kecil (Web +
Reverb + MySQL) dengan traffic rendah, ini biasanya cukup untuk penggunaan
testing/personal. Kalau credit habis sebelum akhir bulan, service akan
di-pause sampai bulan berikutnya atau Anda upgrade ke plan berbayar.
