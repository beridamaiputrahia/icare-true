# Setup Ayat Harian Otomatis (tanpa perlu admin isi manual)

## Cara kerja
Setiap hari jam **06:00**, command `verse:generate-daily` otomatis:
1. Pilih 1 referensi ayat secara acak dari daftar ~125 ayat populer
   (`config/bible-verses.php`).
2. Ambil **teks resmi** ayat itu (Terjemahan Baru/TB) secara live dari
   **API.Bible** (`app/Services/BibleApiService.php`).
3. Simpan sebagai `DailyVerse` baru untuk hari itu, untuk **setiap tenant
   aktif** (multi-tenant tetap didukung — ayat bisa beda per tenant kalau
   nanti dikembangkan lebih lanjut, tapi sekarang isinya sama).

Jam **07:00**, command `notify:daily-verse` yang sudah ada mengirim
notifikasi ayat itu ke semua user aktif — tidak berubah dari sebelumnya.

Admin/superadmin **masih bisa** override manual lewat halaman Ayat Harian
kapan saja (fitur lama tidak dihapus) — kalau admin sudah isi ayat untuk
hari itu sebelum jam 06:00, sistem otomatis akan melewati tenant tersebut
(tidak menimpa yang sudah diisi manual).

## Langkah setup

### 1. Daftar akun API.Bible (gratis, non-komersial)
1. Buka https://scripture.api.bible/signup, daftar gratis (Google/GitHub/email).
2. Setelah login, klik **Create Application** (atau menu serupa di dashboard).
3. Beri nama aplikasi (misal `icare-true`), submit.
4. Salin **API Key** yang muncul.

> Catatan: free tier API.Bible hanya untuk penggunaan non-komersial. Karena
> aplikasi ini komunitas non-komersial, ini sesuai.

### 2. Set environment variable di Render
Buka dashboard Render > service `icare-true` > Environment Variables, isi:

```
API_BIBLE_KEY=<API Key dari langkah 1>
```

Simpan — Render akan otomatis redeploy.

### 3. WAJIB: pastikan Cron Job Render sudah aktif
Fitur ini (dan notifikasi ayat harian, pengingat jadwal) **hanya berjalan**
kalau Laravel Scheduler (`php artisan schedule:run`) benar-benar dipanggil
tiap menit. Render **tidak** menjalankan ini otomatis dari service web biasa
— perlu **Cron Job terpisah**:

1. Dashboard Render > **New** > **Cron Job**.
2. Connect ke repo yang sama (`icare-true`), gunakan Dockerfile yang sama.
3. **Schedule**: `* * * * *` (tiap menit).
4. **Command**: `php artisan schedule:run`
5. Isi environment variables yang SAMA PERSIS dengan service web (terutama
   `DB_*`, `APP_KEY`, `API_BIBLE_KEY` — harus identik supaya akses ke
   database dan API yang sama).

> Kalau langkah ini belum pernah di-setup sebelumnya, fitur notifikasi
> ayat harian dan pengingat jadwal juga belum pernah berjalan otomatis
> selama ini — bukan cuma fitur baru ini.

### 4. Test manual (tanpa menunggu jam 06:00)
Untuk langsung tes tanpa menunggu jadwal, jalankan command manual lewat
Render Shell (kalau tersedia) atau tunggu sampai jam 06:00 WIB berikutnya
setelah Cron Job aktif:

```
php artisan verse:generate-daily
```

Command ini menampilkan log per-tenant: berhasil generate ayat baru, atau
dilewati (karena sudah ada ayat untuk hari itu / gagal API).

### 5. Cek hasilnya
Buka halaman **Ayat Harian** di aplikasi — ayat baru untuk hari itu harus
muncul di paling atas daftar, dengan referensi dan teks dari Alkitab TB.

## Kalau API.Bible tidak menyediakan ayat (gagal)
`BibleApiService` akan melempar error yang di-log (bisa dicek di
`storage/logs/laravel.log` atau tab Logs Render), dan tenant tersebut
dilewati untuk hari itu. `DailyVerse::getToday()` akan otomatis fallback
ke ayat aktif terakhir yang tersedia — jadi tidak ada downtime, aplikasi
tidak akan menampilkan halaman kosong, hanya tidak dapat ayat baru hari itu.
