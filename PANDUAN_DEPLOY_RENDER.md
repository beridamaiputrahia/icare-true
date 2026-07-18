# Panduan Deploy "I Care True" ke Render (Free Tier, gratis selamanya)

Render free tier lebih stabil dari InfinityFree (bukan shared cPanel abal-abal,
tapi Docker container asli), gratis selamanya (bukan trial), dan punya cron
job asli. Kekurangan utamanya:

## ⚠️ Batasan Render Free Tier
- **Auto-sleep setelah idle 15 menit** — kalau tidak ada trafik, container
  dimatikan. Request pertama setelah itu akan lambat (~30-50 detik) karena
  container harus "bangun" (cold start) dulu.
- **Tidak ada WebSocket server terpisah** — Reverb (live chat/game real-time)
  tidak bisa jalan sebagai proses tambahan di free plan. `BROADCAST_CONNECTION`
  di-set ke `log` (fallback, perlu refresh manual).
- **Database gratisnya PostgreSQL, bukan MySQL** — migration project ini
  sudah disesuaikan supaya kompatibel dengan PostgreSQL (lihat catatan di
  bawah).
- **PostgreSQL free tier Render expired setelah 90 hari** — perlu backup &
  buat database baru setelah itu (gratis lagi, tapi manual).

## Yang sudah disiapkan di project ini
- `Dockerfile` — image PHP 8.3 + Apache, install ekstensi (pdo_mysql,
  pdo_pgsql, gd, dll), build asset frontend, jalankan lewat `docker/entrypoint.sh`.
- `docker/entrypoint.sh` — set port dinamis dari Render, jalankan cache config,
  storage:link, migrate, lalu start Apache.
- `render.yaml` — Blueprint config: 1 web service + 1 PostgreSQL database,
  auto-generate `APP_KEY`, auto-wire kredensial database ke env vars.
- `database/migrations/2026_06_28_224200_change_role_enum_on_users_table.php`
  — sudah diperbaiki supaya jalan di PostgreSQL maupun MySQL (migration ini
  tadinya pakai sintaks `ALTER TABLE ... MODIFY COLUMN ENUM(...)` yang
  spesifik MySQL dan akan gagal total di Postgres tanpa perbaikan ini).

## 1. Push ke GitHub

Sama seperti sebelumnya, kalau belum:
```
git remote remove origin
git remote add origin https://github.com/USERNAME_ANDA/icare-true.git
git push -u origin main-icare
```

## 2. Deploy via Blueprint (cara termudah, 1 klik)

1. Daftar/login di https://render.com dengan akun GitHub.
2. Klik **New** > **Blueprint**.
3. Pilih repo `icare-true`, branch `main-icare`.
4. Render akan otomatis membaca `render.yaml` di root project — akan membuat
   1 web service (`icare-true`) dan 1 database PostgreSQL (`icare-true-db`)
   sekaligus, dengan variabel database ter-hubung otomatis.
5. Klik **Apply** — Render mulai build Docker image (proses ini bisa
   5-10 menit untuk build pertama karena install semua dependency).

## 3. Isi variabel tambahan (SMTP, dll)

Setelah deploy pertama selesai, buka service `icare-true` > tab **Environment**,
lengkapi variabel yang belum otomatis terisi (lihat `.env.render.example`
sebagai referensi):
- `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`, dll — isi kalau butuh fitur
  email (wajib SMTP eksternal, misal Gmail App Password).
- `APP_URL` — Render kasih domain otomatis `xxx.onrender.com`, biasanya
  sudah otomatis ter-set benar, tapi cek dan sesuaikan kalau perlu.

Simpan perubahan — Render akan otomatis redeploy.

## 4. Seed data awal (opsional)

Render free tier tidak punya shell interaktif langsung dari dashboard untuk
`web` service gratis, tapi bisa lewat **Shell** tab (tersedia juga di free
plan untuk debugging):
1. Buka service `icare-true` > tab **Shell**.
2. Jalankan:
   ```
   php artisan db:seed --force
   ```

## 5. Cron job untuk notifikasi harian

Render punya **Cron Job** asli (gratis, terpisah dari web service):
1. Dashboard > **New** > **Cron Job**.
2. Connect ke repo yang sama, gunakan Dockerfile yang sama.
3. **Schedule**: `* * * * *`
4. **Command**: `php artisan schedule:run`
5. Isi environment variables yang sama dengan web service (terutama `DB_*`
   dan `APP_KEY` — harus identik supaya bisa akses database yang sama).

Ini akan menjalankan `notify:daily-verse` dan `notify:schedule-reminders`
sesuai jadwal di `routes/console.php` secara otomatis, tanpa workaround.

## 6. Test aplikasi

Buka `https://icare-true.onrender.com` (atau subdomain yang di-generate Render).
Load pertama mungkin lambat (cold start dari sleep) — ini normal untuk free
tier, bukan bug.

Cek log lewat dashboard Render (tab **Logs**) — real-time, jauh lebih mudah
dibanding baca file log manual seperti di InfinityFree.

## 7. Menjaga agar tidak sleep (opsional, trik komunitas)

Beberapa orang memakai layanan ping gratis seperti **cron-job.org** atau
**UptimeRobot** untuk mem-ping URL aplikasi tiap 10-14 menit supaya tidak
idle 15 menit dan auto-sleep. Ini legal (tidak melanggar ToS Render) tapi
tetap dalam batas wajar — jangan ping terlalu sering/agresif.

## 8. Kalau nanti mau live chat/game real-time (Reverb) beneran jalan

Render free tier tidak bisa menjalankan Reverb sebagai proses terpisah.
Alternatif tanpa pindah stack sepenuhnya: gunakan layanan broadcasting pihak
ketiga dengan free tier, seperti **Pusher** (gratis sampai 200 koneksi
bersamaan/hari) — ganti `BROADCAST_CONNECTION=pusher` dan isi kredensial
Pusher, tanpa perlu server Reverb sendiri. Beri tahu saya kalau mau saya
bantu setup ini.
